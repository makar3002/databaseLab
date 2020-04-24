<?php
namespace php\ORM;
require_once ($_SERVER['DOCUMENT_ROOT'].'/php/ORM/general/tablemanager.php');

class AuditoriumTable extends TableManager
{
    public static function getTableName()
    {
        return 'auditorium';
    }

    protected static function getTableMap()
    {
        return array(
            'ID' => array(
                \ORMFieldAttribute::READ_ONLY
            ),
            'NAME' => array(),
            'CAPACITY' => array(),
        );
    }
}