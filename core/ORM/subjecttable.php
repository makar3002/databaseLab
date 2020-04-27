<?php

namespace php\ORM;
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/ORM/general/tablemanager.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/ORM/subjecttoteachertable.php');


class SubjectTable extends TableManager
{
    private static $subjectToTeacherList;

    public static function getList($arFields)
    {
        $subjectList = parent::getList($arFields);
        $subjectIdList = array_column($subjectList, 'ID');
        self::$subjectToTeacherList = SubjectToTeacherTable::getList(array(
            'select' => array('SUBJECT_ID', 'TEACHER_ID'),
            'filter' => array('@SUBJECT_ID' => $subjectIdList)
        ));


        $subjectList = array_map(
            'self::mergeSubjectAndItsTeacherIds',
            $subjectList
        );
        return $subjectList;
    }

    private static function mergeSubjectAndItsTeacherIds($subject)
    {
        $subject['TEACHER_IDS'] = array();

        $subjectId = $subject['ID'];
        foreach (self::$subjectToTeacherList as $key => $subjectToTeacher) {
            if ($subjectToTeacher['SUBJECT_ID'] != $subjectId) {
                continue;
            }

            $subject['TEACHER_IDS'][] = $subjectToTeacher['TEACHER_ID'];
            unset(self::$subjectToTeacherList[$key]);
        }

        return $subject;
    }

    public static function getTableName()
    {
        return 'subject';
    }

    protected static function getTableMap()
    {
        return array(
            'ID' => array(
                \ORMFieldAttribute::READ_ONLY
            ),
            'NAME' => array(),
            'TEACHER_IDS' => array(
                \ORMFieldAttribute::SELECT_ONLY,
                \ORMFieldAttribute::ARRAY_VALUE
            )
        );
    }

//    protected static function getJoinQuery()
//    {
//        $subjectToTeacherTableName = SubjectToTeacherTable::getTableName();
//        return 'INNER JOIN `' . $subjectToTeacherTableName . '` ON ' . self::getTableName() . '.id = `' . $subjectToTeacherTableName . '`.subject_id';
//    }
}
