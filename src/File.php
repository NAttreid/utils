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

final class File
{
	public static function removeDir(string $directory, bool $removeDir = true): void
	{
		$dir = @dir($directory);
		if ($dir) {
			while ($file = $dir->read()) {
				if ($file == '.' || $file == '..') {
					continue;
				} elseif (is_dir($dir->path . DIRECTORY_SEPARATOR . $file)) {
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

	public static function isDirEmpty(string $path): bool
	{
		return (count(glob("$path/*")) === 0);
	}

	/** @throws IOException */
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
	 * @param string|array $sourcePath
	 */
	public static function zip($sourcePath, string $outZipPath): void
	{
		$zipFile = new ZipArchive();
		$zipFile->open($outZipPath, ZipArchive::CREATE);

		if (is_array($sourcePath)) {
			foreach ($sourcePath as $source) {
				self::addToZip((string)$source, $zipFile);
			}
		} else {
			self::addToZip((string)$sourcePath, $zipFile);
		}

		$zipFile->close();
	}

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
	 * @param callable $callable function($buffer, $line) $line -> line number, if false exit
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

	/** @throws IOException */
	public static function parseXml(string $file): SimpleXMLElement
	{
		libxml_use_internal_errors(true);
		$xml = simplexml_load_file($file);

		if (!$xml) {
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

	public static function isImageValid(string $file): bool
	{
		$type = @exif_imagetype($file);
		switch ($type) {
			case 1:
				$img = @imagecreatefromgif($file);
				break;
			case 2:
				$img = @imagecreatefromjpeg($file);
				break;
			case 3:
				$img = @imagecreatefrompng($file);
				break;
			default:
				return false;
		}
		if ($img) {
			$imageW = imagesx($img);
			$imageH = imagesy($img);

			$last_height = $imageH - 5;

			$foo = [];

			for ($x = 0; $x <= $imageW; $x++) {
				for ($y = $last_height; $y <= $imageH; $y++) {
					$rgb = @imagecolorat($img, $x, $y);

					$r = ($rgb >> 16) & 0xFF;
					$g = ($rgb >> 8) & 0xFF;
					$b = $rgb & 0xFF;

					if ($r != 0) {
						$foo[] = $r;
					}
				}
			}

			$bar = array_count_values($foo);

			$gray = ($bar['127'] ?? 0) + ($bar['128'] ?? 0) + ($bar['129'] ?? 0);
			$total = count($foo);
			$other = $total - $gray;

			return $other >= $gray;
		}
		return false;
	}
}
