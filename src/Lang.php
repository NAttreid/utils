<?php

namespace nattreid\helpers;

/**
 * Nastaveni lokalizace
 *
 * @author Attreid <attreid@gmail.com>
 */
abstract class Lang {

    /**
     * Lokalizace
     * @var string
     */
    protected static $locale;

    /**
     * Nastavi locale
     * @param string $locale
     */
    public static function setLocale($locale) {
        self::$locale = $locale;
    }

}
