<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Panelus\Unit\Utils;

use PHPUnit\Framework\TestCase;
use Tests\Tlapnet\Panelus\Fixtures\Layout\AnimalLayout;
use Tlapnet\Panelus\Layout\Entity\Layout;
use Tlapnet\Panelus\Utils\Dumper;

final class DumperLayoutTest extends TestCase
{

	/**
	 * @dataProvider layoutProvider
	 * @param mixed[] $arr
	 */
	public function testDump(Layout $layout, array $arr): void
	{
		$this->assertEquals($arr, Dumper::dumpLayout($layout));
	}

	/**
	 * @return mixed[]
	 */
	public function layoutProvider(): array
	{
		return [
			[new Layout('lid'), ['lid' => ['sections' => []]]],
			[AnimalLayout::createLayout(), AnimalLayout::createRawLayout()],
		];
	}

}
