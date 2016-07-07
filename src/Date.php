<?php

namespace NAttreid\Utils;

use NAttreid\Form\DateRange\Range;

/**
 * Pomocna trida pro datum
 * 
 * @author Attreid <attreid@gmail.com>
 */
class Date extends Lang {

    const
            DAY_SHORT = 'dayNamesShort',
            DAY = 'dayNames',
            MONTH_SHORT = 'monthNamesShort',
            MONTH = 'monthNames',
            DATETIME = 'datetime',
            DATE = 'date';

    private static $dayNamesShort = [
        'en' => [1 => 'mon', 2 => 'tue', 3 => 'wed', 4 => 'thu', 5 => 'fri', 6 => 'sat', 7 => 'sun'],
        'cs' => [1 => 'po', 2 => 'út', 3 => 'st', 4 => 'čt', 5 => 'pá', 6 => 'so', 7 => 'ne']
    ];
    private static $dayNames = [
        'en' => [1 => 'sunday', 2 => 'monday', 3 => 'tuesday', 4 => 'wednesday', 5 => 'thursday', 6 => 'friday', 7 => 'saturday'],
        'cs' => [1 => 'neděle', 2 => 'pondělí', 3 => 'úterý', 4 => 'středa', 5 => 'čtvrtek', 6 => 'pátek', 7 => 'sobota']
    ];
    private static $monthNamesShort = [
        'en' => [1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr', 5 => 'may', 6 => 'jun', 7 => 'jul', 8 => 'aug', 9 => 'sep', 10 => 'oct', 11 => 'nov', 12 => 'dec'],
        'cs' => [1 => 'led', 2 => 'úno', 3 => 'bře', 4 => 'dub', 5 => 'kvě', 6 => 'čer', 7 => 'črn', 8 => 'srp', 9 => 'zář', 10 => 'říj', 11 => 'lis', 12 => 'pro']
    ];
    private static $monthNames = [
        'en' => [1 => 'january', 2 => 'february', 3 => 'march', 4 => 'april', 5 => 'may', 6 => 'june', 7 => 'july', 8 => 'august', 9 => 'september', 10 => 'october', 11 => 'november', 12 => 'december'],
        'cs' => [1 => 'leden', 2 => 'únor', 3 => 'březen', 4 => 'duben', 5 => 'květen', 6 => 'červen', 7 => 'červenec', 8 => 'srpen', 9 => 'září', 10 => 'říjen', 11 => 'listopad', 12 => 'prosinec']
    ];
    private static $datetime = [
        'en' => 'm/d/Y H:i:s',
        'cs' => 'd.m.Y H:i:s'
    ];
    private static $date = [
        'en' => 'm/d/Y',
        'cs' => 'd.m.Y'
    ];

    /**
     * Formatovani
     * @param string $type
     * @return string
     */
    public static function getFormat($type) {
        $arr = self::$$type;
        return $arr[self::$locale];
    }

    /**
     * Vrati pocatecni rok - aktualni rok. V pripade, ze se shoduji pouze aktualni
     * @param int $beginYear pocatecni rok
     * @return string napr: 2012 - 2014 nebo pouze 2014
     */
    public static function getYearToActual($beginYear) {
        $actualYear = strftime('%Y');
        if ($beginYear == $actualYear) {
            return $actualYear;
        } else {
            return $beginYear . ' - ' . $actualYear;
        }
    }

    /**
     * Vrati aktualni cas na milivteriny
     * @return string
     */
    public static function getCurrentTimeStamp() {
        $t = microtime(true);
        $micro = sprintf('%06d', ($t - floor($t)) * 1000000);
        $d = new \DateTime(date('Y-m-d H:i:s.' . $micro, $t));
        return $d->format('Y_m_d_H_i_s_u');
    }

    /**
     * Vrati nazev dne
     * @param int|\Datetime $day
     * @return string
     */
    public static function getDay($day) {
        if ($day instanceof \DateTime) {
            $day = $day->format('N');
        }
        return self::$dayNames[self::$locale][$day];
    }

    /**
     * Vrati zkraceny nazev dne
     * @param int|\Datetime $day
     * @return string
     */
    public static function getShortDay($day) {
        if ($day instanceof \DateTime) {
            $day = $day->format('N');
        }
        return self::$dayNamesShort[self::$locale][$day];
    }

    /**
     * Vrati nazev mesice
     * @param int|\Datetime $month
     * @return string
     */
    public static function getMonth($month) {
        if ($month instanceof \DateTime) {
            $month = $month->format('j');
        }
        return self::$monthNames[self::$locale][$month];
    }

    /**
     * Vrati zkraceny nazev mesice
     * @param int|\Datetime $month
     * @return string
     */
    public static function getShortMonth($month) {
        if ($month instanceof \DateTime) {
            $month = $month->format('j');
        }
        return self::$monthNamesShort[self::$locale][$month];
    }

    /**
     * Vrati nazvy dnu
     * @return array
     */
    public static function getDays() {
        return self::$dayNames[self::$locale];
    }

    /**
     * Vrati zkracene nazvy dnu
     * @return array
     */
    public static function getShortDays() {
        return self::$dayNamesShort[self::$locale];
    }

    /**
     * Vrati nazvy mesicu
     * @return array
     */
    public static function getMonths() {
        return self::$monthNames[self::$locale];
    }

    /**
     * Vrati zkracene nazvy mesicu
     * @return array
     */
    public static function getShortMonths() {
        return self::$monthNamesShort[self::$locale];
    }

    /**
     * Vrati lokalizovany format data
     * @param \DateTime|int $datetime
     * @param array $formats
     * @return string|FALSE
     */
    private static function formatDate($datetime, $formats) {
        if (empty($datetime)) {
            return FALSE;
        } elseif ($datetime instanceof \DateTime) {
            $date = $datetime;
        } else {
            $date = \DateTime::createFromFormat('U', $datetime);
        }
        return $date->format($formats[self::$locale]);
    }

    /**
     * Lokalizovane datum s casem
     * @param \DateTime|int $datetime datum nebo timestamp
     * @return string
     */
    public static function getDateTime($datetime) {
        return self::formatDate($datetime, self::$datetime);
    }

    /**
     * Lokalizovane datum
     * @param \DateTime|int $datetime datum nebo timestamp
     * @return string
     */
    public static function getDate($datetime) {
        return self::formatDate($datetime, self::$date);
    }

    /**
     * Vrati predchozi mesic
     * @return Range
     */
    public static function getPreviousMonth() {
        return new Range(new \DateTime('first day of last month'), new \DateTime('last day of last month'));
    }

}
