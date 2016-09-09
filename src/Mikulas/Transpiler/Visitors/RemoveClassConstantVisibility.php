<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Visitors;

use PhpParser\Node;


class RemoveClassConstantVisibility extends NodeFilteringVisitor
{

	public function filter(Node $node): bool
	{
		return $node instanceof Node\Stmt\ClassConst;
	}


	/**
	 * @param Node\Stmt\ClassConst $node
	 */
	public function transpile(Node $node): Node
	{
		$node->flags &= ~Node\Stmt\Class_::VISIBILITY_MODIFER_MASK;
		return $node;
	}

}
