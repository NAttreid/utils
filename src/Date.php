<?php

declare(strict_types=1);

namespace NAttreid\Utils;

use Datetime;
use DateTimeInterface;
use Exception;
use InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\SmartObject;

final class Date extends Lang
{
	use SmartObject;

	/** @var string[][] */
	private static array $dayNamesShort = [
		'en' => [1 => 'mon', 2 => 'tue', 3 => 'wed', 4 => 'thu', 5 => 'fri', 6 => 'sat', 7 => 'sun'],
		'cs' => [1 => 'po', 2 => 'út', 3 => 'st', 4 => 'čt', 5 => 'pá', 6 => 'so', 7 => 'ne'],
		'de' => [1 => 'Mo', 2 => 'Di', 3 => 'Mi', 4 => 'Do', 5 => 'Fr', 6 => 'Sa', 7 => 'So'],
		'sk' => [1 => 'po', 2 => 'ut', 3 => 'st', 4 => 'št', 5 => 'pia', 6 => 'so', 7 => 'ne'],
		'pl' => [1 => 'pn', 2 => 'wt', 3 => 'śr', 4 => 'cz', 5 => 'pt', 6 => 'so', 7 => 'n'],
		'es' => [1 => 'Lun', 2 => 'Mar', 3 => 'Mié', 4 => 'Jue', 5 => 'Vie', 6 => 'Sáb', 7 => 'Dom'],
		'hu' => [1 => 'hét', 2 => 'kedd', 3 => 'sze', 4 => 'csüt', 5 => 'pén', 6 => 'szo', 7 => 'vas'],
		'ro' => [1 => 'Lun', 2 => 'Mar', 3 => 'Mie', 4 => 'Joi', 5 => 'Vin', 6 => 'Sâm', 7 => 'Dum'],
		'tr' => [1 => 'Pt', 2 => 'Sa', 3 => 'Ça', 4 => 'Pe', 5 => 'Cu', 6 => 'Ct', 7 => 'Pz'],
		'ru' => [1 => 'Пн', 2 => 'Вт', 3 => 'Ср', 4 => 'Чт', 5 => 'Пт', 6 => 'Сб', 7 => 'Вс'],
		'he' => [1 => "ב'", 2 => "ג'", 3 => "ד'", 4 => "ה'", 5 => "ו'", 6 => "שבת", 7 => "א'"],
		'no' => [1 => 'ma', 2 => 'ti', 3 => 'on', 4 => 'to', 5 => 'fr', 6 => 'lø', 7 => 'sø'],
		'fr' => [1 => 'dim.', 2 => 'lun.', 3 => 'mar.', 4 => 'mer.', 5 => 'jeu.', 6 => 'ven.', 7 => 'sam.'],
	];

