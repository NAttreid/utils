<?php

declare(strict_types=1);

namespace NAttreid\Utils;

use DateTime;
use DateTimeInterface;
use Nette\SmartObject;

/**
 * Datum od do
 *
 * @property DateTimeInterface $from datum od
 * @property DateTimeInterface $to datum do
 *
 * @author Attreid <attreid@gmail.com>
 */
class Range
{

	use SmartObject;

	private static $delimiter = '--';
	private static $format = 'Y-m-d';

	/** @var DateTimeInterface */
	private $from;

	/** @var DateTimeInterface */
	private $to;

	public function __construct(DateTimeInterface $from = null, DateTimeInterface $to = null)
	{
		$this->from = $from ?? new DateTime;
		$this->to = $to ?? new DateTime;
	}

	protected function getFrom(): DateTimeInterface
	{
		return $this->from;
	}

	protected function setFrom(DateTimeInterface $from): void
	{
		$this->from = $from;
	}

	protected function getTo(): DateTimeInterface
	{
		return $this->to;
	}

	protected function setTo(DateTimeInterface $to): void
	{
		$this->to = $to;
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
