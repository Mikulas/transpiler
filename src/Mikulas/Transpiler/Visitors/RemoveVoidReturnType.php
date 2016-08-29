<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Modifiers;

use PhpParser\Node;


class RemoveVoidReturnType extends NodeFilteringVisitor
{

	/**
	 * When TRUE, transpile is called
	 *
	 * @param Node $node
	 * @return bool
	 */
	public function filter(Node $node): bool
	{
		return $node instanceof Node\Stmt\Function_
			|| $node instanceof Node\Stmt\ClassMethod
			|| $node instanceof Node\Expr\Closure;
	}


	/**
	 * @param Node\Stmt\Function_|Node\Stmt\ClassMethod|Node\Expr\Closure $node
	 */
	public function transpile(Node $node): Node
	{
		$node->returnType = NULL;
		return $node;
	}

}
