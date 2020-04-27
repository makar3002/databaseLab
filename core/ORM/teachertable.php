<?php
namespace core\ORM;
require_once ($_SERVER['DOCUMENT_ROOT'].'/core/ORM/general/tablemanager.php');

class TeacherTable extends TableManager
{
    public static function getTableName()
    {
        return 'teacher';
    }

    protected static function getTableMap()
    {
        return array(
            'ID' => array(
                \ORMFieldAttribute::READ_ONLY
            ),
            'NAME' => array(),
            'INSTITUTE_ID' => array()
        );
    }
}