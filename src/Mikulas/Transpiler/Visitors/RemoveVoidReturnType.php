<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Modifiers;

use PhpParser\Node;


class RemoveVoidReturnType extends NodeFilteringVisitor
{

	public function filter(Node $node): bool
	{
		return $node instanceof Node\FunctionLike
			&& $node->getReturnType() === 'void';
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
