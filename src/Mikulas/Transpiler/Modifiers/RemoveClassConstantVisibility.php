<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Modifiers;

use Mikulas\Transpiler\Ast;
use PhpParser\Node;


class RemoveClassConstantVisibility implements Modifier
{

	/**
	 * @param Node[] $nodes
	 * @return Node[]
	 */
	public function __invoke(array $nodes)
	{
		Ast::recursiveMap($nodes, function(Node $node) {
			if ($node instanceof Node\Stmt\ClassConst) {
				// remove visibility bits
				$node->flags = $node->flags & ~Node\Stmt\Class_::VISIBILITY_MODIFER_MASK;
			}
		});
	}

}
