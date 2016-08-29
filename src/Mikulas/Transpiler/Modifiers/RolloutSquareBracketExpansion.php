<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Modifiers;

use Mikulas\Transpiler\Ast;
use PhpParser\Node;


class RolloutSquareBracketExpansion implements Modifier
{

	/**
	 * @param Node[] $nodes
	 * @return Node[]
	 */
	public function __invoke(array $nodes)
	{
		ini_set('xdebug.var_display_max_depth', '6');

		$counter = 1;
		Ast::recursiveMap($nodes, function(Node $node) use (&$counter) {
			if ($node instanceof Node\Expr\Assign && $node->var instanceof Node\Expr\Array_) {
				$leftArray = $node->var;

				$rollout = new Node\Expr\Variable("~transpiler-$counter");
				$node->var = $rollout;

				// TODO iterator instance that can modify the tree inplace

				$counter += 1;
			}
		});
	}

}
