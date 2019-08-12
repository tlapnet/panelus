<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Layout\Entity;

use ArrayAccess;
use Tlapnet\Panelus\Exception\Logical\InvalidArgumentException;

class Rawprint implements ArrayAccess
{

	/** @var mixed */
	protected $raw = [];

	/**
	 * @param mixed[] $raw
	 */
	public function __construct(array $raw = [])
	{
		$this->raw = array_merge($this->raw, $raw);
	}

	/**
	 * @return mixed[]
	 */
	public function getRaw(): array
	{
		return $this->raw;
	}

	/**
	 * ArrayAccess *************************************************************
	 */

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function offsetExists($offset): bool
	{
		return isset($this->raw[$offset]);
	}

	/**
	 * @return mixed
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function offsetGet($offset)
	{
		if (!$this->offsetExists($offset)) {
			throw new InvalidArgumentException(sprintf('Key "%s" does not exist', $offset));
		}

		return $this->raw[$offset];
	}

	/**
	 * @param mixed $value
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function offsetSet($offset, $value): void
	{
		$this->raw[$offset] = $value;
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function offsetUnset($offset): void
	{
		unset($this->raw[$offset]);
	}

}
