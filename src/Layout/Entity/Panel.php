<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Layout\Entity;

use Tlapnet\Panelus\Utils\Names;

class Panel
{

	/** @var string */
	private $id;

	/** @var string */
	private $name;

	/** @var Render */
	protected $render;

	/** @var Extra */
	protected $extra;

	/** @var mixed[] */
	protected $config = [];

	public function __construct(string $id)
	{
		$this->id = $id;
		$this->name = Names::panel($id);
		$this->render = new Render();
		$this->extra = new Extra();
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getRender(): Render
	{
		return $this->render;
	}

	public function getExtra(): Extra
	{
		return $this->extra;
	}

	/**
	 * @return mixed[]
	 */
	public function getConfig(): array
	{
		return $this->config;
	}

	/**
	 * @param mixed[] $config
	 */
	public function setConfig(array $config): void
	{
		$this->config = $config;
	}

}
