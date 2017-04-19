<?php

declare(strict_types=1);

namespace NAttreid\Utils;

/**
 * Nastaveni lokalizace
 *
 * @author Attreid <attreid@gmail.com>
 */
abstract class Lang
{

	/**
	 * Lokalizace
	 * @var string
	 */
	protected static $locale;

	/**
	 * Nastavi locale
	 * @param string $locale
	 */
	public static function setLocale(string $locale): void
	{
		self::$locale = $locale;
	}

}
