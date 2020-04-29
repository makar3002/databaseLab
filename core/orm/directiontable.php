<?php
namespace core\orm;
use core\orm\general\FieldAttributeType;
use core\orm\general\TableManager;
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/orm/general/tablemanager.php');

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
        );
    }
}