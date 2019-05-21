<?php

declare(strict_types=1);

namespace NAttreid\Utils;

use LibXMLError;
use Nette\IOException;
use Nette\Utils\Finder;
use SimpleXMLElement;
use SplFileInfo;
use SplFileObject;
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
	public static function removeDir(string $directory, bool $removeDir = true): void
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
	public static function extractZip(string $archive, string $dir, bool $remove = false): void
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
	public static function zip($sourcePath, string $outZipPath): void
	{
		$zipFile = new ZipArchive();
		$zipFile->open($outZipPath, ZipArchive::CREATE);

		if (is_array($sourcePath)) {
			foreach ($sourcePath as $source) {
				self::addToZip((string) $source, $zipFile);
			}
		} else {
			self::addToZip((string) $sourcePath, $zipFile);
		}

		$zipFile->close();
	}

	/**
	 * Prida source do zipu
	 * @param string $sourcePath
	 * @param ZipArchive $zipFile
	 */
	private static function addToZip(string $sourcePath, ZipArchive $zipFile): void
	{
		$source = new SplFileInfo($sourcePath);
		$exclusiveLength = strlen(str_replace($source->getFilename(), '', $source->getRealPath()));

		if ($source->isReadable()) {
			if ($source->isDir()) {
				$zipFile->addEmptyDir($source->getFilename());

				foreach (Finder::findFiles('*')
							 ->from($sourcePath) as $file) {
					/* @var $file SplFileObject */
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
	}

	/**
	 * Rozbaleni adresare GZ
	 * @param string $archive
	 * @param string $sufix
	 * @throws IOException
	 */
	public static function extractGZ(string $archive, string $sufix = null): void
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
	 * @param callable $callable function($buffer, $line) $line -> cislo radku, pokud metoda vrati false, ukonci se cyklus
	 * @param int|null $length
	 * @throws IOException
	 */
	public static function readLine(string $file, callable $callable, ?int $length = 4096): void
	{
		if (!$handle = fopen($file, "r")) {
			throw new IOException("File '$file' cannot be open.");
		}

		$fgets = function () use (&$handle, &$length) {
			if ($length !== null) {
				return fgets($handle, $length);
			} else {
				return fgets($handle);
			}
		};

		$line = 1;
		$stopped = false;
		while (($buffer = $fgets()) !== false) {
			if ($callable($buffer, $line++) === false) {
				$stopped = true;
				break;
			}
		}
		if (!$stopped && !feof($handle)) {
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

	/**
	 * Parsuje XML
	 * @param string $file
	 * @return SimpleXMLElement
	 * @throws IOException
	 */
	public static function parseXml(string $file): SimpleXMLElement
	{
		libxml_use_internal_errors(true);
		$xml = simplexml_load_file($file);

		if (!$xml) {
			/* @var $errors LibXMLError[] */
			$errors = libxml_get_errors();

			$return = '';
			foreach ($errors as $error) {
				switch ($error->level) {
					case LIBXML_ERR_WARNING:
						$return .= "Warning $error->code ";
						break;
					case LIBXML_ERR_ERROR:
						$return .= "Error $error->code ";
						break;
					case LIBXML_ERR_FATAL:
						$return .= "Fatal Error $error->code ";
						break;
				}
				$return .= "on line $error->line and column $error->column: " . trim($error->message) . ". ";
			}

			$return .= "File: $file";

			libxml_clear_errors();
			libxml_use_internal_errors(false);
			throw new IOException($return);
		}
		libxml_use_internal_errors(false);
		return $xml;
	}
}
