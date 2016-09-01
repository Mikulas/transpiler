<?php declare(strict_types = 1);

namespace Tests;

use Mikulas\Transpiler\SourceCodeProcessor;
use Mikulas\Transpiler\Transpiler;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use PHPUnit\Framework\TestCase;


class Helper
{

	public static function assertTranspiledAs(string $expected, string $input)
	{
		$parser = (new ParserFactory)->create(ParserFactory::ONLY_PHP7);
		$transpiler = new Transpiler();
		$printer = new PrettyPrinter\Standard();

		$processor = new SourceCodeProcessor($parser, $transpiler, $printer);

		$transpiled = $processor->transpile($input);

		static::assertSameExcludingEmptyLines($expected, $transpiled);
	}


	public static function getFixture(string $fixture): string
	{
		return file_get_contents(dirname(__DIR__) . "/fixtures/$fixture");
	}


	private static function assertSameExcludingEmptyLines($expected, $actual, $message = '')
	{
		$expected = preg_replace('~\n\s*$~m', '', $expected);
		$actual = preg_replace('~\n\s*$~m', '', $actual);
		TestCase::assertSame($expected, $actual, $message);
	}

}