	/** @var string[][] */
	private static array $dayNames = [
		'en' => [1 => 'sunday', 2 => 'monday', 3 => 'tuesday', 4 => 'wednesday', 5 => 'thursday', 6 => 'friday', 7 => 'saturday'],
		'cs' => [1 => 'neděle', 2 => 'pondělí', 3 => 'úterý', 4 => 'středa', 5 => 'čtvrtek', 6 => 'pátek', 7 => 'sobota'],
		'de' => [1 => 'Montag', 2 => 'Dienstag', 3 => 'Mittwoch', 4 => 'Donnerstag', 5 => 'Freitag', 6 => 'Samstag', 7 => 'Sonntag'],
		'sk' => [1 => 'pondelok', 2 => 'utorok', 3 => 'streda', 4 => 'štvrtok', 5 => 'piatok', 6 => 'sobota', 7 => 'nedeľa'],
		'pl' => [1 => 'poniedziałek', 2 => 'wtorek', 3 => 'środa', 4 => 'czwartek', 5 => 'piątek', 6 => 'sobota', 7 => 'niedziela'],
		'es' => [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'],
		'hu' => [1 => 'hétfő', 2 => 'kedd', 3 => 'szerda', 4 => 'csütörtök', 5 => 'péntek', 6 => 'szombat', 7 => 'vasárnap'],
		'ro' => [1 => 'Luni', 2 => 'Marţi', 3 => 'Miercuri', 4 => 'Joi', 5 => 'Vineri', 6 => 'Sâmbătă', 7 => 'Duminică'],
		'tr' => [1 => 'Pazartesi', 2 => 'Salı', 3 => 'Çarşamba', 4 => 'Perşembe', 5 => 'Cuma', 6 => 'Cumartesi', 7 => 'Pazar'],
		'ru' => [1 => 'понедельник', 2 => 'вторник', 3 => 'среда', 4 => 'четверг', 5 => 'пятница', 6 => 'суббота', 7 => 'воскресенье'],
		'he' => [1 => 'שני', 2 => 'שלישי', 3 => 'רביעי', 4 => 'חמישי', 5 => 'שישי', 6 => 'שבת', 7 => 'ראשון'],
		'no' => [1 => 'mandag', 2 => 'tirsdag', 3 => 'onsdag', 4 => 'torsdag', 5 => 'fredag', 6 => 'lørdag', 7 => 'søndag'],
		'fr' => [1 => 'dimanche', 2 => 'lundi', 3 => 'mardi', 4 => 'mercredi', 5 => 'jeudi', 6 => 'vendredi', 7 => 'samedi'],
	];

	/** @var string[][] */
	private static array $monthNamesShort = [
		'en' => [1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr', 5 => 'may', 6 => 'jun', 7 => 'jul', 8 => 'aug', 9 => 'sep', 10 => 'oct', 11 => 'nov', 12 => 'dec'],
		'cs' => [1 => 'led', 2 => 'úno', 3 => 'bře', 4 => 'dub', 5 => 'kvě', 6 => 'čer', 7 => 'črn', 8 => 'srp', 9 => 'zář', 10 => 'říj', 11 => 'lis', 12 => 'pro'],
		'de' => [1 => 'Jan', 2 => 'Feb', 3 => 'Mär', 4 => 'Apr', 5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Dez'],
		'sk' => [1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr', 5 => 'máj', 6 => 'jún', 7 => 'júl', 8 => 'aug', 9 => 'sep', 10 => 'okt', 11 => 'nov', 12 => 'dez'],
		'pl' => [1 => 'sty', 2 => 'lu', 3 => 'mar', 4 => 'kw', 5 => 'maj', 6 => 'cze', 7 => 'lip', 8 => 'sie', 9 => 'wrz', 10 => 'pa', 11 => 'lis', 12 => 'gru'],
		'es' => [1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'],
		'hu' => [1 => 'jan', 2 => 'feb', 3 => 'márc', 4 => 'ápr', 5 => 'máj', 6 => 'jún', 7 => 'júl', 8 => 'aug', 9 => 'szept', 10 => 'okt', 11 => 'nov', 12 => 'dec'],
		'ro' => [1 => 'Ian', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mai', 6 => 'Iun', 7 => 'Iul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'],
		'tr' => [1 => 'Oca', 2 => 'Şub', 3 => 'Mar', 4 => 'Nis', 5 => 'May', 6 => 'Haz', 7 => 'Tem', 8 => 'Ağu', 9 => 'Eyl', 10 => 'Eki', 11 => 'Kas', 12 => 'Ara'],
		'ru' => [1 => 'Янв', 2 => 'Фев', 3 => 'Мар', 4 => 'Апр', 5 => 'Май', 6 => 'Июн', 7 => 'Июл', 8 => 'Авг', 9 => 'Сен', 10 => 'Окт', 11 => 'Ноя', 12 => 'Дек'],
		'he' => [1 => 'ינו', 2 => 'פבר', 3 => 'מרץ', 4 => 'אפר', 5 => 'מאי', 6 => 'יוני', 7 => 'יולי', 8 => 'אוג', 9 => 'ספט', 10 => 'אוק', 11 => 'נוב', 12 => 'דצמ'],
		'no' => [1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr', 5 => 'mai', 6 => 'jun', 7 => 'jul', 8 => 'aug', 9 => 'sep', 10 => 'okt', 11 => 'nov', 12 => 'des'],
		'fr' => [1 => 'janv.', 2 => 'févr.', 3 => 'mars.', 4 => 'avr.', 5 => 'mai', 6 => 'juin', 7 => 'juil.', 8 => 'août', 9 => 'sept.', 10 => 'oct.', 11 => 'nov.', 12 => 'déc.'],
	];

	/** @var string[][] */
	private static array $monthNames = [
		'en' => [1 => 'january', 2 => 'february', 3 => 'march', 4 => 'april', 5 => 'may', 6 => 'june', 7 => 'july', 8 => 'august', 9 => 'september', 10 => 'october', 11 => 'november', 12 => 'december'],
		'cs' => [1 => 'leden', 2 => 'únor', 3 => 'březen', 4 => 'duben', 5 => 'květen', 6 => 'červen', 7 => 'červenec', 8 => 'srpen', 9 => 'září', 10 => 'říjen', 11 => 'listopad', 12 => 'prosinec'],
		'de' => [1 => 'Januar', 2 => 'Februar', 3 => 'März', 4 => 'April', 5 => 'Mai', 6 => 'Juni', 7 => 'Juli', 8 => 'August', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Dezember'],
		'sk' => [1 => 'január', 2 => 'február', 3 => 'marec', 4 => 'apríl', 5 => 'máj', 6 => 'jún', 7 => 'júl', 8 => 'august', 9 => 'september', 10 => 'október', 11 => 'november', 12 => 'december'],
		'pl' => [1 => 'styczeń', 2 => 'luty', 3 => 'marzec', 4 => 'kwiecień', 5 => 'maj', 6 => 'czerwiec', 7 => 'lipiec', 8 => 'sierpień', 9 => 'wrzesień', 10 => 'październik', 11 => 'listopad', 12 => 'grudzień'],
		'es' => [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'],
		'hu' => [1 => 'január', 2 => 'február', 3 => 'március', 4 => 'április', 5 => 'május', 6 => 'június', 7 => 'július', 8 => 'augusztus', 9 => 'szeptember', 10 => 'október', 11 => 'november', 12 => 'december'],
		'ro' => [1 => 'Ianuarie', 2 => 'Februarie', 3 => 'Martie', 4 => 'Aprilie', 5 => 'Mai', 6 => 'Iunie', 7 => 'Iulie', 8 => 'August', 9 => 'Septembrie', 10 => 'Octombrie', 11 => 'Noiembrie', 12 => 'Decembrie'],
		'tr' => [1 => 'Oca', 2 => 'Şub', 3 => 'március', 4 => 'április', 5 => 'május', 6 => 'június', 7 => 'július', 8 => 'augusztus', 9 => 'szeptember', 10 => 'október', 11 => 'november', 12 => 'december'],
		'ru' => [1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель', 5 => 'Май', 6 => 'Июнь', 7 => 'Июль', 8 => 'Август', 9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь'],
		'he' => [1 => 'ינואר', 2 => 'פברואר', 3 => 'מרץ', 4 => 'אפריל', 5 => 'מאי', 6 => 'יוני', 7 => 'יולי', 8 => 'אוגוסט', 9 => 'ספטמבר', 10 => 'אוקטובר', 11 => 'נובמבר', 12 => 'דצמבר'],
		'no' => [1 => 'januar', 2 => 'februar', 3 => 'mars', 4 => 'april', 5 => 'mai', 6 => 'juni', 7 => 'juli', 8 => 'august', 9 => 'september', 10 => 'oktober', 11 => 'november', 12 => 'desember'],
		'fr' => [1 => 'janvier', 2 => 'février', 3 => 'mars', 4 => 'avril', 5 => 'mai', 6 => 'juin', 7 => 'juillet', 8 => 'août', 9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre'],
	];

	/** @var string[][] */
	private static array $date = [
		'en' => 'n/j/Y',
		'cs' => 'j.n.Y',
		'de' => 'j.n.Y',
		'sk' => 'j.n.Y',
		'pl' => 'j.n.Y',
		'es' => 'j/n/Y',
		'hu' => 'Y.n.j.',
		'ro' => 'j/n/Y',
		'tr' => 'j/n/Y',
		'ru' => 'j.n.Y',
		'he' => 'j/n/Y',
		'no' => 'j.n.Y',
		'fr' => 'j/n/Y',
	];

	/** @var string[][] */
	private static array $time = [
		'en' => 'G:i',
		'cs' => 'G:i',
		'de' => 'G:i',
		'sk' => 'G:i',
		'pl' => 'G:i',
		'es' => 'G:i',
		'hu' => 'G:i',
		'ro' => 'G:i',
		'tr' => 'G:i',
		'ru' => 'G:i',
		'he' => 'G:i',
		'no' => 'G:i',
	];

	/** @var string[][] */
	private static array $seconds = [
		'en' => ':s',
		'cs' => ':s',
		'de' => ':s',
		'sk' => ':s',
		'pl' => ':s',
		'es' => ':s',
		'hu' => ':s',
		'ro' => ':s',
		'tr' => ':s',
		'ru' => ':s',
		'he' => ':s',
		'no' => ':s',
	];

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

	public static function getYearToActual(int $beginYear): string
	{
		$actualYear = strftime('%Y');
		if ($beginYear == $actualYear) {
			return $actualYear;
		} else {
			return $beginYear . ' - ' . $actualYear;
		}
	}

	public static function getCurrentTimeStamp(): string
	{
		$t = microtime(true);
		$micro = sprintf('%06d', ($t - floor($t)) * 1000000);
		$d = new DateTime(date('Y-m-d H:i:s.' . $micro, (int)$t));
		return $d->format('Y_m_d_H_i_s_u');
	}

	/** @param int|DateTimeInterface $day */
	public static function getDay($day): string
	{
		if ($day instanceof DateTimeInterface) {
			$day = (int)$day->format('N');
		}
		if (!is_int($day)) {
			throw new InvalidArgumentException;
		}
		return self::$dayNames[self::$locale][$day];
	}

	/** @param int|DateTimeInterface $day */
	public static function getShortDay($day): string
	{
		if ($day instanceof DateTimeInterface) {
			$day = (int)$day->format('N');
		}
		if (!is_int($day)) {
			throw new InvalidArgumentException;
		}
		return self::$dayNamesShort[self::$locale][$day];
	}

	/** @param int|DateTimeInterface $month */
	public static function getMonth($month): string
	{
		if ($month instanceof DateTimeInterface) {
			$month = (int)$month->format('n');
		}
		if (!is_int($month)) {
			throw new InvalidArgumentException;
		}
		return self::$monthNames[self::$locale][$month];
	}

	/** @param int|DateTimeInterface $month */
	public static function getShortMonth($month): string
	{
		if ($month instanceof DateTimeInterface) {
			$month = (int)$month->format('n');
		}
		if (!is_int($month)) {
			throw new InvalidArgumentException;
		}
		return self::$monthNamesShort[self::$locale][$month];
	}

	/** @return string[] */
	public static function getDays(): array
	{
		return self::$dayNames[self::$locale];
	}

	/** @return string[] */
	public static function getShortDays(): array
	{
		return self::$dayNamesShort[self::$locale];
	}

	/** @return string[] */
	public static function getMonths(): array
	{
		return self::$monthNames[self::$locale];
	}

	/** @return string[] */
	public static function getShortMonths(): array
	{
		return self::$monthNamesShort[self::$locale];
	}

	/** @param DateTimeInterface|int $datetime */
	private static function formatDate($datetime, string $format): ?string
	{
		if (empty($datetime)) {
			return null;
		} elseif ($datetime instanceof DateTimeInterface) {
			$date = $datetime;
		} else {
			$date = DateTime::createFromFormat('U', (string)$datetime);
		}
		return $date->format($format);
	}

	/** @param DateTimeInterface|int $datetime */
	public static function getDate($datetime): ?string
	{
		return self::formatDate($datetime, self::getFormat(true, false, false));
	}

	/** @param DateTimeInterface|int $datetime */
	public static function getDateTime($datetime, bool $withSeconds = false): ?string
	{
		return self::formatDate($datetime, self::getFormat(true, true, $withSeconds));
	}

	/** @param DateTimeInterface|int $datetime */
	public static function getTime($datetime, bool $withSeconds = false): ?string
	{
		return self::formatDate($datetime, self::getFormat(false, true, $withSeconds));
	}

	public static function getPreviousMonth(): Range
	{
		return new Range(new DateTime('first day of last month'), new DateTime('last day of last month'));
	}
}