<?php

namespace nattreid\helpers;

/**
 * Pomocna trida pro cisla
 * 
 * @author Attreid <attreid@gmail.com>
 */
class Number extends Lang {

    /**
     * Vrati cislo v binarni velikosti
     * @param int $number
     * @return int
     */
    private static function getBinary($number) {
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

    /**
     * Vrati zformatovane cislo
     * @param float $number
     * @param int $decimal
     * @return string
     */
    public static function getNumber($number, $decimal = 2) {
        if (is_numeric($number) && floor($number) == $number) {
            $decimal = 0;
        }

        switch (self::$locale) {
            default:
            case 'cs':
                return number_format($number, $decimal, ',', ' ');
            case 'en':
                return number_format($number, $decimal);
        }
    }

    /**
     * Procenta
     * @param float $number
     * @param float $total
     * @param int $decimal
     * @return string
     */
    public static function percent($number, $total, $decimal = 2) {
        return self::getNumber($number / $total * 100, $decimal) . '%';
    }

    /**
     * Frekvence
     * @param float $number
     * @return string
     */
    public static function frequency($number) {
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

    /**
     * Velikost souboru
     * @param float $number
     * @param int $decimal
     * @param boolean $binary
     * @return string
     */
    public static function size($number, $decimal = 2, $binary = FALSE) {
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
