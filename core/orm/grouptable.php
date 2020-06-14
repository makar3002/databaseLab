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
                FieldAttributeType::READ_ONLY
            ),
            'NAME' => array(),
            'COUNT' => array(),
            'DIRECTION_ID' => array(),
            'direction.NAME' => array(
                FieldAttributeType::FROM_JOIN_TABLE,
                FieldAttributeType::WHERE_ONLY,
            )
        );
    }

    protected static function getJoinQuery()
    {
        return 'INNER JOIN direction ON direction.ID = direct_group.DIRECTION_ID';
    }
}