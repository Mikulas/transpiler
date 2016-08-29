<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Modifiers;

use PhpParser\Node;
use PhpParser\NodeVisitor;
use PhpParser\NodeVisitorAbstract;


abstract class NodeFilteringVisitor extends NodeVisitorAbstract
{

	/**
	 * @see NodeVisitor::enterNode()
	 */
	final public function enterNode(Node $node)
	{
		if ($this->filter($node)) {
			return $this->transpile($node);
		}
		return NULL; // node stays as-is
	}


	/**
	 * When TRUE, transpile is called
	 * @param Node $node
	 * @return bool
	 */
	abstract public function filter(Node $node): bool;


	/**
	 * @param Node $node
	 */
	abstract public function transpile(Node $node): Node;

}
