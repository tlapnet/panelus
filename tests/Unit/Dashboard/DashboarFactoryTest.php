<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Panelus\Unit\Dashboard;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tlapnet\Panelus\Control\InternManager;
use Tlapnet\Panelus\Dashboard\DashboardFactory;
use Tlapnet\Panelus\Layout\LayoutManager;
use Tlapnet\Panelus\Panel\PanelManager;
use Tlapnet\Panelus\UI\Dashboard\DashboardControl;
use Tlapnet\Panelus\Utils\Bag;

final class DashboarFactoryTest extends TestCase
{

	public function testCreate(): void
	{
		$factory = new DashboardFactory(
			Mockery::mock(LayoutManager::class),
			Mockery::mock(PanelManager::class),
			Mockery::mock(InternManager::class)
		);

		$control = $factory->create(new Bag());
		$this->assertInstanceOf(DashboardControl::class, $control);
	}

}
