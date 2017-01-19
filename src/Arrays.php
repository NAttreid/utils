<?php

namespace NAttreid\Utils;

/**
 * Pomocna trida pro pole
 *
 * @author Attreid <attreid@gmail.com>
 */
class Arrays
{

	/**
	 * Je pole asociativni
	 * @param array $arr
	 * @return boolean
	 */
	public static function isAssoc(array $arr)
	{
		return array_keys($arr) !== range(0, count($arr) - 1);
	}

	/**
	 * Vlozi do pole na dane misto
	 * @param array $array
	 * @param int $position
	 * @param mixed $insert
	 */
	public static function slice(array &$array, $position, $insert)
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
	public static function isMultidimensional(array $a)
	{
		$rv = array_filter($a, 'is_array');
		return count($rv) > 0;
	}

}
