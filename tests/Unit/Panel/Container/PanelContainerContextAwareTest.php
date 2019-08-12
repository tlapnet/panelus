<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Panelus\Unit\Panel\Container;

use Mockery;
use Nette\DI\Container;
use PHPUnit\Framework\TestCase;
use Tlapnet\Panelus\Exception\Logical\InvalidArgumentException;
use Tlapnet\Panelus\Panel\Container\PanelContainerContextAware;
use Tlapnet\Panelus\Panel\IPanelControlFactory;

class PanelContainerContextAwareTest extends TestCase
{

	public function testGet(): void
	{
		$factory = Mockery::mock(IPanelControlFactory::class);

		$context = Mockery::mock(Container::class);
		$context->shouldReceive('getService')
			->with('service.foo')
			->andReturn($factory);

		$container = new PanelContainerContextAware($context);
		$container->add('foo', 'service.foo');

		$this->assertTrue($container->has('foo'));
		$this->assertFalse($container->has('bar'));
		$this->assertEquals($factory, $container->get('foo'));
	}

	public function testGetMap(): void
	{
		$container = $this->mockContainer();
		$container->add('foo', 'service.foo');
		$container->add('bar', 'service.bar');

		$this->assertEquals(['foo', 'bar'], $container->getMap());
	}

	public function testGetException(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Panel missing does not exist.');

		$container = $this->mockContainer();
		$container->get('missing');
	}

	public function testAddInvalidNameException1(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid name "▓" given. Allowed characters [a-zA-Z0-9]+[a-zA-Z0-9_]+.');

		$container = $this->mockContainer();
		$container->add('▓', 'service.foo');
	}

	public function testAddInvalidNameException2(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid name "_foo" given. Allowed characters [a-zA-Z0-9]+[a-zA-Z0-9_]+.');

		$container = $this->mockContainer();
		$container->add('_foo', 'service.foo');
	}

	public function testAddAlreadyExistsException(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Panel foo already exists.');

		$container = $this->mockContainer();
		$container->add('foo', 'service.foo');
		$container->add('foo', 'service.foo');
	}

	private function mockContainer(): PanelContainerContextAware
	{
		$context = Mockery::mock(Container::class);
		$container = new PanelContainerContextAware($context);

		return $container;
	}

}
