<?php

namespace App\Model\Validation;
/**
 * Validation Class. Used for validation of model data
 *
 * Offers different validation methods.
 */
class ExtraValidation
{
    /**
     * Some complex patterns needed in multiple places
     *
     * @var array
     */
    protected static $_pattern = [
		  'phone' => '/^(13|14|15|16|17|18|19)\d{9}$/',
    ];

    public static function isArrayNum($check): bool
    {
        if (!is_array($check)) {
            return false;
        }
        foreach ($check as $v){
            if(!is_numeric($v)){
                return false;
            }
        }

        return true;
    }

    public static function exRang($check, int $min, int $max): bool
    {
        if (!is_scalar($check)) {
            return false;
        }
        $length = mb_strlen((string)$check);

        return $length >= $min && $length <= $max;
    }

	 public static function isPhone($check): bool
    {
        if ((empty($check) && $check !== '0') || !is_scalar($check)) {
            return false;
        }

        return self::_check($check, static::$_pattern['phone'] );
    }
    /**
     * Runs a regular expression match.
     *
     * @param mixed $check Value to check against the $regex expression
     * @param string $regex Regular expression
     * @return bool Success of match
     */
    protected static function _check($check, string $regex): bool
    {
        return is_scalar($check) && preg_match($regex, (string)$check);
    }

}
