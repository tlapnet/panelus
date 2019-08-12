<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Dashboard;

use Tlapnet\Panelus\Control\InternManager;
use Tlapnet\Panelus\Layout\LayoutManager;
use Tlapnet\Panelus\Panel\PanelManager;
use Tlapnet\Panelus\UI\Dashboard\DashboardControl;
use Tlapnet\Panelus\Utils\Bag;

class DashboardFactory
{

	/** @var LayoutManager */
	protected $lm;

	/** @var PanelManager */
	protected $pm;

	/** @var InternManager */
	protected $im;

	public function __construct(LayoutManager $lm, PanelManager $pm, InternManager $im)
	{
		$this->lm = $lm;
		$this->pm = $pm;
		$this->im = $im;
	}

	/**
	 * @return DashboardControl|object
	 */
	public function create(Bag $bag)
	{
		$dashboard = new DashboardControl(
			new DashboardManager(
				$this->lm,
				$this->pm,
				$this->im,
				$bag
			),
			$bag
		);

		return $dashboard;
	}

}
