<?php

namespace App\Helpers;

class Number
{
    /**
     * @param $number
     * @return int|mixed|string
     */
    public static function short($number)
    {
        if (!is_numeric($number)) {
            return $number;
        }

        $number = self::format($number);

        return $number;
    }

    /**
     * @param $number
     * @return mixed|string
     */
    public static function format($number)
    {
        // Convert string to numeric
        $number = self::rawFormat($number);

        // Currency format - Ex: USD 100,234.56 | EUR 100 234,56
        $number = number_format($number, (int) config('currency.decimal_places', 2), config('currency.decimal_separator', '.'), config('currency.thousand_separator', ','));

        return $number;
    }

    /**
     * @param $number
     * @return mixed|string
     */
    public static function rawFormat($number)
    {
        if (is_numeric($number)) {
            return $number;
        }

        $number = trim($number);
        $number = strtr($number, array(' ' => ''));
        $number = preg_replace('/ +/', '', $number);
        $number = str_replace(',', '.', $number);
        $number = preg_replace('/[^0-9\.]/', '', $number);

        return $number;
    }

    /**
     * @param $number
     * @return int|mixed|string
     */
    public static function money($number)
    {
        if (config('settings.decimals_superscript')) {
            return static::moneySuperscript($number);
        }

        $number = self::short($number);

        // In line current
        if (config('currency.in_left') == 1) {
            $number = config('currency.symbol') . $number;
        } else {
            $number = $number . ' ' . config('currency.symbol');
        }

        // Remove decimal value if it's null
        $defaultDecimal = str_pad('', (int) config('currency.decimal_places', 2), '0');
        $number = str_replace(config('currency.decimal_separator', '.') . $defaultDecimal, '', $number);

        return $number;
    }

    /**
     * @param $number
     * @return int|mixed|string
     */
    public static function moneySuperscript($number)
    {
        $number = self::short($number);

        $tmp = explode(config('currency.decimal_separator', '.'), $number);

        if (isset($tmp[1]) && !empty($tmp[1])) {
            if (config('currency.in_left') == 1) {
                $number = config('currency.symbol') . $tmp[0] . '<sup>' . $tmp[1] . '</sup>';
            } else {
                $number = $tmp[0] . '<sup>'. config('currency.symbol') . $tmp[1] . '</sup>';
            }
        } else {
            if (config('currency.in_left') == 1) {
                $number = config('currency.symbol') . $number;
            } else {
                $number = $number . ' ' . config('currency.symbol');
            }

            // Remove decimal value if it's null
            $defaultDecimal = str_pad('', (int) config('currency.decimal_places', '2'), '0');
            $number = str_replace(config('currency.decimal_separator', '.') . $defaultDecimal, '', $number);
        }

        return $number;
    }

    /**
     * @param null $locale
     * @return array
     */
    public static function getSeparators($locale = null)
    {
        if (empty($locale)) {
            $locale = config('app.locale');
        }

        $separators = [];
        $separators['thousand'] = (starts_with($locale, 'fr')) ? ' ' : ',';
        $separators['decimal'] = (starts_with($locale, 'fr')) ? ',' : '.';

        return $separators;
    }

    /**
     * @return mixed|string
     */
    public static function setLanguage()
    {
        $localeCode = config('app.locale');

        // Set locale
        setlocale(LC_ALL, $localeCode);

        return $localeCode;
    }

    /**
     * @param $int
     * @param $nb
     * @return string
     */
    public static function leadZero($int, $nb)
    {
        $diff = $nb - strlen($int);
        if ($diff <= 0) {
            return $int;
        } else {
            return str_repeat('0', $diff) . $int;
        }
    }

    /**
     * @param $number
     * @param $limit
     * @return mixed
     */
    public static function zeroPad($number, $limit)
    {
        return (strlen($number) >= $limit) ? $number : self::zeroPad("0" . $number, $limit);
    }

    /**
     * @param $number
     * @param int $decimals
     * @return string
     */
    public static function localeFormat($number, $decimals = 2)
    {
        self::setLanguage();

        $locale = localeconv();
        $number = number_format($number, $decimals, $locale['decimal_point'], $locale['thousands_sep']);

        return $number;
    }

    /**
     * @param $num
     * @return float
     * @todo: test me
     */
    public static function tofloat($num)
    {
        $dot_pos = strrpos($num, '.');
        $comma_pos = strrpos($num, ',');
        $sep = (($dot_pos > $comma_pos) && $dot_pos) ? $dot_pos : ((($comma_pos > $dot_pos) && $comma_pos) ? $dot_pos : false);

        if (!$sep) {
            return floatval(preg_replace("/[^0-9]/", "", $num));
        }

        return floatval(preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' . preg_replace("/[^0-9]/", "",
                substr($num, $sep + 1, strlen($num))));
    }
}
