<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Utils;

use Nette\Utils\Strings;

final class Names
{

	public static function panel(string $str): string
	{
		// Split panel name
		// task__1 => task & 1
		// It allows us to define same panel multiple times.
		$parts = Strings::split($str, '#__#');

		return $parts[0];
	}

}
