<?php declare(strict_types = 1);

namespace Mikulas\Transpiler;

use PhpParser\Parser;
use PhpParser\PrettyPrinterAbstract;


class SourceCodeProcessor
{

	/** @var Parser */
	private $parser;

	/** @var Transpiler */
	private $transpiler;

	/** @var PrettyPrinterAbstract */
	private $printer;


	public function __construct(Parser $parser, Transpiler $transpiler, PrettyPrinterAbstract $printer)
	{
		$this->parser = $parser;
		$this->transpiler = $transpiler;
		$this->printer = $printer;
	}


	/**
	 * @param string $sourceCode
	 * @return string transpiled source
	 */
	public function transpile(string $sourceCode): string
	{
		$oldNodes = $this->parser->parse($sourceCode);
		$newNodes = $this->transpiler->transpile($oldNodes);
		return $this->printer->prettyPrintFile($newNodes);
	}

}
