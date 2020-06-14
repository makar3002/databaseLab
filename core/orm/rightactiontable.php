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

    protected static function getTableMap()
    {
        return array(
            'ID' => array(
                FieldAttributeType::READ_ONLY
            ),
            'CODE' => array(),
            'NAME' => array(),
        );
    }
}