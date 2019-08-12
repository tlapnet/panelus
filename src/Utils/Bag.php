<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Utils;

use Tlapnet\Panelus\Exception\Logical\InvalidStateException;

class Bag
{

	/** @var mixed[] */
	protected $bag = [];

	/**
	 * @param mixed $value
	 */
	public function add(string $key, $value): void
	{
		$this->bag[$key] = $value;
	}

	/**
	 * @param mixed $default
	 * @return mixed
	 */
	public function get(string $key, $default = null)
	{
		if (!$this->has($key)) {
			if (func_num_args() >= 2) {
				return $default;
			}
			throw new InvalidStateException(sprintf('Item "%s" not found in bag', $key));
		}

		return $this->bag[$key];
	}

	public function has(string $key): bool
	{
		return array_key_exists($key, $this->bag);
	}

}
