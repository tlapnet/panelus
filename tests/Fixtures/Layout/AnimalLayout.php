<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Panelus\Fixtures\Layout;

use Tlapnet\Panelus\Layout\Entity\Layout;
use Tlapnet\Panelus\Layout\Entity\Panel;
use Tlapnet\Panelus\Layout\Entity\Section;

final class AnimalLayout
{

	public static function createLayout(): Layout
	{
		$p1 = new Panel('p1');
		$p1->setConfig(['foo' => 'bar']);
		$p1->getRender()->addAll(['cat' => 'meow', 'dog' => 'bark']);

		$p2 = new Panel('p2');
		$p2->setConfig(['baz' => 'bat', 'woo' => 'doo']);

		$p3 = new Panel('p3');
		$p3->setConfig(['loo' => 'goo']);
		$p3->getRender()->add('cow', 'booo');

		$s1 = new Section('s1');
		$s1->addPanels([$p1, $p2]);
		$s1->getRender()->add('pig', 'oink');

		$s2 = new Section('s2');
		$s2->addPanel($p3);

		$l = new Layout('animal');
		$l->addSections([$s1, $s2]);

		return $l;
	}

	/**
	 * @return mixed[]
	 */
	public static function createRawLayout(): array
	{
		return [
			'animal' => [
				'sections' => [
					's1' => [
						'render' => ['pig' => 'oink'],
						'panels' => [
							'p1' => [
								'render' => ['cat' => 'meow', 'dog' => 'bark'],
								'config' => ['foo' => 'bar'],
							],
							'p2' => [
								'config' => ['baz' => 'bat', 'woo' => 'doo'],
							],
						],
					],
					's2' => [
						'panels' => [
							'p3' => [
								'render' => ['cow' => 'booo'],
								'config' => ['loo' => 'goo'],
							],
						],
					],
				],
			],
		];
	}

}
