<?php
namespace core\ORM;
require_once ($_SERVER['DOCUMENT_ROOT'].'/core/ORM/general/tablemanager.php');

class UserTable extends TableManager
{
    public static function getTableName()
    {
        return 'user';
    }

    protected static function getTableMap()
    {
        return array(
            'ID' => array(
                \ORMFieldAttribute::READ_ONLY
            ),
            'NAME' => array(),
            'LAST_NAME' => array(),
            'SECOND_NAME' => array(),
            'EMAIL' => array(),
            'PASSWORD' => array(
                \ORMFieldAttribute::SELECT_AND_WHERE_ONLY
            )
        );
    }
}