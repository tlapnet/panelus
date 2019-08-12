<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Layout;

use Tlapnet\Panelus\Layout\Container\ILayoutContainer;
use Tlapnet\Panelus\Layout\Entity\Layout;

class LayoutManager
{

	/** @var ILayoutContainer */
	protected $container;

	public function __construct(ILayoutContainer $container)
	{
		$this->container = $container;
	}

	public function get(string $layout): Layout
	{
		return $this->container->get($layout);
	}

	/**
	 * @param mixed[][] $extra
	 */
	public function merge(string $layout, array ...$extra): Layout
	{
		return $this->container->merge($layout, ...$extra);
	}

}
