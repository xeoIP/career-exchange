<?php

namespace App\Helpers\Validator;

class GlobalValidator
{
    /**
     * @param $value
     * @param $parameters
     * @return bool
     */
    public static function mbBetween($value, $parameters)
    {
        $min = (isset($parameters[0])) ? (int)$parameters[0] : 0;
        $max = (isset($parameters[1])) ? (int)$parameters[1] : 999999;

        $value = strip_tags($value);

        if (mb_strlen($value) < $min) {
            return false;
        } else {
            if (mb_strlen($value) > $max) {
                return false;
            }
        }

        return true;
    }
}
