<?php

declare(strict_types = 1);

namespace NAttreid\Utils;

use Nette\IOException;
use Nette\Utils\Finder;
use SplFileInfo;
use ZipArchive;

/**
 * Pomocna trida pro praci se soubory
 *
 * @author Attreid <attreid@gmail.com>
 */
class File
{

	/**
	 * Smazani adresare
	 * @param string $directory adresar
	 * @param bool $removeDir smazat adresar (false smaze jen obsah)
	 */
	public static function removeDir(string $directory, bool $removeDir = true)
	{
		$dir = @dir($directory);
		if ($dir) {
			while ($file = $dir->read()) {
				if ($file == '.' || $file == '..') {
					continue;
				} else if (is_dir($dir->path . DIRECTORY_SEPARATOR . $file)) {
					self::removeDir($dir->path . DIRECTORY_SEPARATOR . $file);
				} else {
					unlink($dir->path . DIRECTORY_SEPARATOR . $file);
				}
			}
			if ($removeDir) {
				rmdir($dir->path);
			}
			$dir->close();
		}
	}

	/**
	 * Je adresar prazdny?
	 * @param string $path
	 * @return bool
	 */
	public static function isDirEmpty(string $path): bool
	{
		return (count(glob("$path/*")) === 0);
	}

	/**
	 * Rozbaleni adresare ZIP
	 * @param string $archive
	 * @param string $dir
	 * @param bool $remove
	 * @throws IOException
	 */
	public static function extractZip(string $archive, string $dir, bool $remove = false)
	{
		$zip = new ZipArchive();
		$x = $zip->open($archive);
		if ($x === true) {
			if (!$zip->extractTo($dir)) {
				throw new IOException("File '$archive' cannot be extracted");
			}
			$zip->close();
			if ($remove) {
				unlink($archive);
			}
		} else {
			throw new IOException("File '$archive' cannot be opened: $x");
		}
	}

	/**
	 * Zazipuje soubor/y nebo adresar|e
	 *
	 * @param string|array $sourcePath cesta k adresari k archivaci
	 * @param string $outZipPath cesta k vystupnimu souboru zip
	 */
	public static function zip(string $sourcePath, string $outZipPath)
	{
		$zipFile = new ZipArchive();
		$zipFile->open($outZipPath, ZipArchive::CREATE);

		if (is_array($sourcePath)) {
			foreach ($sourcePath as $source) {
				self::addToZip($source, $zipFile);
			}
		} else {
			self::addToZip($sourcePath, $zipFile);
		}


		$zipFile->close();
	}

	/**
	 * Prida source do zipu
	 * @param string $sourcePath
	 * @param ZipArchive $zipFile
	 */
	private static function addToZip(string $sourcePath, ZipArchive $zipFile)
	{
		$source = new SplFileInfo($sourcePath);
		$exclusiveLength = strlen(str_replace($source->getFilename(), '', $source->getRealPath()));

		if ($source->isDir()) {
			$zipFile->addEmptyDir($source->getFilename());

			foreach (Finder::findFiles('*')
						 ->from($sourcePath) as $file) {
				$filePath = $file->getRealPath();
				$localPath = substr($filePath, $exclusiveLength);

				if ($file->isDir()) {
					$zipFile->addEmptyDir($localPath);
				} else {
					$zipFile->addFile($filePath, $localPath);
				}
			}
		} else {
			$zipFile->addFile($source->getRealPath(), $source->getFilename());
		}
	}

	/**
	 * Rozbaleni adresare GZ
	 * @param string $archive
	 * @param string $sufix
	 * @throws IOException
	 */
	public static function extractGZ(string $archive, string $sufix = null)
	{
		if ($sfp = @gzopen($archive, "rb")) {
			$source = str_replace('.gz', '', $archive);
			if ($sufix != null) {
				$source .= '.' . $sufix;
			}
			if ($fp = @fopen($source, "w")) {

				while (!gzeof($sfp)) {
					$string = gzread($sfp, 4096);
					if (!fwrite($fp, $string, strlen($string))) {
						throw new IOException("File '$source' cannot be write.");
					}
				}
				fclose($fp);
			} else {
				throw new IOException("File '$source' cannot be write.");
			}
			gzclose($sfp);
			unlink($archive);
		} else {
			throw new IOException("File '$archive' cannot be read.");
		}
	}

	/**
	 * Cteni ze souboru po radcich
	 * @param string $file
	 * @param callable $callable function($line)
	 * @throws IOException
	 */
	public static function readFileLine(string $file, callable $callable)
	{
		if (!$handle = fopen($file, "r")) {
			throw new IOException("File '$file' cannot be open.");
		}
		while (($line = fgets($handle, 4096)) !== false) {
			$callable($line);
		}
		if (!feof($handle)) {
			throw new IOException("Error: unexpected fgets() fail '$file'");
		}
		fclose($handle);
	}

	/**
	 * Vrati velikost souboru nebo slozky
	 * @param string $path
	 * @return float
	 */
	public static function size(string $path): float
	{
		if (is_file($path)) {
			return filesize($path);
		} else {
			$size = 0;
			foreach (glob(rtrim($path, '/') . '/*', GLOB_NOSORT) as $each) {
				$size += self::size($each);
			}
			return $size;
		}
	}

	/**
	 * Vrati nazvy trid v souboru
	 * @param string $file
	 * @return string[]
	 */
	public static function getClasses(string $file): array
	{
		$php_code = file_get_contents($file);
		$classes = [];
		$tokens = token_get_all($php_code);
		$count = count($tokens);
		for ($i = 2; $i < $count; $i++) {
			if ($tokens[$i - 2][0] == T_CLASS && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING) {
				$class_name = $tokens[$i][1];
				$classes[] = $class_name;
			}
		}
		return $classes;
	}

}
