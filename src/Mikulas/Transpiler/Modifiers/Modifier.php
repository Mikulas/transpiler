<?php declare(strict_types = 1);

namespace Mikulas\Transpiler\Modifiers;

use PhpParser\Node;


interface Modifier
{

	/**
	 * @param Node[] $nodes
	 * @return Node[]
	 */
	public function __invoke(array $nodes);

}
