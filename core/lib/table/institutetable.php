<?php
namespace core\lib\table;


use core\lib\orm\FieldAttributeType;
use core\lib\orm\TableManager;


class InstituteTable extends TableManager
{
    public static function getTableName()
    {
        return 'institute';
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
            'NAME' => array(
                'ATTRIBUTES' => array()
            ),
        );
    }
}