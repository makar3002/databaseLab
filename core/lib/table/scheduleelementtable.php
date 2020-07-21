<?php
namespace core\lib\table;


use core\lib\db\DB;
use core\lib\orm\FieldAttributeType;
use core\lib\orm\TableManager;


class ScheduleElementTable extends TableManager
{
    public static function getScheduleByDirectionId($directionId)
    {
        $query = 'CALL GET_SCHEDULE_BY_DIRECTION_ID(' . $directionId. ');';

        $db = DB::getInstance();
        $db->prepare($query);
        $result = $db->execute();
        return $result->fetchAll();
    }

    public static function getTableName()
    {
        return 'schedule_element';
    }

    public static function getTableMap()
    {
        return array(
            'ID' => array(
                'ATTRIBUTES' => array(
                    FieldAttributeType::PRIMARY,
                    FieldAttributeType::READ_ONLY
                )
            ),
            'TYPE' => array(
                'ATTRIBUTES' => array()
            ),
            'SUBGROUP' => array(
                'ATTRIBUTES' => array()
            ),
            'KIND' => array(
                'ATTRIBUTES' => array()
            ),
            'DAY_OF_WEEK' => array(
                'ATTRIBUTES' => array()
            ),
            'NUMBER' => array(
                'ATTRIBUTES' => array()
            ),
            'GROUP_ID' => array(
                'ATTRIBUTES' => array(),
                'REFERENCE' => array(
                    'TABLE_CLASS' => GroupTable::class,
                    'SELECT_NAME_MAP' => array(
                        'GROUP_NAME' => 'NAME'
                    )
                ),
            ),
            'TEACHER_ID' => array(
                'ATTRIBUTES' => array(),
                'REFERENCE' => array(
                    'TABLE_CLASS' => TeacherTable::class,
                    'SELECT_NAME_MAP' => array(
                        'TEACHER_NAME' => 'NAME'
                    )
                ),
            ),
            'AUDITORIUM_ID' => array(
                'ATTRIBUTES' => array(),
                'REFERENCE' => array(
                    'TABLE_CLASS' => AuditoriumTable::class,
                    'SELECT_NAME_MAP' => array(
                        'AUDITORIUM_NAME' => 'NAME'
                    )
                ),
            ),
            'SUBJECT_ID' => array(
                'ATTRIBUTES' => array(),
                'REFERENCE' => array(
                    'TABLE_CLASS' => SubjectTable::class,
                    'SELECT_NAME_MAP' => array(
                        'SUBJECT_NAME' => 'NAME'
                    )
                ),
            ),
        );
    }
}