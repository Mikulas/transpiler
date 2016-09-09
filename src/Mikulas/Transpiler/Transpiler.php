<?php declare(strict_types = 1);

namespace Mikulas\Transpiler;

use Mikulas\Transpiler\Visitors;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor as PhpParserVisitors;


class Transpiler
{

	const VERSION = '1.1.2';

	/** @var NodeTraverser */
	private $traverser;


	public function __construct()
	{
		$variableFactory = new VariableFactory();

		$this->traverser = new NodeTraverser(TRUE);
		$this->traverser->addVisitor(new PhpParserVisitors\NameResolver());

		$this->traverser->addVisitor(new Visitors\RemoveClassConstantVisibility());
		$this->traverser->addVisitor(new Visitors\RemoveVoidReturnType());
		$this->traverser->addVisitor(new Visitors\ExpandNamedAssignment($variableFactory));
		$this->traverser->addVisitor(new Visitors\RemoveNullableReturnValues());
		$this->traverser->addVisitor(new Visitors\RewriteNullableParameterType());
		$this->traverser->addVisitor(new Visitors\RemoveIterableReturnType());
		$this->traverser->addVisitor(new Visitors\RemoveIterableParameterType());
		$this->traverser->addVisitor(new Visitors\RewriteClosureFromCallable());
		$this->traverser->addVisitor(new Visitors\ExpandMultiCatchHandler());
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
