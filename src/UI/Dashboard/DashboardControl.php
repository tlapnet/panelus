<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\UI\Dashboard;

use Nette\Application\UI\Control;
use Nette\Application\UI\ITemplate;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\ComponentModel\IComponent;
use Tlapnet\Panelus\Dashboard\DashboardManager;
use Tlapnet\Panelus\Layout\Entity\Panel;
use Tlapnet\Panelus\UI\Panel\AbstractPanelControl;
use Tlapnet\Panelus\Utils\Bag;

/**
 * @property-read Template|ITemplate $template
 */
class DashboardControl extends Control
{

	/** @var DashboardManager */
	protected $dm;

	/** @var Bag */
	protected $bag;

	public function __construct(DashboardManager $dm, Bag $bag)
	{
		$this->dm = $dm;
		$this->bag = $bag;
	}

	/**
	 * Pass creating to control creator
	 */
	protected function createComponent(string $name): IComponent
	{
		return $this->dm->createComponent($name);
	}

	/**
	 * Instant, initialize, configure panel control by given panel entity.
	 */
	public function createPanelComponent(Panel $panel): AbstractPanelControl
	{
		return $this->dm->createPanel($panel);
	}

	public function render(): void
	{
		$this->template->setFile(__DIR__ . '/templates/dashboard.latte');
		$this->template->layout = $this->dm->getLayout();
		$this->template->render();
	}

}
