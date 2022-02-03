<?php

declare(strict_types=1);

namespace NAttreid\Utils;

class Arrays extends \Nette\Utils\Arrays
{

	public static function isAssoc(array $arr): bool
	{
		return array_keys($arr) !== range(0, count($arr) - 1);
	}

	/**
	 * @param mixed $insert
	 */
	public static function slice(array &$array, int $position, $insert): void
	{
		if (isset($array[$position])) {
			array_splice($array, $position, 0, [$insert]);
		} else {
			$array[$position] = $insert;
		}
	}

	public static function isMultidimensional(array $a): bool
	{
		$rv = array_filter($a, 'is_array');
		return count($rv) > 0;
	}
}
