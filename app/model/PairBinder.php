<?php
/**
 * Created by PhpStorm.
 * User: vladi
 * Date: 03.09.2016
 * Time: 16:25
 */

namespace App\Model;


class PairBinder {

    public static function bind(array $data, $key, $value){
        $result = [];
        foreach ($data as $item) {
            $k = $item[$key];
            $v = $item[$value];
            $result[$k] = $v;
        }
        return $result;
    }
}