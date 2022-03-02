<?php

declare(strict_types=1);

namespace NAttreid\Utils;

final class Number extends Lang
{

	private static function getBinary(float $number): int
	{
		$num = 2;
		if ($number < $num) {
			return 0;
		}
		$result = $num;
		while (true) {
			$num *= 2;
			if ($number < $num) {
				return $result;
			}
			$result = $num;
		}
	}

	public static function getNumber(float $number, int $decimal = 2): string
	{
		if (floor($number) == $number) {
			$decimal = 0;
		}

		switch (self::$locale) {
			default:
				return number_format($number, $decimal, ',', ' ');
			case 'en':
				return number_format($number, $decimal);
		}
	}

	public static function percent(float $number, float $total, int $decimal = 2): string
	{
		return self::getNumber($number / $total * 100, $decimal) . '%';
	}

	public static function frequency(float $number): string
	{
		if ($number > 1000000000) {
			return self::getNumber($number / 1000000000, 2) . ' GHz';
		} elseif ($number > 1000000) {
			return self::getNumber($number / 1000000, 0) . ' MHz';
		} elseif ($number > 1000) {
			return self::getNumber($number / 1000, 0) . ' KHz';
		} else {
			return self::getNumber($number, 0) . ' Hz';
		}
	}

	public static function size(float $number, int $decimal = 2, bool $binary = false): string
	{
		if ($number > 1024 * 1024 * 1024) {
			if ($binary) {
				$number = self::getBinary($number / (1024 * 1024)) / 1024;
			} else {
				$number = $number / (1024 * 1024 * 1024);
			}
			return self::getNumber($number, $decimal) . ' GB';
		} elseif ($number > 1024 * 1024) {
			$number /= 1024 * 1024;
			if ($binary) {
				$number = self::getBinary($number);
			}
			return self::getNumber($number, $decimal) . ' MB';
		} elseif ($number > 1024) {
			$number /= 1024;
			if ($binary) {
				$number = self::getBinary($number);
			}
			return self::getNumber($number, $decimal) . ' KB';
		} else {
			if ($binary) {
				$number = self::getBinary($number);
			}
			return self::getNumber($number, $decimal) . ' B';
		}
	}
}
