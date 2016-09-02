<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Modifiers;

use PhpParser\Node;


class RewriteNullableParameterType extends NodeFilteringVisitor
{

	public function filter(Node $node): bool
	{
		return $node instanceof Node\Param
			&& $node->type instanceof Node\NullableType;
	}


	/**
	 * @param Node\Param $node
	 */
	public function transpile(Node $node): Node
	{
		/** @var Node\NullableType $nullable */
		$nullable = $node->type;
		$node->type = $nullable->type;
		$node->default = new Node\Expr\ConstFetch(new Node\Name('NULL'));
		return $node;
	}

}
