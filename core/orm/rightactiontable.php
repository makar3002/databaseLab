<?php
namespace Core\Orm;
use Core\Orm\General\FieldAttributeType;
use Core\Orm\General\TableManager;

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