<?php

namespace nattreid\helpers;

/**
 * Pomocna trida pro pole
 * 
 * @author Attreid <attreid@gmail.com>
 */
class Arrays {

    public static function isAssoc($arr) {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

}
