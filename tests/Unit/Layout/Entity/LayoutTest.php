<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Panelus\Unit\Layout\Entity;

use PHPUnit\Framework\TestCase;
use Tlapnet\Panelus\Exception\Logical\InvalidStateException;
use Tlapnet\Panelus\Layout\Entity\Layout;
use Tlapnet\Panelus\Layout\Entity\Panel;
use Tlapnet\Panelus\Layout\Entity\Section;

final class LayoutTest extends TestCase
{

	public function testAddSection(): void
	{
		$layout = new Layout('lid');
		$layout->addSection(new Section('sectionB'));
		$layout->addSection(new Section('sectionA'));

		$this->assertEquals(
			['sectionA', 'sectionB'],
			array_keys($layout->getSections()),
			'sections should be ordered by their ids'
		);
	}

	public function testAddDuplicateSection(): void
	{
		$layout = new Layout('lid');
		$sectionA = new Section('sectionA');
		$layout->addSection($sectionA);

		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Section "sectionA" already registered');
		$layout->addSection($sectionA);
	}

	public function testLookupPanel(): void
	{
		$layout = new Layout('lid');
		$sectionA = new Section('sectionA');
		$sectionA->addPanel(new Panel('panel1'));
		$layout->addSection($sectionA);

		$sectionB = new Section('sectionB');
		$sectionB->addPanel(new Panel('panel2'));
		$layout->addSection($sectionB);

		$this->assertCount(2, $layout->getSections());
		$this->assertSame($sectionA->getPanels()['panel1'], $layout->lookupPanel('panel1'));
		$this->assertSame($sectionB->getPanels()['panel2'], $layout->lookupPanel('panel2'));
	}

	public function testLookupPanelDuplicates(): void
	{
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Multiple panels with same id "panel2" found');

		$layout = new Layout('lid');

		$sectionA = new Section('sectionA');
		$sectionA->addPanel(new Panel('panel1'));
		$sectionA->addPanel(new Panel('panel2'));
		$layout->addSection($sectionA);

		$sectionB = new Section('sectionB');
		$sectionB->addPanel(new Panel('panel2'));
		$layout->addSection($sectionB);

		$layout->lookupPanel('panel2');
	}

}
