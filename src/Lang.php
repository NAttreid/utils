<?php

declare(strict_types=1);

namespace NAttreid\Utils;

abstract class Lang
{
	protected static string $locale;

	public static function setLocale(string $locale): void
	{
		self::$locale = $locale;
	}
}
