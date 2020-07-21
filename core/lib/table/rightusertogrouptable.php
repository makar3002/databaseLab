<?php
namespace core\lib\table;


use core\lib\orm\FieldAttributeType;
use core\lib\orm\TableManager;


class RightUserToGroupTable extends TableManager
{
    public static function getTableName()
    {
        return 'right_user_to_group';
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
            'GROUP_ID' => array(
                'ATTRIBUTES' => array(),
                'REFERENCE' => array(
                    'TABLE_CLASS' => RightGroupTable::class,
                    'SELECT_NAME_MAP' => array(
                        'GROUP_NAME' => 'NAME'
                    )
                ),
            ),
            'USER_ID' => array(
                'ATTRIBUTES' => array(),
                'REFERENCE' => array(
                    'TABLE_CLASS' => UserTable::class,
                    'SELECT_NAME_MAP' => array(
                        'USER_NAME' => 'NAME',
                        'USER_LAST_NAME' => 'LAST_NAME',
                        'USER_SECOND_NAME'
                    )
                ),
            ),
        );
    }
}