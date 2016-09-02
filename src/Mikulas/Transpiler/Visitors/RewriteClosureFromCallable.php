<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Modifiers;

use PhpParser\Node;


class RewriteClosureFromCallable extends NodeFilteringVisitor
{

	const ARGS_NAME = 'args';


	public function filter(Node $node): bool
	{
		return $node instanceof Node\Expr\StaticCall
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

		$param = new Node\Param(self::ARGS_NAME);
		$param->variadic = TRUE;
		$closure->params = [$param];

		if ($callable->value instanceof Node\Expr\Array_) {
			$value = $callable->value->items[0]->value;
			if ($value instanceof Node\Expr\Variable) {
				$closure->uses[] = new Node\Expr\ClosureUse($value->name, TRUE);
			}
		}

		$arg = new Node\Arg(new Node\Expr\Variable(self::ARGS_NAME));

		$call = new Node\Expr\FuncCall(new Node\Name('call_user_func_array'), [$callable, $arg]);
		$closure->stmts[] = new Node\Stmt\Return_($call);

		return $closure;
	}

}
