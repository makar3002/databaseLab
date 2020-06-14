<?php

namespace Core\Orm;
use Core\Orm\General\FieldAttributeType;
use Core\Orm\General\TableManager;

class SubjectToTeacherTable extends TableManager
{
    public static function getTableName()
    {
        return 'subject_to_teacher';
    }

    protected static function getTableMap()
    {
        return array(
            'ID' => array(
                FieldAttributeType::READ_ONLY
            ),
            'SUBJECT_ID' => array(),
            'TEACHER_ID' => array()
        );
    }
}
