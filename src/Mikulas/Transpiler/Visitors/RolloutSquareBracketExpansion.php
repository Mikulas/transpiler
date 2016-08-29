<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Modifiers;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeVisitorAbstract;


class RolloutSquareBracketExpansion extends NodeVisitorAbstract
{

	/** @var int */
	private $newVariableId = 1;


	/**
	 * @return Node[]
	 */
	public function leaveNode(Node $node)
	{
		if (! $node instanceof Node\Expr\Assign || ! $node->var instanceof Node\Expr\Array_) {
			return NULL; // node stays as-is
		}

		ini_set('xdebug.var_display_max_depth', '6');

		// ['a' => $a, 'c' => $c] = ['a' => 1, 'b' => 2, 'c' => 3];
		// $leftArray             = $node->expression
		// # -->
		// ${'~transpiler-1'} = ['a' => 1, 'b' => 2, 'c' => 3];
		// $a = ${'~transpiler-1'}['a'];
		// $c = ${'~transpiler-1'}['c'];

		$nodes = [];
		$leftArray = $node->var;

		// ${'~transpiler-1'} = ['a' => 1, 'b' => 2, 'c' => 3];
		$rollout = new Node\Expr\Variable(
			new String_("~transpiler-{$this->newVariableId}")
		);
		$this->newVariableId += 1;
		$node->var = $rollout;
		$nodes[] = $node;

		// $a = ${'~transpiler-1'}['a'];
		/** @var Node\Expr\ArrayItem $item */
		foreach ($leftArray->items as $item) {
			/** @var Node\Expr\Variable $var */
			$nodes[] = new Node\Expr\Assign($item->value,
				new Node\Expr\ArrayDimFetch($rollout, $item->key)
			);
		}

		return $nodes;
	}

}
