<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Utils;

use ArrayIterator;
use Contributte\Utils\Deeper;
use Tlapnet\Panelus\Exception\Logical\InvalidStateException;
use Traversable;

trait TAttributes
{

	/** @var mixed[] */
	protected $attributes = [];

	/**
	 * @param mixed[] $params
	 */
	public function __construct(array $params = [])
	{
		$this->attributes = $params;
	}

	/**
	 * @return mixed[]
	 */
	public function getAll(): array
	{
		return $this->attributes;
	}

	public function has(string $key): bool
	{
		return array_key_exists($key, $this->attributes);
	}

	public function hasDeep(string $key): bool
	{
		return Deeper::has($key, $this->attributes);
	}

	/**
	 * @param mixed $default
	 * @return mixed
	 */
	public function get(string $key, $default = null)
	{
		if (!isset($this->attributes[$key])) {
			if (func_num_args() >= 2) {
				return $default;
			}
			throw new InvalidStateException(sprintf('Attribute "%s" not found', $key));
		}

		return $this->attributes[$key];
	}

	/**
	 * @param mixed $default
	 * @return mixed
	 */
	public function getDeep(string $key, $default = null)
	{
		if (!$this->hasDeep($key)) {
			if (func_num_args() >= 2) {
				return $default;
			}
			throw new InvalidStateException(sprintf('Attribute "%s" not found', $key));
		}

		return Deeper::get($key, $this->attributes);
	}

	/**
	 * @param mixed $value
	 */
	public function add(string $key, $value): void
	{
		$this->attributes[$key] = $value;
	}

	/**
	 * @param mixed[] $params
	 */
	public function addAll(array $params): void
	{
		foreach ($params as $key => $value) {
			$this->add($key, $value);
		}
	}

	/**
	 * IteratorAggregate *******************************************************
	 */

	/**
	 * @return ArrayIterator|Traversable
	 */
	public function getIterator()
	{
		return new ArrayIterator($this->attributes);
	}

	/**
	 * ArrayAccess *************************************************************
	 */

	/**
	 * @param mixed $offset
	 */
	public function offsetExists($offset): bool
	{
		return $this->has($offset);
	}

	/**
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->get($offset);
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value): void
	{
		$this->add($offset, $value);
	}

	/**
	 * @param mixed $offset
	 */
	public function offsetUnset($offset): void
	{
		unset($this->attributes[$offset]);
	}

}
