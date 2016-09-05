<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Modifiers;

use Mikulas\Transpiler\VariableFactory;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;


class RewriteNegativeStringOffsets extends NodeVisitorAbstract
{

	/** @var VariableFactory */
	private $variableFactory;


	public function __construct(VariableFactory $variableFactory)
	{
		$this->variableFactory = $variableFactory;
	}


	public function leaveNode(Node $node)
	{
		if ($node instanceof Node\Expr\ArrayDimFetch && $this->isNegative($node->dim)) {
			return $this->transpileNegativeDim($node);
		}
		var_dump($node);
		die;
	}


	private function transpileNegativeDim(Node\Expr\ArrayDimFetch $node)
	{
		var_dump($node);
	}


	private function isNegative(Node\Expr $expr): bool
	{
		return $expr instanceof Node\Expr\UnaryMinus || (
			$expr instanceof Node\Scalar\LNumber && $expr->value < 0
		);
	}

}
