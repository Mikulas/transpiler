<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Modifiers;

use Mikulas\Transpiler\Ast;
use PhpParser\Node;


class RemoveVoidReturnType implements Modifier
{

	/**
	 * @param Node[] $nodes
	 * @return Node[]
	 */
	public function __invoke(array $nodes)
	{
		Ast::recursiveMap($nodes, function(Node $node) {
			if ($node instanceof Node\Stmt\Function_
			 || $node instanceof Node\Stmt\ClassMethod
			 || $node instanceof Node\Expr\Closure) {
				$node->returnType = NULL;
			}
		});
	}

}
