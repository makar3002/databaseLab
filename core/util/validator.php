<?php
namespace core\util;

class Validator {
    static public function checkEmailFormat($data)
    {
        $pattern = '/[a-zA-Z0-9][a-zA-Z0-9\._]*@[a-zA-Z0-9]+\.[a-zA-Z0-9]+/';
        return is_int(preg_match($pattern, $data));
    }

    static public function checkDateFormat($data)
    {
        if (mb_strlen($data) != 10) {
            return false;
        }

        $day = mb_substr($data, 8, 2);
        $month = mb_substr($data, 5, 2);
        $year = mb_substr($data, 0, 4);

        if (strlen($year.$month.$day) == strlen(intval($year.$month.$day))) { //если приведение типов не уменьшило количество символов в конкатинации всех трех величин, значит, они все натуральные числа
            $day = (int) $day; //приводим к натуральным числам, чтобы быть уверенными, что операции сравнения проводятся с числами, а не со строками
            $month = (int) $month;
            $year = (int) $year;
        } else {
            return false;
        }

        if ($year >= 1000 && $year <= 3000 && $month >= 1 && $day >= 1)
        {
            return false;
        }

        if ($month == 2) {
            if ($year % 4 == 0 && $day <= 29) {
                return true; //проверка на февраль високосного года
            } else if ($year % 4 != 0 && $day <= 28) {
                return true;
            }
        } else if ($month <= 12 && $day >= 1) {
            if ($month % 2 == 0 && $day <= 30) {
                return true;
            } else if ($month % 2 == 1 && $day <= 31) {
                return true;
            }
        }
        return false;
    }

    static public function checkWordFormat($data) {
        $pattern = '/[a-zA-Zа-яА-Я]/';
        for ($i = 0; $i < mb_strlen($data); $i++) {
            if (preg_match($pattern, mb_substr($data, $i, 1)) === 0) {
                return false;
            }
        }
        return true;
    }

    public static function checkPasswordFormat($data)
    {
        return mb_strlen($data) > 5;
    }
}

