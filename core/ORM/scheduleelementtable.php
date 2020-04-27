<?php
namespace php\ORM;
require_once ($_SERVER['DOCUMENT_ROOT'].'/core/ORM/general/tablemanager.php');

class ScheduleElementTable extends TableManager
{
    public static function getTableName()
    {
        return 'schedule_element';
    }

    protected static function getTableMap()
    {
        return array(
            'ID' => array(
                \ORMFieldAttribute::READ_ONLY
            ),
            'TYPE' => array(),
            'SUBGROUP' => array(),
            'KIND' => array(),
            'DAY_OF_WEEK' => array(),
            'NUMBER' => array(),
            'GROUP_ID' => array(),
            'TEACHER_ID' => array(),
            'AUDITORIUM_ID' => array()
        );
    }
}