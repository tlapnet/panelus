<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Panelus\Unit\Layout;

use PHPUnit\Framework\TestCase;
use Tlapnet\Panelus\Exception\Logical\InvalidArgumentException;
use Tlapnet\Panelus\Exception\Logical\InvalidStateException;
use Tlapnet\Panelus\Layout\Entity\Layout;
use Tlapnet\Panelus\Layout\Entity\Rawprint;
use Tlapnet\Panelus\Layout\LayoutMarshaller;
use Tlapnet\Panelus\Utils\Dumper;

class LayoutMarshallerTest extends TestCase
{

	public const VALID_RAW_PRINT_DATA = [
		'default' => [
			'_default' => [
				'sections' => [
					'render' => [
						'height' => 'auto',
					],
				],
				'panels' => [
					'config' => [
						'foo',
						'bar',
						'baz',
					],
					'render' => [
						'width' => 4,
					],
				],
			],
			'sections' => [
				's10' => [
					'panels' => [
						'panel1' => [
							'render' => [
								'width' => 12,
							],
						],
					],
				],
				's20' => [
					'panels' => [
						'panel2' => [],
					],
				],
			],
		],
		'extended' => [
			'extends' => 'default',
			'sections' => [
				's20' => [
					'panels' => [
						'panel2' => [],
						'panel3' => [],
					],
				],
				's30' => [
					'panels' => [
						'panel4' => [],
					],
				],
			],
		],
	];

	/** @var LayoutMarshaller */
	private $marshaller;

	protected function setUp(): void
	{
		$this->marshaller = new LayoutMarshaller();
	}

	public function testMarshall(): void
	{
		$rawprint = new Rawprint(self::VALID_RAW_PRINT_DATA);
		$layout = $this->marshaller->marshall($rawprint, 'extended');
		$this->assertInstanceOf(Layout::class, $layout);
	}

	public function testMarshallUndefinedLayoutException(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Cannot marshall undefined layout "missing"');
		$this->marshaller->marshall(new Rawprint([]), 'missing');
	}

	public function testMarshallCannotMergeLayoutException(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Cannot merge undefined layout "missing"');
		$rawprint = new Rawprint([
			'foo' => [
				'extends' => 'missing',
			],
		]);
		$this->marshaller->marshall($rawprint, 'foo');
	}

	public function testMarshallSectionNameMissingException(): void
	{
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Section name must be provided, given ""');
		$rawprint = new Rawprint([
			'default' => [
				'sections' => [
					'' => [],
				],
			],
		]);
		$this->marshaller->marshall($rawprint, 'default');
	}

	public function testMarshallSectionNoPanelsException(): void
	{
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Section "foo" must have at least 1 panel');
		$rawprint = new Rawprint([
			'default' => [
				'sections' => [
					'foo' => [],
				],
			],
		]);
		$this->marshaller->marshall($rawprint, 'default');
	}

	public function testMarshallPanelNoNameException(): void
	{
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Panel name must be provided, given ""');
		$rawprint = new Rawprint([
			'default' => [
				'sections' => [
					'foo' => [
						'panels' => [
							'' => [],
						],
					],
				],
			],
		]);
		$this->marshaller->marshall($rawprint, 'default');
	}

	public function testMarshallWithEmpty(): void
	{
		$rawprint = new Rawprint([
			'default' => [
				'sections' => [
					'foo' => [
						'panels' => [
							'bar' => [],
						],
					],
				],
			],
		]);

		$layout = $this->marshaller->marshallWith($rawprint, 'default', []);

		$this->assertEquals([
			'default' => [
				'sections' => [
					'foo' => [
						'panels' => [
							'bar' => [],
						],
					],
				],
			],
		], Dumper::dumpLayout($layout));
	}

	public function testMarshallWithExtra(): void
	{
		$rawprint = new Rawprint([
			'default' => [
				'sections' => [
					'foo' => [
						'panels' => [
							'bar' => [],
						],
					],
				],
			],
		]);

		$extra = [
			'sections' => [
				'foo2' => [
					'panels' => [
						'bar2' => [],
					],
				],
			],
		];
		$layout = $this->marshaller->marshallWith($rawprint, 'default', $extra);

		$this->assertEquals([
			'default' => [
				'sections' => [
					'foo' => [
						'panels' => [
							'bar' => [],
						],
					],
					'foo2' => [
						'panels' => [
							'bar2' => [],
						],
					],
				],
			],
		], Dumper::dumpLayout($layout));
	}

	public function testMarshallWithExtraChain(): void
	{
		$rawprint = new Rawprint([
			'default' => [
				'sections' => [
					'foo' => [
						'panels' => [
							'bar' => [],
						],
					],
					'bar' => [
						'panels' => [
							'baz' => false,
						],
					],
				],
			],
		]);

		$extra = [
			'sections' => [
				'foo' => false,
				'foo2' => [
					'panels' => [
						'bar2' => [],
					],
				],
				'bar' => [
					'panels' => [
						'baz' => [
							'render' => ['width' => 6],
						],
					],
				],
			],
		];
		$layout = $this->marshaller->marshallWith($rawprint, 'default', $extra);

		$this->assertEquals([
			'default' => [
				'sections' => [
					'foo2' => [
						'panels' => [
							'bar2' => [],
						],
					],
					'bar' => [
						'panels' => [
							'baz' => [
								'render' => ['width' => 6],
							],
						],
					],
				],
			],
		], Dumper::dumpLayout($layout));
	}

}
