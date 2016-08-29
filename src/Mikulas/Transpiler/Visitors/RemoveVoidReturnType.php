<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Modifiers;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;


class RemoveVoidReturnType extends NodeVisitorAbstract
{

	public function enterNode(Node $node)
	{
		if ($node instanceof Node\Stmt\Function_
		 || $node instanceof Node\Stmt\ClassMethod
		 || $node instanceof Node\Expr\Closure)
		{
			$node->returnType = NULL;
			return $node;
		}
	}

}
