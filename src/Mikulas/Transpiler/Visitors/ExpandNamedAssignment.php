<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Visitors;

use Mikulas\Transpiler\VariableFactory;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;


class ExpandNamedAssignment extends NodeVisitorAbstract
{

	/** @var VariableFactory */
	private $variableFactory;


	public function __construct(VariableFactory $variableFactory)
	{
		$this->variableFactory = $variableFactory;
	}


	public function enterNode(Node $node)
	{
		// transform
		//  while (list(...) = $right) {$block;}
		//  foreach ($left as list(...)) {$block;}
		// to
		//  while ($temp = $right) {list(...) = $temp; $block;}
		//  foreach ($left as $temp) {list(...) = $temp; $block;}

		if (
			$node instanceof Node\Stmt\While_ &&
			$node->cond instanceof Node\Expr\Assign &&
			$node->cond->var instanceof Node\Expr\List_
		) {
			$list = $node->cond->var;
			$tempVar = $this->variableFactory->create();
			$node->cond->var = $tempVar;
			$assignment = new Node\Expr\Assign($list, $tempVar);
			array_unshift($node->stmts, $assignment);

		} elseif ($node instanceof Node\Stmt\Foreach_) {
			$list = $node->valueVar;
			$tempVar = $this->variableFactory->create();
			$node->valueVar = $tempVar;
			$assignment = new Node\Expr\Assign($list, $tempVar);
			array_unshift($node->stmts, $assignment);
		}
	}


	/**
	 * @return Node[]
	 */
	public function leaveNode(Node $node)
	{
		if (! $node instanceof Node\Expr\Assign) {
			return NULL; // node stays as-is, not assignment
		}
		if (! $node->var instanceof Node\Expr\Array_ && ! $node->var instanceof Node\Expr\List_) {
			return NULL; // node stays as-is, simple assignment
		}

		// ['a' => $a, 'c' => $c] = ['a' => 1, 'b' => 2, 'c' => 3];
		// $leftSide              = $node->expression
		// # -->
		// ${'~transpiler-1'} = ['a' => 1, 'b' => 2, 'c' => 3];
		// $a = ${'~transpiler-1'}['a'];
		// $c = ${'~transpiler-1'}['c'];

		$nodes = [];
		$leftSide = $node->var;

		if ($node->expr instanceof Node\Expr\Variable) {
			// simple case `list() = $var`, temp variable is not required
			$rollout = $node->expr;
		} else {
			// ${'~transpiler-1'} = ['a' => 1, 'b' => 2, 'c' => 3];
			$rollout = $this->variableFactory->create();
			$node->var = $rollout;
			$nodes[] = $node;
		}

		// $a = ${'~transpiler-1'}['a'];
		foreach ($this->getVariableDimensions($leftSide->items) as list($variable, $dimensions)) {
			/** @var Node\Expr\Variable $var */
			$nodes[] = new Node\Expr\Assign($variable,
				$this->nestedDimFetch($rollout, $dimensions)
			);
		}

		return $nodes;
	}


	private function nestedDimFetch(Node\Expr $var, array $dimensions): Node\Expr\ArrayDimFetch
	{
		foreach ($dimensions as $dimension) {
			$var = new Node\Expr\ArrayDimFetch($var, $dimension);
		}
		return $var;
	}


	/**
	 * Convert complex left sides such as
	 * [['A' => $a], ['B' => $b]]
	 * to
	 * [$a, [0, 'A']], [$b, [0, 'B]]
	 *
	 * @param Node\Expr\ArrayItem[] $items
	 * @return array [[variable, dimension stack], ...]
	 */
	private function getVariableDimensions(array $items, array $stack = [])
	{
		foreach ($items as $index => $item) {
			$newStack = $stack;

			if ($item instanceof Node\Expr\List_) {
				$newStack[] = new Node\Scalar\LNumber($index);
				yield from $this->getVariableDimensions($item->items, $newStack);
				continue;

			} elseif ($item === NULL) {
				// happens when list(, $second)
				continue;
			}

			assert($item instanceof Node\Expr\ArrayItem);
			$newStack[] = $item->key === NULL ? new Node\Scalar\LNumber($index) : $item->key;

			if ($item->value instanceof Node\Expr\Array_  || $item->value instanceof Node\Expr\List_) {
				yield from $this->getVariableDimensions($item->value->items, $newStack);

			} else {
				yield [$item->value, $newStack];
			}
		}

		return [];
	}

}
