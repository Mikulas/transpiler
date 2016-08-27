<?php declare(strict_types = 1);

namespace Mikulas\Transpiler;

use Mikulas\Transpiler\Modifiers\Chain;
use Mikulas\Transpiler\Modifiers\Modifier;
use Mikulas\Transpiler\Modifiers\RemoveClassConstantVisibility;
use Mikulas\Transpiler\Modifiers\RemoveVoidReturnType;
use PhpParser\Node;


class Transpiler
{

	/** @var Modifier */
	private $modifier;


	public function __construct()
	{
		// TODO factory?
		$this->modifier = new Chain([
			new RemoveVoidReturnType(),
			new RemoveClassConstantVisibility(),
		]);
	}


	/**
	 * @param Node[] $nodes
	 */
	public function transpile(array $nodes)
	{
		($this->modifier)($nodes);
	}

}
