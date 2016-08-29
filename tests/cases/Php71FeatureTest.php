<?php declare(strict_types = 1);

namespace Tests\Mikulas\Transpiler;

use Mikulas\Transpiler\Transpiler;
use PhpParser\ParserFactory;


class Php71FeatureTest extends \PHPUnit_Framework_TestCase
{

	public function testVoidReturnType()
	{
		$this->assertTranspiledAs('voidReturnType');
	}


	public function testClassConstantVisibility()
	{
		$this->assertTranspiledAs('classConstantVisibility');
	}


//	public function testNamedSquareBracketExpansion()
//	{
//		$this->assertTranspiledAs('namedSquareBracketExpansion');
//	}


	private function assertTranspiledAs(string $fixture)
	{
		$parser = (new ParserFactory)->create(ParserFactory::ONLY_PHP7);

		$source71 = $this->getFixture("$fixture.php71");
		$nodes = $parser->parse($source71);

		$transpiler = new Transpiler();
		$transpiler->transpile($nodes);

		$source70 = $this->getFixture("$fixture.php70");
		$expected = $parser->parse($source70);

		static::assertEquals($expected, $nodes);
	}


	private function getFixture(string $fixture): string
	{
		return file_get_contents(dirname(__DIR__) . "/fixtures/$fixture");
	}

}
