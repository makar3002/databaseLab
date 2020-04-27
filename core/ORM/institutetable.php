<?php
namespace core\ORM;
require_once ($_SERVER['DOCUMENT_ROOT'].'/core/ORM/general/tablemanager.php');

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