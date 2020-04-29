<?php
namespace core\orm;
use core\orm\general\FieldAttributeType;
use core\orm\general\TableManager;
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/orm/general/tablemanager.php');

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
                FieldAttributeType::READ_ONLY
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