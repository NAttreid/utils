<?php

namespace NAttreid\Utils;

use Nette\Database\Table\Selection,
    Nextras\Dbal\QueryBuilder\QueryBuilder,
    Nextras\Orm\Mapper\Dbal\DbalCollection;

/**
 * Pomocna trida pro hashovani
 * 
 * @author Attreid <attreid@gmail.com>
 */
class Hasher {

    private $salt;

    /**
     * Konstruktor tridy
     * @param string $salt
     */
    public function __construct($salt) {
        $this->salt = $salt;
    }

    /**
     * Zahashuje retezec
     * @param string $string
     * @return string
     */
    public function hash($string) {
        return hash('sha256', $string . $this->salt);
    }

    /**
     * Vyhleda podle hashe
     * @param Selection|QueryBuilder|DbalCollection $data
     * @param string $column
     * @param string $hash
     * @return Selection|QueryBuilder
     */
    public function hashSQL($data, $column, $hash) {
        if ($data instanceof DbalCollection) {
            $data = $data->getQueryBuilder();
        }

        if ($data instanceof Selection) {
            return $data->where("SHA2(CONCAT(`$column`,  '{$this->salt}'), 256)", $hash);
        } elseif ($data instanceof QueryBuilder) {
            return $data->andWhere('SHA2(CONCAT(%column, %s), 256) = %s', $column, $this->salt, $hash);
        }
    }

    /**
     * Zkontroluje zda je hash vytvoren z daneho retezce
     * @param string $string
     * @param string $hash
     * @return boolean
     */
    public function check($string, $hash) {
        return $this->hash($string) == $hash;
    }

}
