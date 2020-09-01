<?php
namespace core\lib\table;


use core\lib\orm\FieldAttributeType;
use core\lib\orm\TableManager;


class RightActionToGroupTable extends TableManager
{
    public static function getTableName()
    {
        return 'right_action_to_group';
    }

    public static function getTableMap()
    {
        return array(
            'ID' => array(
                'ATTRIBUTES' => array(
                    FieldAttributeType::PRIMARY,
                    FieldAttributeType::READ_ONLY
                ),
            ),
            'GROUP_ID' => array(
                'ATTRIBUTES' => array(),
                'REFERENCE' => array(
                    'TABLE_CLASS' => RightGroupTable::class,
                    'SELECT_NAME_MAP' => array(
                        'GROUP_NAME' => 'NAME'
                    )
                ),
            ),
            'ACTION_ID' => array(
                'ATTRIBUTES' => array(),
                'REFERENCE' => array(
                    'TABLE_CLASS' => RightActionTable::class,
                    'SELECT_NAME_MAP' => array(
                        'ACTION_NAME' => 'NAME'
                    )
                ),
            ),
        );
    }
}