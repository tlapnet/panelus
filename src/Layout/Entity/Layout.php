<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Layout\Entity;

use Tlapnet\Panelus\Exception\Logical\InvalidStateException;

class Layout
{

	/** @var string */
	private $id;

	/** @var Section[] */
	protected $sections = [];

	public function __construct(string $id)
	{
		$this->id = $id;
	}

	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @return Section[]
	 */
	public function getSections(): array
	{
		return $this->sections;
	}

	/**
	 * @param Section[] $sections
	 */
	public function setSections(array $sections): void
	{
		$this->sections = [];
		$this->addSections($sections);
	}

	/**
	 * @param Section[] $sections
	 */
	public function addSections(array $sections): void
	{
		foreach ($sections as $section) {
			$this->addSection($section);
		}
	}

	public function addSection(Section $section): void
	{
		if (isset($this->sections[$section->getId()])) {
			throw new InvalidStateException(sprintf('Section "%s" already registered', $section->getId()));
		}

		$this->sections[$section->getId()] = $section;

		// Sort section by id(s)
		ksort($this->sections, SORT_NATURAL);
	}

	public function lookupPanel(string $id): ?Panel
	{
		$panels = [];

		foreach ($this->getSections() as $section) {
			foreach ($section->getPanels() as $panel) {
				// Validate duplicate panels
				if (isset($panels[$panel->getId()])) {
					throw new InvalidStateException(sprintf('Multiple panels with same id "%s" found', $panel->getId()));
				}

				// Track panels ids
				$panels[$panel->getId()] = $panel;
			}
		}

		return $panels[$id] ?? null;
	}

}
