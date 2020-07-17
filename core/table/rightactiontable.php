<?php
namespace core\table;

use core\util\orm\FieldAttributeType;
use core\util\orm\TableManager;

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