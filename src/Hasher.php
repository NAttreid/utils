<?php

declare(strict_types=1);

namespace NAttreid\Utils;

use Nette\Database\Table\Selection;
use Nette\InvalidArgumentException;
use Nextras\Dbal\QueryBuilder\QueryBuilder;
use Nextras\Orm\Collection\DbalCollection;

final class Hasher
{
	private string $salt;

	public function __construct(string $salt)
	{
		$this->salt = $salt;
	}

	public function hash(string $string): string
	{
		return hash('sha256', $string . $this->salt);
	}

	/**
	 * @param Selection|QueryBuilder|DbalCollection $data
	 * @param string|array $columns
	 * @param string $hash
	 * @return Selection|QueryBuilder
	 * @throws InvalidArgumentException
	 */
	public function hashSQL($data, $columns, string $hash)
	{
		if (is_string($columns)) {
			$columns = [$columns];
		}

		if (is_array($columns)) {
			if ($data instanceof DbalCollection) {
				$data = $data->getQueryBuilder();
			}

			if ($data instanceof Selection) {
				$col = '';
				foreach ($columns as $column) {
					if ($col !== '') {
						$col .= ',';
					}
					$col .= "`$column`";
				}
				return $data->where("SHA2(CONCAT($col,  '{$this->salt}'), 256)", $hash);
			} elseif ($data instanceof QueryBuilder) {
				return $data->andWhere('SHA2(CONCAT(%column[], %s), 256) = %s', $columns, $this->salt, $hash);
			}
		}
		throw new InvalidArgumentException;
	}

	public function check(string $string, string $hash): bool
	{
		return hash_equals($this->hash($string), $hash);
	}

}
