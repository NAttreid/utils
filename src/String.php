<?php

namespace nattreid\helpers;

use Nette\Utils\Strings;

/**
 * Pomocna trida pro retezce
 * 
 * @author Attreid <attreid@gmail.com>
 */
class String {

    /**
     * Vrati klicova slova z textu
     * @param string $text
     * @param int $maxLen
     * @return string
     */
    public static function getKeyWords($text, $maxLen = 60) {
        $keyWords = [];
        // nalezeni vsech linku v textu
        preg_match_all('/<a[^>]*>[^<]+<\/a>/i', $text, $found);
        if (isset($found[0])) {
            foreach ($found[0] as $kw) {
                $keyWords[] = Strings::lower(Strings::trim(strip_tags($kw)));
            }
        }

        // h2 a strong
        preg_match_all('/<(h2|strong)[^>]*>[^<]+<\/(h2|strong)>/i', $text, $found);
        if (isset($found[0])) {
            foreach ($found[0] as $kw) {
                $keyWords[] = Strings::lower(Strings::trim(strip_tags($kw)));
            }
        }

        $result = implode(', ', array_unique($keyWords));

        return Strings::truncate($result, $maxLen, '');
    }

    /**
     * Vrati email z textu
     * @param string $text
     * @return array
     */
    public static function findEmails($text) {
        $result = [];
        preg_match_all('/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i', $text, $result);
        return isset($result[0]) ? $result[0] : [];
    }

    /**
     * Nastavi promenou na defaultni hodnotu pokud je prazdna
     * @param string $var
     * @param string $default
     * @return mixed
     */
    public static function ifEmpty(&$var, $default = NULL) {
        if (empty($var)) {
            $var = $default;
        }
        return $var;
    }

}
