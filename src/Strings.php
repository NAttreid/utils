<?php

declare(strict_types=1);

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
	public static function getKeyWords(string $text, int $maxLen = 60): string
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
	 * @return string[]
	 */
	public static function findEmails(string $text): array
	{
		$result = [];
		preg_match_all('/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i', $text, $result);
		return isset($result[0]) ? $result[0] : [];
	}

	/**
	 * @param string $haystack
	 * @param string|string[] $needle
	 * @return bool
	 */
	public static function contains($haystack, $needle): bool
	{
		if (!is_array($needle)) {
			$needle = [$needle];
		}
		return self::containsArray($haystack, $needle) !== false;
	}

	/**
	 * Vrati retezec, ktery se v textu vyskytuje
	 * @param string $haystack
	 * @param string[] $needle
	 * @return string|null
	 */
	public static function containsArray(string $haystack, array $needle): ?string
	{
		foreach ($needle as $query) {
			if (parent::contains($haystack, $query)) {
				return $query;
			}
		}
		return null;
	}

	/**
	 * @param string $ip IP ve formatu IPV4 napr. 127.0.0.1
	 * @param string $range IP/CIDR maska napr. 127.0.0.0/24, take 127.0.0.1 je akceptovano jako /32
	 * @return bool
	 */
	public static function ipInRange(string $ip, string $range): bool
	{
		if (strpos($range, '/') == false) {
			$range .= '/32';
		}
		// $range je v IP/CIDR formatu (127.0.0.1/24)
		list($range, $netmask) = explode('/', $range, 2);
		$range_decimal = ip2long($range);
		$ip_decimal = ip2long($ip);
		$wildcard_decimal = pow(2, (32 - $netmask)) - 1;
		$netmask_decimal = ~$wildcard_decimal;
		return (($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal));
	}
}
