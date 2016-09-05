<?php declare(strict_types = 1);

namespace Tests\Mikulas\Transpiler;

use PhpParser\ParserFactory;
use Tests\Helper;


class ReadmeTest extends \PHPUnit_Framework_TestCase
{

	const README_FILE = __DIR__ . '/../../README.adoc';


	/**
	 * @dataProvider getReadmeSnippets
	 */
	public function testAllReadmeExamplesCompile(string $input, string $expected)
	{
		$parser = (new ParserFactory)->create(ParserFactory::ONLY_PHP7);

		$prefix = "<?php\n";
		$input = "$prefix$input";

		$parser->parse($input);

		if ($expected !== '') {
			$expected = "$prefix$expected";
			Helper::assertTranspiledAs($expected, $input);
		}
	}


	public function getReadmeSnippets()
	{
		$raw = file_get_contents(static::README_FILE);
		$matches = [];
		preg_match_all('~```(?P<php71>.*?)(# -->(?P<php70>.*?))?```~s', $raw, $matches);

		foreach ($matches['php71'] as $i => $_) {
			yield [
				$matches['php71'][$i],
				$matches['php70'][$i],
			];
		}
	}

}
