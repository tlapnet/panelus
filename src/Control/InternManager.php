<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Control;

use Nette\DI\Container;
use Tlapnet\Panelus\Exception\Logical\InvalidStateException;
use Tlapnet\Panelus\UI\Intern\AbstractInternControl;

class InternManager
{

	/** @var Container */
	protected $context;

	/** @var string[] */
	protected $map = [];

	public function __construct(Container $context)
	{
		$this->context = $context;
	}

	public function add(string $control, string $service): void
	{
		$this->map[$control] = $service;
	}

	public function create(string $control): AbstractInternControl
	{
		if (!isset($this->map[$control])) {
			throw new InvalidStateException(sprintf('Undefined internal component "%s"', $control));
		}

		/** @var AbstractInternControl $obj */
		$obj = $this->context->getByType($this->map[$control]);

		return $obj;
	}

}
