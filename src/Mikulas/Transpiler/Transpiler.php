<?php declare(strict_types = 1);

namespace Mikulas\Transpiler;

use Mikulas\Transpiler\Modifiers\RemoveClassConstantVisibility;
use Mikulas\Transpiler\Modifiers\RemoveNullableReturnValues;
use Mikulas\Transpiler\Modifiers\RemoveVoidReturnType;
use Mikulas\Transpiler\Modifiers\ExpandNamedAssignment;
use Mikulas\Transpiler\Modifiers\RewriteNullableParameters;
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
		$this->traverser->addVisitor(new ExpandNamedAssignment());
		$this->traverser->addVisitor(new RemoveNullableReturnValues());
		$this->traverser->addVisitor(new RewriteNullableParameters());
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
