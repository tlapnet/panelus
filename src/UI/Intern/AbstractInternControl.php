<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\UI\Intern;

use Nette\Application\UI\Control;
use Nette\Application\UI\ITemplate;
use Nette\Bridges\ApplicationLatte\Template;
use Tlapnet\Panelus\Utils\Bag;

/**
 * @property-read Template|ITemplate $template
 */
abstract class AbstractInternControl extends Control
{

	/** @var Bag */
	protected $bag;

	public function setBag(Bag $bag): void
	{
		$this->bag = $bag;
	}

}
