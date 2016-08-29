<?php declare(strict_types = 1);

namespace Tests\Mikulas\Transpiler;

use Mikulas\Transpiler\SourceCodeProcessor;
use Mikulas\Transpiler\Transpiler;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;


class Php71FeatureTest extends \PHPUnit_Framework_TestCase
{

	public function getFeatureList(): array
	{
		return [
			['voidReturnType'],
			['classConstantVisibility'],
			['namedSquareBracketExpansion'],
		];
	}


	/**
	 * @dataProvider getFeatureList
	 */
	public function testFeature(string $feature)
	{
		$this->assertTranspiledAs($feature);
	}


	private function assertTranspiledAs(string $fixture)
	{
		$parser = (new ParserFactory)->create(ParserFactory::ONLY_PHP7);
		$transpiler = new Transpiler();
		$printer = new PrettyPrinter\Standard();

		$processor = new SourceCodeProcessor($parser, $transpiler, $printer);

		$source71 = $this->getFixture("$fixture.php71");
		$transpiled = $processor->transpile($source71);
		$expected = $this->getFixture("$fixture.php70");

		static::assertSameExcludingEmptyLines($expected, $transpiled);
	}


	private function assertSameExcludingEmptyLines($expected, $actual, $message = '')
	{
		$expected = preg_replace('~\n\s*$~m', '', $expected);
		$actual = preg_replace('~\n\s*$~m', '', $actual);
		return static::assertSame($expected, $actual, $message);
	}


	private function getFixture(string $fixture): string
	{
		return file_get_contents(dirname(__DIR__) . "/fixtures/$fixture");
	}

}
