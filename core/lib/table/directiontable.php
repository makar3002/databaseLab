<?php
namespace core\lib\table;


use core\lib\orm\FieldAttributeType;
use core\lib\orm\TableManager;


class DirectionTable extends TableManager
{
    public static function getTableName()
    {
        return 'direction';
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
            'INSTITUTE_ID' => array(
                'ATTRIBUTES' => array(),
                'REFERENCE' => array(
                    'TABLE_CLASS' => InstituteTable::class,
                    'SELECT_NAME_MAP' => array(
                        'INSTITUTE_NAME' => 'NAME'
                    )
                ),
            )
        );
    }
}