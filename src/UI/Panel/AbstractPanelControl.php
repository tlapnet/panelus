<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\UI\Panel;

use Nette\Application\UI\Control;
use Nette\Application\UI\ITemplate;
use Nette\Bridges\ApplicationLatte\Template;
use Tlapnet\Panelus\Layout\Entity\Panel;
use Tlapnet\Panelus\Utils\Bag;

/**
 * @property-read Template|ITemplate $template
 */
abstract class AbstractPanelControl extends Control
{

	/** @var Panel */
	protected $panel;

	/** @var mixed[] */
	protected $configuration = [];

	/** @var Bag */
	protected $bag;

	public function getPanel(): Panel
	{
		return $this->panel;
	}

	/**
	 * @return mixed[]
	 */
	public function getConfiguration(): array
	{
		return $this->configuration;
	}

	public function getBag(): Bag
	{
		return $this->bag;
	}

	/**
	 * Drain configuration from panel to separate these layers.
	 * Sometimes we need to track panel itself, so thus there
	 * are the reference to panel entity.
	 *
	 * Dashboard bag is holding key => value information
	 * about dashboard.
	 *
	 * Called before component is attached to component tree.
	 */
	public function initialize(Panel $panel, Bag $bag): void
	{
		$this->panel = $panel;
		$this->configuration = $panel->getConfig();
		$this->bag = $bag;
	}

	/**
	 * Configure and setup component before render.
	 *
	 * Called after panel and control bag is provided.
	 * Also after component is attached to component tree.
	 */
	public function configure(): void
	{
	}

}
