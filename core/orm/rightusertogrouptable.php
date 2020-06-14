<?php
namespace Core\Orm;
use Core\Orm\General\FieldAttributeType;
use Core\Orm\General\TableManager;

class RightUserToGroupTable extends TableManager
{
    public static function getTableName()
    {
        return 'right_user_to_group';
    }

    protected static function getTableMap()
    {
        return array(
            'ID' => array(
                FieldAttributeType::READ_ONLY
            ),
            'GROUP_ID' => array(),
            'USER_ID' => array(),
        );
    }
}