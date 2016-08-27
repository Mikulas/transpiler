<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Modifiers;

use PhpParser\Node;


class Chain implements Modifier
{

	/** @var Modifier[] */
	private $modifiers = [];


	public function __construct(array $modifiers = [])
	{
		foreach ($modifiers as $modifier) {
			$this->registerModifier($modifier);
		}
	}


	public function registerModifier(Modifier $modifier)
	{
		$this->modifiers[] = $modifier;
	}


	/**
	 * @param Node[] $nodes
	 * @return Node[]
	 */
	public function __invoke(array $nodes)
	{
		foreach ($this->modifiers as $modifier) {
			$modifier($nodes);
		}
	}

}
