<?php

declare(strict_types = 1);

namespace NAttreid\Utils;

use Nette\Utils\Random;

/**
 * Docasny soubor
 *
 * @author Attreid <attreid@gmail.com>
 */
class TempFile
{

	/** @var string */
	private $file;

	/** @var resource */
	private $handler;

	/**
	 *
	 * @param string $name pokud je null vygeneruje se random
	 * @param bool $timePrefix
	 */
	public function __construct(string $name = null, bool $timePrefix = false)
	{
		if ($name === null) {
			$name = Random::generate();
		}
		if ($timePrefix) {
			$date = new \DateTime;
			$name = $date->format('Y-m-d_H-i-s') . '_' . $name;
		}
		$this->file = $this->getUniqueFile($name);
		$this->handler = fopen($this->file, 'w+');
	}

	/**
	 * @param string $name
	 * @return string
	 */
	private function getUniqueFile(string $name): string
	{
		$file = sys_get_temp_dir() . '/' . $name;
		if (file_exists($file)) {
			$file .= Random::generate(1);
			return $this->getUniqueFile($file);
		} else {
			return $file;
		}
	}

	/**
	 * Zapise do souboru
	 * @param string $str
	 */
	public function write(string $str)
	{
		fwrite($this->handler, $str);
	}

	public function __destruct()
	{
		fclose($this->handler);
		unlink($this->file);
	}

	public function __toString()
	{
		return $this->file;
	}

}
