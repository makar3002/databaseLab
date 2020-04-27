<?php
class ORMFieldAttribute
{
    const READ_ONLY = 'R_O';
    const WHERE_ONLY = 'W_O';
    const SELECT_ONLY = 'S_O';
    const ARRAY_VALUE = 'A_V';
    const SELECT_AND_WHERE_ONLY = 'S_W_O';

    public static function canSelectField ($arFieldAttributes)
    {
        if (!self::checkFieldAttributes($arFieldAttributes)) {
            throw new RuntimeException('Ошибка описания поля в классе таблицы.');
        }
        $isWhereOnly = array_search(self::WHERE_ONLY, $arFieldAttributes);
        $hasArrayValue = array_search(self::ARRAY_VALUE, $arFieldAttributes);

        $canSelectField = !$isWhereOnly && !$hasArrayValue;
        return $canSelectField;
    }

    public static function canFilterField ($arFieldAttributes)
    {
        if (!self::checkFieldAttributes($arFieldAttributes)) {
            throw new RuntimeException('Ошибка описания поля в классе таблицы.');
        }
        $isSelectOnly = array_search(self::SELECT_ONLY, $arFieldAttributes);
        $hasArrayValue = array_search(self::ARRAY_VALUE, $arFieldAttributes);
        $isSelectAndUpdateOnly = array_search(self::SELECT_AND_WHERE_ONLY, $arFieldAttributes);

        $canFilterField = !$isSelectOnly && !$hasArrayValue && !$isSelectAndUpdateOnly;
        return $canFilterField;
    }

    public static function canUpdateField ($arFieldAttributes)
    {
        if (!self::checkFieldAttributes($arFieldAttributes)) {
            throw new RuntimeException('Ошибка описания поля в классе таблицы.');
        }
        $isReadOnly = array_search(self::READ_ONLY, $arFieldAttributes);
        $hasArrayValue = array_search(self::ARRAY_VALUE, $arFieldAttributes);
        $isSelectOnly = array_search(self::SELECT_ONLY, $arFieldAttributes);
        $isWhereOnly = array_search(self::WHERE_ONLY, $arFieldAttributes);

        $canUpdateField = !$isReadOnly && !$hasArrayValue && !$isSelectOnly && !$isWhereOnly;
        return $canUpdateField;
    }

    private static function checkFieldAttributes ($arFieldAttributes)
    {
        if (!is_array($arFieldAttributes)) {
            return false;
        }
        $isSelectOnly = false;
        $isWhereOnly = false;

        foreach ($arFieldAttributes as $attr) {
            if (!is_string($attr)) {
                return false;
            }

            if ($attr === self::SELECT_ONLY) {
                $isSelectOnly = true;
            }

            if ($attr === self::WHERE_ONLY) {
                $isWhereOnly = true;
            }
        }

        $isSelectAndWhereAtTheSameTime = $isWhereOnly && $isSelectOnly;

        return !$isSelectAndWhereAtTheSameTime;
    }
}