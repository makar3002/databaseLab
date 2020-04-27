<?php

namespace core\ORM;
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/ORM/general/tablemanager.php');

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
                \ORMFieldAttribute::READ_ONLY
            ),
            'SUBJECT_ID' => array(),
            'TEACHER_ID' => array()
        );
    }
}
