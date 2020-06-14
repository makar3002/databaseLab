<?php
namespace Core\Orm;
use Core\Orm\General\FieldAttributeType;
use Core\Orm\General\TableManager;

class RightActionToGroupTable extends TableManager
{
    public static function getTableName()
    {
        return 'right_action_to_group';
    }

    protected static function getTableMap()
    {
        return array(
            'ID' => array(
                FieldAttributeType::READ_ONLY
            ),
            'GROUP_ID' => array(),
            'ACTION_ID' => array()
        );
    }
}