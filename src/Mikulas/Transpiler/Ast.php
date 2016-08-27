<?php declare(strict_types = 1);

namespace Mikulas\Transpiler;

use PhpParser\Node;


class Ast
{

	/**
	 * Runs $fn recursively on all Nodes
	 * @param Node[]   $nodes
	 * @param callable $fn
	 */
	public static function recursiveMap(array $nodes, callable $fn)
	{
		$stack = $nodes;
		while ($node = array_shift($stack)) {
			$fn($node);
			if ($node instanceof Node\Stmt\ClassLike) {
				array_push($stack, ...$node->stmts);
			}
		}
	}

}
