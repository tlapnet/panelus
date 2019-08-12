<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Panelus\Unit\Layout\Container;

use PHPUnit\Framework\TestCase;
use Tests\Tlapnet\Panelus\Fixtures\Layout\AnimalLayout;
use Tlapnet\Panelus\Layout\Container\RawContainer;
use Tlapnet\Panelus\Layout\Entity\Layout;
use Tlapnet\Panelus\Layout\Entity\Rawprint;

final class RawContainerTest extends TestCase
{

	public function testGet(): void
	{
		$container = new RawContainer(new Rawprint(AnimalLayout::createRawLayout()));

		$layout1 = $container->get('animal');
		$this->assertInstanceOf(Layout::class, $layout1);

		$layout2 = $container->get('animal');
		$this->assertSame($layout1, $layout2);
	}

}
