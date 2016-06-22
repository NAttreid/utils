<?php

namespace NAttreid\Utils;

use Nette\Database\Table\Selection;

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
        return crypt($string, $this->salt);
    }

    /**
     * Vyhleda podle hashe
     * @param Selection $selection
     * @param string $column
     * @param string $hash
     * @return Selection
     */
    public function hashSQL(Selection $selection, $column, $hash) {
        return $selection->where("ENCRYPT(`$column`,  {$this->salt})", $hash);
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
