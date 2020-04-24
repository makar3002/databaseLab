<?php
namespace php\ORM;
require_once ($_SERVER['DOCUMENT_ROOT'].'/php/ORM/general/tablemanager.php');

class InstituteTable extends TableManager
{
    public static function getTableName()
    {
        return 'institute';
    }

    protected static function getTableMap()
    {
        return array(
            'ID' => array(
                \ORMFieldAttribute::READ_ONLY
            ),
            'NAME' => array()
        );
    }
}