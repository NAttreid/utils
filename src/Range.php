<?php

declare(strict_types=1);

namespace NAttreid\Utils;

use DateTime;
use DateTimeInterface;
use Nette\SmartObject;

/**
 * @property DateTimeInterface $from datum od
 * @property DateTimeInterface $to datum do
 */
class Range
{

	use SmartObject;

	private static string $delimiter = '--';
	private static string $format = 'Y-m-d';

	private ?DateTimeInterface $from;
	private ?DateTimeInterface $to;

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
