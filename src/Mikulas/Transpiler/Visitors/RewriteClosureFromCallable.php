<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Visitors;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;


class RewriteClosureFromCallable extends NodeFilteringVisitor
{

	public function filter(Node $node): bool
	{
		return $node instanceof Node\Expr\StaticCall
			&& $node->class instanceof Node\Name
			&& $node->class->toString() === 'Closure' // TODO properly resolve to FQN
			&& $node->name === 'fromCallable';
	}


	/**
	 * @param Node\Expr\StaticCall $node
	 */
	public function transpile(Node $node): Node
	{
		$closure = new Node\Expr\Closure();

		/** @var Node\Arg $callable */
		$callable = $node->args[0];

		// extract all variables for use
		$traverser = new NodeTraverser();
		$traverser->addVisitor(new class($closure) extends NodeVisitorAbstract {
			/** @var Node\Expr\Closure */
			private $closure;

			public function __construct(Node\Expr\Closure $closure) {
				$this->closure = $closure;
			}

			public function enterNode(Node $node) {
				if ($node instanceof Node\Expr\Variable) {
					$this->closure->uses[] = new Node\Expr\ClosureUse($node->name, TRUE);
				}
			}
		});
		$traverser->traverse($node->args);

		$arg = new Node\Arg(new Node\Expr\FuncCall(new Node\Name('func_get_args')));

		$call = new Node\Expr\FuncCall(new Node\Name('call_user_func_array'), [$callable, $arg]);
		$closure->stmts[] = new Node\Stmt\Return_($call);

		return $closure;
	}

}
