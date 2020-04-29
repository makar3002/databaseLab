<?php
namespace core\orm;
use core\orm\general\FieldAttributeType;
use core\orm\general\TableManager;
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/orm/general/tablemanager.php');

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
            'DIRECTION_ID' => array()
        );
    }
}