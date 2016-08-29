<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Modifiers;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;


class RemoveClassConstantVisibility extends NodeVisitorAbstract
{

	public function enterNode(Node $node)
	{
		if (!$node instanceof Node\Stmt\ClassConst) {
			return NULL;
		}

		// remove visibility bits
		$node->flags &= ~Node\Stmt\Class_::VISIBILITY_MODIFER_MASK;
		return $node;
	}

}
