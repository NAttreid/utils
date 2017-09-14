<?php

declare(strict_types=1);

namespace NAttreid\Utils;

use Datetime;
use DateTimeImmutable;
use InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\SmartObject;

/**
 * Pomocna trida pro datum
 *
 * @author Attreid <attreid@gmail.com>
 */
class Date extends Lang
{
	use SmartObject;

	/** @var string[][] */
	private static $dayNamesShort = [
		'en' => [1 => 'mon', 2 => 'tue', 3 => 'wed', 4 => 'thu', 5 => 'fri', 6 => 'sat', 7 => 'sun'],
		'cs' => [1 => 'po', 2 => 'út', 3 => 'st', 4 => 'čt', 5 => 'pá', 6 => 'so', 7 => 'ne'],
		'de' => [1 => 'Mo', 2 => 'Di', 3 => 'Mi', 4 => 'Do', 5 => 'Fr', 6 => 'Sa', 7 => 'So'],
		'sk' => [1 => 'po', 2 => 'ut', 3 => 'st', 4 => 'št', 5 => 'pia', 6 => 'so', 7 => 'ne'],
		'pl' => [1 => 'pn', 2 => 'wt', 3 => 'śr', 4 => 'cz', 5 => 'pt', 6 => 'so', 7 => 'n'],
	];

	/** @var string[][] */
	private static $dayNames = [
		'en' => [1 => 'sunday', 2 => 'monday', 3 => 'tuesday', 4 => 'wednesday', 5 => 'thursday', 6 => 'friday', 7 => 'saturday'],
		'cs' => [1 => 'neděle', 2 => 'pondělí', 3 => 'úterý', 4 => 'středa', 5 => 'čtvrtek', 6 => 'pátek', 7 => 'sobota'],
		'de' => [1 => 'Montag', 2 => 'Dienstag', 3 => 'Mittwoch', 4 => 'Donnerstag', 5 => 'Freitag', 6 => 'Samstag', 7 => 'Sonntag'],
		'sk' => [1 => 'pondelok', 2 => 'utorok', 3 => 'streda', 4 => 'štvrtok', 5 => 'piatok', 6 => 'sobota', 7 => 'nedeľa'],
		'pl' => [1 => 'poniedziałek', 2 => 'wtorek', 3 => 'środa', 4 => 'czwartek', 5 => 'piątek', 6 => 'sobota', 7 => 'niedziela'],
	];

