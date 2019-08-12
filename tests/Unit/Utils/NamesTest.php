<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Panelus\Unit\Utils;

use PHPUnit\Framework\TestCase;
use Tlapnet\Panelus\Utils\Names;

final class NamesTest extends TestCase
{

	/**
	 * @dataProvider panelProvider
	 */
	public function testPanel(string $input, string $expected): void
	{
		$this->assertEquals($expected, Names::panel($input));
	}

	/**
	 * @return mixed[]
	 */
	public function panelProvider(): array
	{
		return [
			['', ''],
			['simple', 'simple'],
			['starting__', 'starting'],
			['__ending', ''],
			['panel__1', 'panel'],
			['panel__1__suffix', 'panel'],
		];
	}

}
