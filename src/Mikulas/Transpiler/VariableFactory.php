<?php declare(strict_types = 1);

namespace Mikulas\Transpiler;

use PhpParser\Node;


class VariableFactory
{

	/**
	 * @var int
	 */
	private $index = 0;


	public function create(): Node\Expr\Variable
	{
		$this->index += 1;
		return new Node\Expr\Variable(
			new Node\Scalar\String_("~transpiler-{$this->index}")
		);
	}

}
