<?php

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
	 * @param boolean $timePrefix
	 */
	public function __construct($name = null, $timePrefix = false)
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

	private function getUniqueFile($name)
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
	public function write($str)
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
