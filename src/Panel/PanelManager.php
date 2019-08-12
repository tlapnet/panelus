<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Panel;

use Tlapnet\Panelus\Panel\Container\IPanelContainer;

class PanelManager
{

	/** @var IPanelContainer */
	protected $container;

	public function __construct(IPanelContainer $container)
	{
		$this->container = $container;
	}

	public function has(string $name): bool
	{
		return $this->container->has($name);
	}

	public function get(string $name): IPanelControlFactory
	{
		return $this->container->get($name);
	}

}
