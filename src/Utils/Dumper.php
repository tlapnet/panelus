<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Utils;

use Tlapnet\Panelus\Layout\Entity\Layout;

final class Dumper
{

	/**
	 * @return mixed[]
	 */
	public static function dumpLayout(Layout $layout): array
	{
		$output = [$layout->getId() => []];
		$sections = [];

		foreach ($layout->getSections() as $section) {
			$sections[$section->getId()] = [];

			if (($sectionRender = $section->getRender()->getAll()) !== []) {
				$sections[$section->getId()]['render'] = $sectionRender;
			}

			if (($sectionPanels = $section->getPanels()) !== []) {
				$panels = [];

				foreach ($sectionPanels as $panel) {
					$panels[$panel->getId()] = [];

					if (($panelRender = $panel->getRender()->getAll()) !== []) {
						$panels[$panel->getId()]['render'] = $panelRender;
					}
					if (($panelConfig = $panel->getConfig()) !== []) {
						$panels[$panel->getId()]['config'] = $panelConfig;
					}
				}

				$sections[$section->getId()]['panels'] = $panels;
			}
		}

		$output[$layout->getId()]['sections'] = $sections;

		return $output;
	}

}
