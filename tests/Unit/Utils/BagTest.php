<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Panelus\Unit\Utils;

use PHPUnit\Framework\TestCase;
use Tlapnet\Panelus\Utils\Bag;

class BagTest extends TestCase
{

	public function testBag(): void
	{
		$controlBag = new Bag();
		$controlBag->add('foo', 'bar');

		$this->assertTrue($controlBag->has('foo'));
		$this->assertFalse($controlBag->has('foobar'));

		$this->assertEquals('bar', $controlBag->get('foo'));
		$this->assertEquals(null, $controlBag->get('foobar', null));
	}

}
