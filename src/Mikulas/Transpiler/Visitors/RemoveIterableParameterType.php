<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Modifiers;

use PhpParser\Node;


class RemoveIterableParameterType extends NodeFilteringVisitor
{

	public function filter(Node $node): bool
	{
		return $node instanceof Node\Param
			&& $node->type === 'iterable';
	}


	/**
	 * @param Node\Param $node
	 */
	public function transpile(Node $node): Node
	{
		$node->type = NULL;
		return $node;
	}

}
