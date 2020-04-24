<?php
namespace php\ORM;
require_once ($_SERVER['DOCUMENT_ROOT'].'/php/ORM/general/tablemanager.php');

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
                \ORMFieldAttribute::READ_ONLY
            ),
            'NAME' => array(),
            'INSTITUTE_ID' => array(),
        );
    }
}