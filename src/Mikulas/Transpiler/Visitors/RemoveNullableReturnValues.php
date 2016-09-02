<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Modifiers;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;


class RemoveNullableReturnValues extends NodeFilteringVisitor
{

	public function filter(Node $node): bool
	{
		return $node instanceof FunctionLike
			&& $node->getReturnType() instanceof Node\NullableType;
	}


	/**
	 * @param FunctionLike $node
	 */
	public function transpile(Node $node): Node
	{
		$node->returnType = NULL;
		return $node;
	}

}
