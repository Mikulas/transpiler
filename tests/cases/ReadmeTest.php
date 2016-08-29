<?php declare(strict_types = 1);

namespace Tests\Mikulas\Transpiler;

use Mikulas\Transpiler\Transpiler;
use PhpParser\ParserFactory;


class ReadmeTest extends \PHPUnit_Framework_TestCase
{

	const README_FILE = __DIR__ . '/../../README.adoc';


	/**
	 * @dataProvider getReadmeSnippets
	 */
	public function testAllReadmeExamplesCompile(string $source71, string $source70)
	{
		$parser = (new ParserFactory)->create(ParserFactory::ONLY_PHP7);

		$prefix = "<?php\n";
		$nodes = $parser->parse($prefix . $source71);

		$transpiler = new Transpiler();
		$transpiler->transpile($nodes);

		if ($source70 !== '') {
			$expected = $parser->parse($prefix . $source70);
			static::assertEquals($expected, $nodes);
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
