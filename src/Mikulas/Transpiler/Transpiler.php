<?php declare(strict_types = 1);

namespace Mikulas\Transpiler;

use Mikulas\Transpiler\Modifiers\RemoveClassConstantVisibility;
use Mikulas\Transpiler\Modifiers\RemoveVoidReturnType;
use Mikulas\Transpiler\Modifiers\RolloutSquareBracketExpansion;
use PhpParser\Node;
use PhpParser\NodeTraverser;


class Transpiler
{

	/** @var NodeTraverser */
	private $traverser;


	public function __construct()
	{
		// TODO factory?
		$this->traverser = new NodeTraverser(TRUE);
		$this->traverser->addVisitor(new RemoveClassConstantVisibility());
		$this->traverser->addVisitor(new RemoveVoidReturnType());
		$this->traverser->addVisitor(new RolloutSquareBracketExpansion());
	}


	/**
	 * @param Node[] $nodes
	 * @return Node[]
	 */
	public function transpile(array $nodes): array
	{
		return $this->traverser->traverse($nodes);
	}

}
