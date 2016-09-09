<?php declare(strict_types = 1);

namespace Mikulas\TranspilerBuild;

use Mikulas\Transpiler\SourceCodeProcessor;
use Mikulas\Transpiler\Transpiler;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use Symfony\Component\Finder\Finder;


class PathTranspiler
{

	/** @var SourceCodeProcessor */
	private $processor;

	/** @var SourceFinder */
	private $finder;


	public function __construct()
	{
		$parser = (new ParserFactory())->create(ParserFactory::ONLY_PHP7);
		$transpiler = new Transpiler();
		$printer = new Standard();
		$this->processor = new SourceCodeProcessor($parser, $transpiler, $printer);

		$this->finder = new SourceFinder(new Finder());
	}


	public function transpile(string $path)
	{
		foreach ($this->finder->findSourceFiles($path) as $file) {
			$source = $this->processor->transpile($file->getContents());
			file_put_contents($file->getRealPath(), $source);
			yield $file->getRealPath();
		}
	}

}
