<?php

namespace NAttreid\Utils;

/**
 * Pomocna trida pro retezce
 *
 * @author Attreid <attreid@gmail.com>
 */
class Strings extends \Nette\Utils\Strings
{

	/**
	 * Vrati klicova slova z textu
	 * @param string $text
	 * @param int $maxLen
	 * @return string
	 */
	public static function getKeyWords($text, $maxLen = 60)
	{
		$keyWords = [];
		// nalezeni vsech linku v textu
		preg_match_all('/<a[^>]*>[^<]+<\/a>/i', $text, $found);
		if (isset($found[0])) {
			foreach ($found[0] as $kw) {
				$keyWords[] = self::lower(self::trim(strip_tags($kw)));
			}
		}

		// h2 a strong
		preg_match_all('/<(h2|strong)[^>]*>[^<]+<\/(h2|strong)>/i', $text, $found);
		if (isset($found[0])) {
			foreach ($found[0] as $kw) {
				$keyWords[] = self::lower(self::trim(strip_tags($kw)));
			}
		}

		$result = implode(', ', array_unique($keyWords));

		return self::truncate($result, $maxLen, '');
	}

	/**
	 * Vrati email z textu
	 * @param string $text
	 * @return array
	 */
	public static function findEmails($text)
	{
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
	public static function ifEmpty(&$var, $default = null)
	{
		if (empty($var)) {
			$var = $default;
		}
		return $var;
	}

	/**
	 * @param string $haystack
	 * @param string|string[] $needle
	 * @return bool
	 */
	public static function contains($haystack, $needle)
	{
		if (!is_array($needle)) {
			$needle = [$needle];
		}
		foreach ($needle as $query) {
			if (parent::contains($haystack, $query)) {
				return true;
			}
		}
		return false;
	}
}
