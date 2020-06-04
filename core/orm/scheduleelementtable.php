<?php
namespace Core\Orm;
use Core\Orm\General\FieldAttributeType;
use Core\Orm\General\TableManager;
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
            'direct_group.NAME' => array(
                FieldAttributeType::FROM_JOIN_TABLE,
                FieldAttributeType::WHERE_ONLY,
            ),
            'TEACHER_ID' => array(),
            'teacher.NAME' => array(
                FieldAttributeType::FROM_JOIN_TABLE,
                FieldAttributeType::WHERE_ONLY,
            ),
            'AUDITORIUM_ID' => array(),
            'auditorium.NAME' => array(
                FieldAttributeType::FROM_JOIN_TABLE,
                FieldAttributeType::WHERE_ONLY,
            ),
            'SUBJECT_ID' => array(),
            'subject.NAME' => array(
                FieldAttributeType::FROM_JOIN_TABLE,
                FieldAttributeType::WHERE_ONLY,
            ),
        );
    }

    protected static function getJoinQuery()
    {
        return 'INNER JOIN direct_group ON direct_group.ID = schedule_element.GROUP_ID INNER JOIN teacher ON teacher.ID = schedule_element.TEACHER_ID INNER JOIN auditorium ON auditorium.ID = schedule_element.AUDITORIUM_ID  INNER JOIN subject ON subject.ID = schedule_element.SUBJECT_ID';
    }
}