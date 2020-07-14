<?php
namespace Core\Orm;
use Core\Orm\General\FieldAttributeType;
use Core\Orm\General\TableManager;

class GroupTable extends TableManager
{
    public static function getTableName()
    {
        return 'direct_group';
    }

    protected static function getTableMap()
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
            'COUNT' => array(
                'ATTRIBUTES' => array()
            ),
            'DIRECTION_ID' => array(
                'ATTRIBUTES' => array(),
                'REFERENCE' => array(
                    'TABLE_CLASS' => DirectionTable::class,
                    'SELECT_NAME_MAP' => array(
                        'DIRECTION_NAME' => 'NAME'
                    )
                ),
            )
        );
    }
}