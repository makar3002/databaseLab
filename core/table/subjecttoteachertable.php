<?php

namespace core\table;

use core\util\orm\FieldAttributeType;
use core\util\orm\TableManager;

class SubjectToTeacherTable extends TableManager
{
    public static function getTableName()
    {
        return 'subject_to_teacher';
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
            'SUBJECT_ID' => array(
                'ATTRIBUTES' => array(),
                'REFERENCE' => array(
                    'TABLE_CLASS' => SubjectTable::class,
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
                        'ACTION_NAME' => 'NAME'
                    )
                ),
            ),
        );
    }
}
