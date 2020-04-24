<?php
namespace php\ORM;
require_once ($_SERVER['DOCUMENT_ROOT'].'/php/ORM/general/tablemanager.php');

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
                \ORMFieldAttribute::READ_ONLY
            ),
            'NAME' => array(),
            'COUNT' => array(),
            'DIRECTION_ID' => array()
        );
    }
}