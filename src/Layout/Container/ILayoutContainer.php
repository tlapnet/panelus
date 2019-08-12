<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Layout\Container;

use Tlapnet\Panelus\Layout\Entity\Layout;

interface ILayoutContainer
{

	public function get(string $name): Layout;

	/**
	 * @param mixed[][] $extra
	 */
	public function merge(string $name, array ...$extra): Layout;

}
