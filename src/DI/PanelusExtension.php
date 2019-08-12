<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Statement;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;
use Tlapnet\Panelus\Control\InternManager;
use Tlapnet\Panelus\Dashboard\DashboardFactory;
use Tlapnet\Panelus\Exception\Logical\InvalidStateException;
use Tlapnet\Panelus\Layout\Container\RawContainer;
use Tlapnet\Panelus\Layout\Entity\Rawprint;
use Tlapnet\Panelus\Layout\LayoutManager;
use Tlapnet\Panelus\Panel\Container\PanelContainerContextAware;
use Tlapnet\Panelus\Panel\PanelManager;

/**
 * @property-read stdClass $config
 */
final class PanelusExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'layouts' => Expect::array(),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->config;

		if (!isset($config->layouts['default'])) {
			throw new InvalidStateException(
				sprintf('Missing layout `default`. Please register it under key %s.', $this->prefix('layouts.default'))
			);
		}

		$builder->addDefinition($this->prefix('panel.manager'))
			->setType(PanelManager::class);

		$builder->addDefinition($this->prefix('panel.container'))
			->setType(PanelContainerContextAware::class);

		$builder->addDefinition($this->prefix('layout.manager'))
			->setFactory(LayoutManager::class);

		$builder->addDefinition($this->prefix('layout.container'))
			->setFactory(RawContainer::class, [new Statement(Rawprint::class, [$config->layouts])]);

		$builder->addDefinition($this->prefix('dashboard.factory'))
			->setFactory(DashboardFactory::class);

		$builder->addDefinition($this->prefix('intern.manager'))
			->setFactory(InternManager::class);
	}

}
