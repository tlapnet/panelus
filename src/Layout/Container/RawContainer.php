<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Layout\Container;

use Contributte\Utils\Arrays;
use Tlapnet\Panelus\Layout\Entity\Layout;
use Tlapnet\Panelus\Layout\Entity\Rawprint;
use Tlapnet\Panelus\Layout\LayoutMarshaller;

class RawContainer implements ILayoutContainer
{

	/** @var Rawprint */
	protected $rawprint;

	/** @var LayoutMarshaller|null */
	protected $marshaller;

	/** @var Layout[] */
	protected $layouts = [];

	public function __construct(Rawprint $rawprint)
	{
		$this->rawprint = $rawprint;
	}

	public function getRawprint(): Rawprint
	{
		return $this->rawprint;
	}

	public function get(string $name): Layout
	{
		if (!isset($this->layouts[$name])) {
			$this->layouts[$name] = $this->getMarshaller()->marshall($this->rawprint, $name);
		}

		return $this->layouts[$name];
	}

	/**
	 * @param mixed[][] $extra
	 */
	public function merge(string $name, array ...$extra): Layout
	{
		$extraHash = Arrays::hash($extra);
		$layoutsKey = $name . '/' . $extraHash;

		if (!isset($this->layouts[$layoutsKey])) {
			$this->layouts[$layoutsKey] = $this->getMarshaller()->marshallWith($this->rawprint, $name, ...$extra);
		}

		return $this->layouts[$layoutsKey];
	}

	protected function getMarshaller(): LayoutMarshaller
	{
		if ($this->marshaller === null) {
			$this->marshaller = new LayoutMarshaller();
		}

		return $this->marshaller;
	}

}
