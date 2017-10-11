<?php

declare(strict_types=1);

namespace NAttreid\Utils;

use DateTime;
use DateTimeInterface;

/**
 * Datum od do
 *
 * @author Attreid <attreid@gmail.com>
 */
class Range
{

	private static $delimiter = '--';
	private static $format = 'Y-m-d';

	/**
	 * Datum od
	 * @var DateTimeInterface
	 */
	public $from;

	/**
	 * Datum do
	 * @var DateTimeInterface
	 */
	public $to;

	public function __construct(DateTimeInterface $from = null, DateTimeInterface $to = null)
	{
		$this->from = $from ?: new DateTime;
		$this->to = $to ?: new DateTime;
	}

	/**
	 * Vytvori objekt z retezce intervalu
	 * @param string $interval
	 * @return self
	 */
	public static function createFromString(string $interval): self
	{
		list($from, $to) = explode(self::$delimiter, $interval);

		$dateFrom = DateTime::createFromFormat(self::$format, $from);
		$dateTo = DateTime::createFromFormat(self::$format, $to);

		return new self($dateFrom, $dateTo);
	}

	public function __toString()
	{
		return $this->from->format(self::$format) . self::$delimiter . $this->to->format(self::$format);
	}

}
