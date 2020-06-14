<?php
namespace Core\Orm;
use Core\Orm\General\FieldAttributeType;
use Core\Orm\General\TableManager;

class RightGroupTable extends TableManager
{
    public static function getTableName()
    {
        return 'right_group';
    }

    protected static function getTableMap()
    {
        return array(
            'ID' => array(
                FieldAttributeType::READ_ONLY
            ),
            'NAME' => array()
        );
    }
}