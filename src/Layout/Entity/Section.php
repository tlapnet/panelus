<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Layout\Entity;

use Tlapnet\Panelus\Exception\Logical\InvalidStateException;

class Section
{

	/** @var string */
	private $id;

	/** @var Panel[] */
	private $panels = [];

	/** @var Render */
	protected $render;

	public function __construct(string $id)
	{
		$this->id = $id;
		$this->render = new Render();
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getRender(): Render
	{
		return $this->render;
	}

	/**
	 * @return Panel[]
	 */
	public function getPanels(): array
	{
		return $this->panels;
	}

	/**
	 * @param Panel[] $panels
	 */
	public function setPanels(array $panels): void
	{
		$this->panels = [];
		$this->addPanels($panels);
	}

	/**
	 * @param Panel[] $panels
	 */
	public function addPanels(array $panels): void
	{
		foreach ($panels as $Panel) {
			$this->addPanel($Panel);
		}
	}

	public function addPanel(Panel $panel): void
	{
		if (isset($this->panels[$panel->getId()])) {
			throw new InvalidStateException(sprintf('Panel "%s" already registered', $panel->getId()));
		}

		$this->panels[$panel->getId()] = $panel;
	}

	public function lookupPanel(string $id): ?Panel
	{
		return $this->panels[$id] ?? null;
	}

}
