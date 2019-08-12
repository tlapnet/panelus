<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Panelus\Unit\Layout\Entity;

use PHPUnit\Framework\TestCase;
use Tlapnet\Panelus\Exception\Logical\InvalidStateException;
use Tlapnet\Panelus\Layout\Entity\Panel;
use Tlapnet\Panelus\Layout\Entity\Section;

final class SectionTest extends TestCase
{

	public function testAddDuplicatePanel(): void
	{
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage(sprintf('Panel "%s" already registered', 'foo'));

		$section = new Section('sid');
		$section->addPanel(new Panel('foo'));
		$section->addPanel(new Panel('foo'));
	}

	public function testLookupPanel(): void
	{
		$section = new Section('sid');

		for ($i = 1; $i <= 5; $i++) {
			$section->addPanel(new Panel('panel' . $i));
		}

		$this->assertNotNull($section->lookupPanel('panel4'));
	}

}
