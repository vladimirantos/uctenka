<?php
namespace App\Model;
use Nette\SmartObject;

/**
 * Porovnává zadaný objekt s aktuálním.
 * Vrací:
 *  -1 aktuální objekt větší než zadaný
 *   0 jsou si oba objekty rovny
 *   1 zadaný objekt je větší než aktuální
 */
interface IComparable{

    /**
     * @param BaseObject $obj
     * @return int
     */
    function compareTo(BaseObject $obj);
}


/**
 * @author Vladimír Antoš
 * @version 1.0.0
 */
abstract class BaseObject {
    use SmartObject;

    /**
     * @param mixed $obj
     * @return bool
     */
    public function equals(BaseObject $obj) {
        return $this == $obj;
    }

    /**
     * Metoda zjištuje, jestli jsou objekty stejné.
     * @param BaseObject $obj
     * @return bool
     */
    final public function instanceEquals(BaseObject $obj) {
        return $this === $obj;
    }

    /**
     * Vrací typ zadané proměnné. Pokud je proměnná neznámého typu, metoda vrací false.
     * @param mixed $var
     * @return string
     */
    final public function type($var) {
        return gettype($var);
    }

    /**
     * Vrací název zadané třídy.
     * @param Object $obj
     * @param bool $onlyName Pokud je false, metoda vrací název včetně jmeného prostoru.
     * @return string
     */
    final public function getClassType($obj, $onlyName = true) {
        $classname = get_class($obj);
        if ($onlyName && preg_match('@\\\\([\w]+)$@', $classname, $matches)) {
            $classname = $matches[1];
        }
        return $classname;
    }

    /**
     * Vrací hash hodnotu objektu.
     * @return string
     */
    public function getHashCode() {
        return spl_object_hash($this);
    }

    /**
     * @return int
     */
    public function getTime() {
        return time();
    }

    /**
     * @return string
     */
    public function toString() {
        return get_class($this);
    }

    /**
     * @return string
     */
    public function serialize() {
        return serialize($this);
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->toString();
    }
}


class DateTime extends BaseObject{
    private $year;
    private $month;
    private $day;

    /**
     * DateTime constructor.
     * @param $year
     * @param $month
     * @param $day
     */
    public function __construct($year, $month, $day) {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    /**
     * @return mixed
     */
    public function getYear() {
        return $this->year;
    }

    /**
     * @return mixed
     */
    public function getMonth() {
        return $this->month;
    }

    /**
     * @return mixed
     */
    public function getDay() {
        return $this->day;
    }

    public function toString() {
        return $this->year . '-' . $this->month . '-' . $this->day;
    }


    public static function now() {
        $date = date("Y-m-d");
        $datearr = explode('-', $date);
        return new DateTime($datearr[0], $datearr[1], $datearr[2]);
    }
}