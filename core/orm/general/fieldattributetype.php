<?php
namespace Core\Orm\General;
class FieldAttributeType
{
    const READ_ONLY = 'R_O';
    const WHERE_ONLY = 'W_O';
    const SELECT_ONLY = 'S_O';
    const ARRAY_VALUE = 'A_V';
    const SELECT_AND_WHERE_ONLY = 'S_W_O';
    const FROM_JOIN_TABLE = 'F_J_T';
    const REQUIRED = 'R';

    public static function isJoinTableField ($arFieldAttributes)
    {
        if (!self::checkFieldAttributes($arFieldAttributes)) {
            throw new RuntimeException('Ошибка описания поля в классе таблицы.');
        }
        $isJoinTableField = is_int(array_search(self::FROM_JOIN_TABLE, $arFieldAttributes));
        return $isJoinTableField;
    }

    public static function canSelectField ($arFieldAttributes)
    {
        if (!self::checkFieldAttributes($arFieldAttributes)) {
            throw new RuntimeException('Ошибка описания поля в классе таблицы.');
        }
        $isWhereOnly = is_int(array_search(self::WHERE_ONLY, $arFieldAttributes));
        $hasArrayValue = is_int(array_search(self::ARRAY_VALUE, $arFieldAttributes));

        $canSelectField = !$isWhereOnly && !$hasArrayValue;
        return $canSelectField;
    }

    public static function canFilterField ($arFieldAttributes)
    {
        if (!self::checkFieldAttributes($arFieldAttributes)) {
            throw new RuntimeException('Ошибка описания поля в классе таблицы.');
        }
        $isSelectOnly = is_int(array_search(self::SELECT_ONLY, $arFieldAttributes));
        $hasArrayValue = is_int(array_search(self::ARRAY_VALUE, $arFieldAttributes));
        $isSelectAndUpdateOnly = is_int(array_search(self::SELECT_AND_WHERE_ONLY, $arFieldAttributes));

        $canFilterField = !$isSelectOnly && !$hasArrayValue && !$isSelectAndUpdateOnly;
        return $canFilterField;
    }

    public static function canUpdateField ($arFieldAttributes)
    {
        if (!self::checkFieldAttributes($arFieldAttributes)) {
            throw new RuntimeException('Ошибка описания поля в классе таблицы.');
        }
        $isReadOnly = is_int(array_search(self::READ_ONLY, $arFieldAttributes));
        $hasArrayValue = is_int(array_search(self::ARRAY_VALUE, $arFieldAttributes));
        $isSelectOnly = is_int(array_search(self::SELECT_ONLY, $arFieldAttributes));
        $isWhereOnly = is_int(array_search(self::WHERE_ONLY, $arFieldAttributes));

        $canUpdateField = !$isReadOnly && !$hasArrayValue && !$isSelectOnly && !$isWhereOnly;
        return $canUpdateField;
    }

    public static function isRequiredField ($arFieldAttributes)
    {
        if (!self::checkFieldAttributes($arFieldAttributes)) {
            throw new RuntimeException('Ошибка описания поля в классе таблицы.');
        }
        $isRequired = is_int(array_search(self::REQUIRED, $arFieldAttributes));

        return $isRequired;
    }

    public static function canAddField ($arFieldAttributes)
    {
        if (!self::checkFieldAttributes($arFieldAttributes)) {
            throw new RuntimeException('Ошибка описания поля в классе таблицы.');
        }
        $isReadOnly = is_int(array_search(self::READ_ONLY, $arFieldAttributes));
        $hasArrayValue = is_int(array_search(self::ARRAY_VALUE, $arFieldAttributes));

        $canAddField = !$isReadOnly && !$hasArrayValue;
        return $canAddField;
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