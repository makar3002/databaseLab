<?php
namespace core\lib\table;


use core\lib\orm\FieldAttributeType;
use core\lib\orm\TableManager;


class RightActionTable extends TableManager
{
    public static function getTableName()
    {
        return 'right_action';
    }

    public static function getTableMap()
    {
        return array(
            'ID' => array(
                'ATTRIBUTES' => array(
                    FieldAttributeType::PRIMARY,
                    FieldAttributeType::READ_ONLY
                )
            ),
            'CODE' => array(
                'ATTRIBUTES' => array()
            ),
            'NAME' => array(
                'ATTRIBUTES' => array()
            ),
        );
    }
}