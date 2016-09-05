<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Modifiers;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;


class ExpandMultiCatchHandler extends NodeVisitorAbstract
{

	/**
	 * @return Node[]
	 */
	public function leaveNode(Node $node)
	{
		if (! $node instanceof Node\Stmt\Catch_) {
			return NULL; // node stays as-is, not assignment
		}
		if (count($node->types) <= 1) {
			return NULL; // node stays as-is, not assignment
		}

		$nodes = [];
		foreach ($node->types as $type) {
			$catch = clone $node;
			$catch->types = [$type];
			$nodes[] = $catch;
		}
		return $nodes;
	}

}
