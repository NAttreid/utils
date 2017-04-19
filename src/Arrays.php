<?php

declare(strict_types=1);

namespace NAttreid\Utils;

/**
 * Pomocna trida pro pole
 *
 * @author Attreid <attreid@gmail.com>
 */
class Arrays extends \Nette\Utils\Arrays
{

	/**
	 * Je pole asociativni
	 * @param array $arr
	 * @return bool
	 */
	public static function isAssoc(array $arr): bool
	{
		return array_keys($arr) !== range(0, count($arr) - 1);
	}

	/**
	 * Vlozi do pole na dane misto
	 * @param array $array
	 * @param int $position
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

	/**
	 * Je pole vicerozmerne
	 * @param array $a
	 * @return bool
	 */
	public static function isMultidimensional(array $a): bool
	{
		$rv = array_filter($a, 'is_array');
		return count($rv) > 0;
	}

}
