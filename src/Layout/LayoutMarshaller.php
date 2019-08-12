<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Layout;

use Contributte\Utils\Merger;
use Tlapnet\Panelus\Exception\Logical\InvalidArgumentException;
use Tlapnet\Panelus\Exception\Logical\InvalidStateException;
use Tlapnet\Panelus\Layout\Entity\Layout;
use Tlapnet\Panelus\Layout\Entity\Panel;
use Tlapnet\Panelus\Layout\Entity\Rawprint;
use Tlapnet\Panelus\Layout\Entity\Section;

class LayoutMarshaller
{

	/** @var mixed[] */
	protected $tmp;

	public function marshall(Rawprint $rawprint, string $layoutName): Layout
	{
		// Validate layout exists
		if (!isset($rawprint[$layoutName])) {
			throw new InvalidArgumentException(sprintf('Cannot marshall undefined layout "%s"', $layoutName));
		}

		// Init tmps
		$this->tmp = [
			'panels' => [],
		];

		// Merge definitions
		$definition = $this->merge($rawprint, $layoutName);

		// Create layout
		$layout = new Layout($layoutName);
		$layout->addSections($this->createSections($definition));

		return $layout;
	}

	/**
	 * @param mixed[][] $extra
	 */
	public function marshallWith(Rawprint $rawprint, string $layoutName, array ...$extra): Layout
	{
		// Validate layout exists
		if (!isset($rawprint[$layoutName])) {
			throw new InvalidArgumentException(sprintf('Cannot merge undefined layout "%s"', $layoutName));
		}

		// Clone rawprint (to prevent unexpected modifications)
		$rawprint = clone $rawprint;

		$extraMerged = [];
		foreach ($extra as $single) {
			// Merge given extra parts together
			$extraMerged = Merger::merge($single, $extraMerged);
		}

		// Merge final extra parts with rawprint
		// and replace merged layout back to rawprint
		$rawprint[$layoutName] = Merger::merge($extraMerged, $rawprint[$layoutName]);

		// Marshall our updated rawprint as usual
		return $this->marshall($rawprint, $layoutName);
	}

	/**
	 * @return string[][]
	 */
	protected function merge(Rawprint $rawprint, string $layoutName): array
	{
		// Validate layout exists
		if (!isset($rawprint[$layoutName])) {
			throw new InvalidArgumentException(sprintf('Cannot merge undefined layout "%s"', $layoutName));
		}

		// Is layout extending?
		// - yes => merge with parent layout
		// - no => return layout
		if (isset($rawprint[$layoutName]['extends'])) {
			// @recursion
			$parent = $this->merge($rawprint, $rawprint[$layoutName]['extends']);

			return Merger::merge($rawprint[$layoutName], $parent);
		}

		return $rawprint[$layoutName];
	}

	/**
	 * @param mixed[] $layout
	 * @return Section[]
	 */
	public function createSections(array $layout): array
	{
		// Layout has no sections, skip processing
		if (!isset($layout['sections'])) {
			return [];
		}

		$output = [];

		foreach ($layout['sections'] as $sectionName => $section) {
			// Ensure type
			$sectionName = (string) $sectionName;

			// Each section needs a name
			if (strlen($sectionName) <= 0) {
				throw new InvalidStateException(sprintf('Section name must be provided, given "%s"', $sectionName));
			}

			// Skip section (this could happen when some layout extends other layout and remove section)
			if ($section === false) {
				continue;
			}

			// Section must have panels
			if (!isset($section['panels'])) {
				throw new InvalidStateException(sprintf('Section "%s" must have at least 1 panel', $sectionName));
			}

			// Creates section
			$output[$sectionName] = $s = new Section($sectionName);

			// Setup section render params
			$s->getRender()->addAll(
				Merger::merge(
					$section['render'] ?? [],
					$layout['_default']['sections']['render'] ?? []
				)
			);

			// Append all panels
			$s->addPanels($this->createPanels($layout, $section));
		}

		return $output;
	}

	/**
	 * @param mixed[] $layout
	 * @param mixed[] $section
	 * @return Panel[]
	 */
	public function createPanels(array $layout, array $section): array
	{
		$output = [];

		foreach ($section['panels'] as $panelName => $panel) {
			// Ensure type
			$panelName = (string) $panelName;

			// Each panel needs a name
			if (strlen($panelName) <= 0) {
				throw new InvalidStateException(sprintf('Panel name must be provided, given "%s"', $panelName));
			}

			// Skip section (this could happen when some layout extends other layout and remove panel)
			if ($panel === false) {
				continue;
			}

			// Panel must be unique in layout
			if (isset($this->tmp['panels'][$panelName])) {
				throw new InvalidStateException(sprintf('More panels with same name "%s"  defined', $panelName));
			}

			$this->tmp['panels'][$panelName] = $panelName;

			$output[$panelName] = $this->createPanel($layout, $panelName, $panel);
		}

		return $output;
	}

	/**
	 * @param mixed[] $layout
	 * @param mixed[] $panel
	 */
	public function createPanel(array $layout, string $panelName, array $panel): Panel
	{
		$p = new Panel($panelName);

		// Setup panel render params
		$p->getRender()->addAll(
			Merger::merge(
				$panel['render'] ?? [],
				$layout['_default']['panels']['render'] ?? []
			)
		);

		// Setup panel extra params
		$p->getExtra()->addAll(
			Merger::merge(
				$panel['extra'] ?? [],
				$layout['_default']['panels']['extra'] ?? []
			)
		);

		// Setup panel configuration
		if (isset($panel['config'])) {
			$p->setConfig($panel['config']);
		}

		return $p;
	}

}
