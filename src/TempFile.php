<?php

declare(strict_types=1);

namespace NAttreid\Utils;

use DateTime;
use Exception;
use Nette\SmartObject;
use Nette\Utils\Random;

/**
 * @property string $delimiter
 * @property string $enclosure
 * @property string $escapeChar
 */
class TempFile
{
	use SmartObject;

	/** @var resource */
	private $handler;

	private string $file;
	private string $name;
	private string $delimiter = ';';
	private string $enclosure = '"';
	private string $escapeChar = "\\";

	protected function getDelimiter(): string
	{
		return $this->delimiter;
	}

	protected function setDelimiter(string $delimiter): void
	{
		$this->delimiter = $delimiter;
	}

	protected function getEnclosure(): string
	{
		return $this->enclosure;
	}

	protected function setEnclosure(string $enclosure): void
	{
		$this->enclosure = $enclosure;
	}

	protected function getEscapeChar(): string
	{
		return $this->escapeChar;
	}

	protected function setEscapeChar(string $escapeChar): void
	{
		$this->escapeChar = $escapeChar;
	}

	public function __construct(string $name = null, bool $timePrefix = false)
	{
		if ($name === null) {
			$name = Random::generate();
		}
		if ($timePrefix) {
			$date = new DateTime;
			$name = $date->format('Y-m-d_H-i-s') . '_' . $name;
		}
		$this->name = $name;
		$this->file = $this->getUniqueFile($name);
		$this->handler = fopen($this->file, 'w+');
	}

	private function getUniqueFile(string $name): string
	{
		$file = sys_get_temp_dir() . '/' . $name;
		if (file_exists($file)) {
			$char = Random::generate(1);
			return $this->getUniqueFile($char . '_' . $name);
		} else {
			return $file;
		}
	}

	public function write(string $str): self
	{
		fwrite($this->handler, $str);
		return $this;
	}

	public function puts(string $str): self
	{
		fputs($this->handler, $str);
		return $this;
	}

	public function writeCsv(array $data): self
	{
		fputcsv($this->handler, $data, $this->delimiter, $this->enclosure, $this->escapeChar);
		return $this;
	}

	public function move(string $path): ?string
	{
		$file = $path . '/' . $this->name;
		if (@rename($this->file, $file)) {
			return $file;
		}
		return null;
	}

	public function copy(string $path): ?string
	{
		$file = $path . '/' . $this->name;
		if (@copy($this->file, $file)) {
			return $file;
		}
		return null;
	}

	public function __destruct()
	{
		fclose($this->handler);
		@unlink($this->file);
	}

	public function __toString()
	{
		return $this->file;
	}
}
