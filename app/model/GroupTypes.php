<?php
namespace App\Model;

/**
 * Class GroupTypes
 * @package App\Model
 * @author Vladimír Antoš
 * @version 1.0
 */
class GroupTypes {
    const PRIVATED = "private";
    const SHARED = "shared";

    public static function convert($type){
        return $type == self::PRIVATED ? "Soukromá" : "Sdílená";
    }

    public static function equals($type1, $type2){
        return $type1 == $type2;
    }
}