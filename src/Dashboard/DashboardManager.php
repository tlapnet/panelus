<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Dashboard;

use Nette\ComponentModel\IComponent;
use Nette\Utils\Strings;
use Tlapnet\Panelus\Control\InternManager;
use Tlapnet\Panelus\Exception\Logical\InvalidStateException;
use Tlapnet\Panelus\Layout\Entity\Layout;
use Tlapnet\Panelus\Layout\Entity\Panel;
use Tlapnet\Panelus\Layout\LayoutManager;
use Tlapnet\Panelus\Panel\PanelManager;
use Tlapnet\Panelus\Panelus;
use Tlapnet\Panelus\UI\Dashboard\DashboardControl;
use Tlapnet\Panelus\UI\Intern\AbstractInternControl;
use Tlapnet\Panelus\UI\Panel\AbstractPanelControl;
use Tlapnet\Panelus\Utils\Bag;
use Tlapnet\Panelus\Utils\Names;

class DashboardManager
{

	/** @var LayoutManager */
	protected $lm;

	/** @var PanelManager */
	protected $pm;

	/** @var InternManager */
	protected $im;

	/** @var Bag */
	protected $bag;

	/** @var Layout|null */
	protected $layout;

	public function __construct(LayoutManager $lm, PanelManager $pm, InternManager $im, Bag $bag)
	{
		$this->lm = $lm;
		$this->pm = $pm;
		$this->im = $im;
		$this->bag = $bag;
	}

	/**
	 * Lazily creates panel or internal components.
	 *
	 * - Dashboard panel can have only panel subcomponents.
	 *    - Except components starts with _ (internal components)
	 */
	public function createComponent(string $name): IComponent
	{
		// Components starting with _ means internal!
		if (Strings::startsWith($name, '_')) {
			return $this->createInternComponent($name);
		}

		return $this->createPanelComponent($name);
	}

	public function createPanelComponent(string $name): AbstractPanelControl
	{
		// Lookup panel in layout
		$panel = $this->getLayout()->lookupPanel($name);

		if ($panel === null) {
			throw new InvalidStateException(sprintf('Panel "%s" is not defined in layout', $name));
		}

		// Create and setup panel
		$control = $this->createPanel($panel);

		return $control;
	}

	/**
	 * Instant, initialize, configure panel control by given panel entity.
	 */
	public function createPanel(Panel $panel): AbstractPanelControl
	{
		// Detect panel name
		$name = Names::panel($panel->getId());

		// Creates panel control
		$control = $this->pm->get($name)->createControl();

		// Propagates into panel control
		$control->initialize($panel, $this->bag);

		// Called after component is added to component tree and has all dependencies
		$control->monitor(DashboardControl::class, function (DashboardControl $dashboard) use ($control): void {
			$control->configure();
		});

		return $control;
	}

	public function createInternComponent(string $name): AbstractInternControl
	{
		$component = $this->im->create(substr($name, 1));

		// Propagates user bag into intern component
		$component->setBag($this->bag);

		return $component;
	}

	public function getLayout(): Layout
	{
		if ($this->layout === null) {
			// Get layout name from given control bag.
			// This layout is used to be base for all TemplateProcess and TemplateStep modifications.
			$layoutName = $this->bag->get(Panelus::BAG_LAYOUT_EXTENDS);

			// Get extra layout chain schema from given control bag.
			// It applies multiple layouts.
			if (($layoutChain = $this->bag->get(Panelus::BAG_LAYOUT_CHAIN, null)) !== null) {
				return $this->lm->merge($layoutName, ...$layoutChain);
			}

			// Create just simple layouts (no extra modifications)
			$this->layout = $this->lm->get($layoutName);
		}

		return $this->layout;
	}

}
