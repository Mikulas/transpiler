<?php declare(strict_types = 1);

namespace Mikulas\TranspilerBuild;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;


class SourceFinder
{

	/** @var Finder */
	private $finder;


	public function __construct(Finder $finder)
	{
		$this->finder = $finder;
	}


	/**
	 * @param string $path
	 * @return SplFileInfo[]
	 */
	public function findSourceFiles(string $path)
	{
		yield from $this->finder->files()->name('/\.phpt?$/i')->in($path);
	}

}
