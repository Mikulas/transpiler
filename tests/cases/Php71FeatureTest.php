<?php declare(strict_types = 1);

namespace Tests\Mikulas\Transpiler;

use Tests\Helper;


class Php71FeatureTest extends \PHPUnit_Framework_TestCase
{

	public function getFeatureList(): array
	{
		return [
//			['voidReturnType'],
//			['classConstantVisibility'],
//			['namedAssignment'],
//			['nullableTypes'],
//			['iterable'],
			['closureFromCallable'],
		];
	}


	/**
	 * @dataProvider getFeatureList
	 */
	public function testFeature(string $feature)
	{
		$input = Helper::getFixture("$feature.php71");
		$expected = Helper::getFixture("$feature.php70");
		Helper::assertTranspiledAs($expected, $input);
	}

}