	/** @var string[][] */
	private static $monthNamesShort = [
		'en' => [1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr', 5 => 'may', 6 => 'jun', 7 => 'jul', 8 => 'aug', 9 => 'sep', 10 => 'oct', 11 => 'nov', 12 => 'dec'],
		'cs' => [1 => 'led', 2 => 'úno', 3 => 'bře', 4 => 'dub', 5 => 'kvě', 6 => 'čer', 7 => 'črn', 8 => 'srp', 9 => 'zář', 10 => 'říj', 11 => 'lis', 12 => 'pro'],
		'de' => [1 => 'Jan', 2 => 'Feb', 3 => 'Mär', 4 => 'Apr', 5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Dez'],
		'sk' => [1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr', 5 => 'máj', 6 => 'jún', 7 => 'júl', 8 => 'aug', 9 => 'sep', 10 => 'okt', 11 => 'nov', 12 => 'dez'],
		'pl' => [1 => 'sty', 2 => 'lu', 3 => 'mar', 4 => 'kw', 5 => 'maj', 6 => 'cze', 7 => 'lip', 8 => 'sie', 9 => 'wrz', 10 => 'pa', 11 => 'lis', 12 => 'gru'],
	];

	/** @var string[][] */
	private static $monthNames = [
		'en' => [1 => 'january', 2 => 'february', 3 => 'march', 4 => 'april', 5 => 'may', 6 => 'june', 7 => 'july', 8 => 'august', 9 => 'september', 10 => 'october', 11 => 'november', 12 => 'december'],
		'cs' => [1 => 'leden', 2 => 'únor', 3 => 'březen', 4 => 'duben', 5 => 'květen', 6 => 'červen', 7 => 'červenec', 8 => 'srpen', 9 => 'září', 10 => 'říjen', 11 => 'listopad', 12 => 'prosinec'],
		'de' => [1 => 'Januar', 2 => 'Februar', 3 => 'März', 4 => 'April', 5 => 'Mai', 6 => 'Juni', 7 => 'Juli', 8 => 'August', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Dezember'],
		'sk' => [1 => 'január', 2 => 'február', 3 => 'marec', 4 => 'apríl', 5 => 'máj', 6 => 'jún', 7 => 'júl', 8 => 'august', 9 => 'september', 10 => 'október', 11 => 'november', 12 => 'december'],
		'pl' => [1 => 'styczeń', 2 => 'luty', 3 => 'marzec', 4 => 'kwiecień', 5 => 'maj', 6 => 'czerwiec', 7 => 'lipiec', 8 => 'sierpień', 9 => 'wrzesień', 10 => 'październik', 11 => 'listopad', 12 => 'grudzień'],
	];

	/** @var string[][] */
	private static $date = [
		'en' => 'n/j/Y',
		'cs' => 'j.n.Y',
		'de' => 'j.n.Y',
		'sk' => 'j.n.Y',
		'pl' => 'j.n.Y',
	];

	/** @var string[][] */
	private static $time = [
		'en' => 'G:i',
		'cs' => 'G:i',
		'de' => 'G:i',
		'sk' => 'G:i',
		'pl' => 'G:i',
	];

	/** @var string[][] */
	private static $seconds = [
		'en' => ':s',
		'cs' => ':s',
		'de' => ':s',
	];

	/**
	 * Formatovani
	 * @param bool $date
	 * @param bool $time
	 * @param bool $seconds
	 * @return string
	 */
	public static function getFormat(bool $date = true, bool $time = true, bool $seconds = false): string
	{
		$format = null;
		if ($date) {
			$format .= self::$date[self::$locale];
		}

		if ($time) {
			if (!empty($format)) {
				$format .= ' ';
			}

			$format .= self::$time[self::$locale];

			if ($seconds) {
				$format .= self::$seconds[self::$locale];
			}
		}

		if ($format === null) {
			throw new InvalidStateException;
		}

		return $format;
	}

	/**
	 * Vrati pocatecni rok - aktualni rok. V pripade, ze se shoduji pouze aktualni
	 * @param int $beginYear pocatecni rok
	 * @return string napr: 2012 - 2014 nebo pouze 2014
	 */
	public static function getYearToActual(int $beginYear): string
	{
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
	public static function getCurrentTimeStamp(): string
	{
		$t = microtime(true);
		$micro = sprintf('%06d', ($t - floor($t)) * 1000000);
		$d = new DateTime(date('Y-m-d H:i:s.' . $micro, (int) $t));
		return $d->format('Y_m_d_H_i_s_u');
	}

	/**
	 * Vrati nazev dne
	 * @param int|Datetime $day
	 * @return string
	 */
	public static function getDay($day): string
	{
		if ($day instanceof DateTime) {
			$day = (int) $day->format('N');
		}
		if (!is_int($day)) {
			throw new InvalidArgumentException;
		}
		return self::$dayNames[self::$locale][$day];
	}

	/**
	 * Vrati zkraceny nazev dne
	 * @param int|Datetime $day
	 * @return string
	 */
	public static function getShortDay($day): string
	{
		if ($day instanceof DateTime) {
			$day = (int) $day->format('N');
		}
		if (!is_int($day)) {
			throw new InvalidArgumentException;
		}
		return self::$dayNamesShort[self::$locale][$day];
	}

	/**
	 * Vrati nazev mesice
	 * @param int|Datetime $month
	 * @return string
	 */
	public static function getMonth($month): string
	{
		if ($month instanceof DateTime) {
			$month = (int) $month->format('j');
		}
		if (!is_int($month)) {
			throw new InvalidArgumentException;
		}
		return self::$monthNames[self::$locale][$month];
	}

	/**
	 * Vrati zkraceny nazev mesice
	 * @param int|Datetime $month
	 * @return string
	 */
	public static function getShortMonth($month): string
	{
		if ($month instanceof DateTime) {
			$month = (int) $month->format('j');
		}
		if (!is_int($month)) {
			throw new InvalidArgumentException;
		}
		return self::$monthNamesShort[self::$locale][$month];
	}

	/**
	 * Vrati nazvy dnu
	 * @return string[]
	 */
	public static function getDays(): array
	{
		return self::$dayNames[self::$locale];
	}

	/**
	 * Vrati zkracene nazvy dnu
	 * @return string[]
	 */
	public static function getShortDays(): array
	{
		return self::$dayNamesShort[self::$locale];
	}

	/**
	 * Vrati nazvy mesicu
	 * @return string[]
	 */
	public static function getMonths(): array
	{
		return self::$monthNames[self::$locale];
	}

	/**
	 * Vrati zkracene nazvy mesicu
	 * @return string[]
	 */
	public static function getShortMonths(): array
	{
		return self::$monthNamesShort[self::$locale];
	}

	/**
	 * Vrati lokalizovany format data
	 * @param DateTime|int $datetime
	 * @param string $format
	 * @return string|null
	 */
	private static function formatDate($datetime, string $format): ?string
	{
		if (empty($datetime)) {
			return null;
		} elseif ($datetime instanceof DateTime || $datetime instanceof DateTimeImmutable) {
			$date = $datetime;
		} else {
			$date = DateTime::createFromFormat('U', (string) $datetime);
		}
		return $date->format($format);
	}

	/**
	 * Lokalizovane datum
	 * @param DateTime|int $datetime datum nebo timestamp
	 * @return string|null
	 */
	public static function getDate($datetime): ?string
	{
		return self::formatDate($datetime, self::getFormat(true, false, false));
	}

	/**
	 * Lokalizovane datum s casem
	 * @param DateTime|int $datetime datum nebo timestamp
	 * @param bool $withSeconds
	 * @return null|string
	 */
	public static function getDateTime($datetime, bool $withSeconds = false): ?string
	{
		return self::formatDate($datetime, self::getFormat(true, true, $withSeconds));
	}

	/**
	 * Lokalizovany cas
	 * @param DateTime|int $datetime datum nebo timestamp
	 * @param bool $withSeconds
	 * @return null|string
	 */
	public static function getTime($datetime, bool $withSeconds = false): ?string
	{
		return self::formatDate($datetime, self::getFormat(false, true, $withSeconds));
	}

	/**
	 * Vrati predchozi mesic
	 * @return Range
	 */
	public static function getPreviousMonth(): Range
	{
		return new Range(new DateTime('first day of last month'), new DateTime('last day of last month'));
	}

}