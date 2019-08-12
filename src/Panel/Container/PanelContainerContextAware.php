<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Panel\Container;

use Nette\DI\Container;
use Nette\Utils\Strings;
use Tlapnet\Panelus\Exception\Logical\InvalidArgumentException;
use Tlapnet\Panelus\Panel\IPanelControlFactory;

class PanelContainerContextAware implements IPanelContainer
{

	/** @var IPanelControlFactory[] */
	private $initialized = [];

	/** @var string[] */
	private $map = [];

	/** @var Container */
	private $context;

	public function __construct(Container $context)
	{
		$this->context = $context;
	}

	public function has(string $name): bool
	{
		return isset($this->map[$name]);
	}

	public function add(string $name, string $panel): void
	{
		// Validate panel name
		if (Strings::match($name, '#^[a-zA-Z0-9]+[a-zA-Z0-9_]+$#') === null) {
			throw new InvalidArgumentException(sprintf('Invalid name "%s" given. Allowed characters [a-zA-Z0-9]+[a-zA-Z0-9_]+.', $name));
		}

		if ($this->has($name)) {
			throw new InvalidArgumentException(sprintf('Panel %s already exists.', $name));
		}

		$this->map[$name] = $panel;
	}

	public function get(string $name): IPanelControlFactory
	{
		if (!$this->has($name)) {
			throw new InvalidArgumentException(sprintf('Panel %s does not exist.', $name));
		}

		if (!isset($this->initialized[$name])) {
			$this->initialized[$name] = $this->context->getService($this->map[$name]);
		}

		return $this->initialized[$name];
	}

	/**
	 * @return string[]
	 */
	public function getMap(): array
	{
		return array_keys($this->map);
	}

}
