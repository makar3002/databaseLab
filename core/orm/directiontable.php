<?php
namespace Core\Orm;
use Core\Orm\General\FieldAttributeType;
use Core\Orm\General\TableManager;

class DirectionTable extends TableManager
{
    public static function getTableName()
    {
        return 'direction';
    }

    protected static function getTableMap()
    {
        return array(
            'ID' => array(
                FieldAttributeType::READ_ONLY
            ),
            'NAME' => array(),
            'INSTITUTE_ID' => array(),
            'institute.NAME' => array(
                FieldAttributeType::FROM_JOIN_TABLE,
                FieldAttributeType::WHERE_ONLY
            )
        );
    }

    protected static function getJoinQuery()
    {
        return 'INNER JOIN institute ON institute.ID = direction.INSTITUTE_ID';
    }
}