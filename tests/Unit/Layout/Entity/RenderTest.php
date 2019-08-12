<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Panelus\Unit\Layout\Entity;

use PHPUnit\Framework\TestCase;
use Throwable;
use Tlapnet\Panelus\Exception\Logical\InvalidStateException;
use Tlapnet\Panelus\Layout\Entity\Render;

class RenderTest extends TestCase
{

	public function testGet(): void
	{
		$render = new Render();
		$render->add('foo', 'bar');
		$this->assertEquals('bar', $render->get('foo'));
	}

	public function testGetDefault(): void
	{
		$render = new Render();
		$this->assertEquals('bar', $render->get('foo', 'bar'));
	}

	public function testGetException(): void
	{
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Attribute "missing" not found');

		$render = new Render();
		$render->get('missing');
	}

	public function testAddGetAll(): void
	{
		$render = new Render();
		$this->assertEmpty($render->getAll());

		$render->add('foo', 'bar');
		$render->addAll(['one' => '1st', 'two' => '2nd']);

		$this->assertEquals(['foo' => 'bar', 'one' => '1st', 'two' => '2nd'], $render->getAll());
	}

	public function testAddAllException(): void
	{
		$this->expectException(Throwable::class);

		$render = new Render();
		$render->addAll(['foo', 'var']);
	}

	public function testAddRewrite(): void
	{
		$render = new Render();
		$render->addAll(['one' => '1st', 'two' => '2nd']);
		$render->add('one', 'foo');

		$this->assertEquals(['one' => 'foo', 'two' => '2nd'], $render->getAll());
	}

	public function testDeep(): void
	{
		$render = new Render();
		$render->addAll([
			'foo' => [
				'bar' => 1,
			],
		]);

		$this->assertTrue($render->hasDeep('foo.bar'));
		$this->assertEquals(1, $render->getDeep('foo.bar'));
	}

}
