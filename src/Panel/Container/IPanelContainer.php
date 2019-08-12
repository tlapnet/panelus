<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Panel\Container;

use Tlapnet\Panelus\Panel\IPanelControlFactory;

interface IPanelContainer
{

	public function has(string $name): bool;

	public function get(string $name): IPanelControlFactory;

}
