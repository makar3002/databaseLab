<?php

namespace core\orm;
use core\orm\general\FieldAttributeType;
use core\orm\general\TableManager;
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/orm/general/tablemanager.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/orm/subjecttoteachertable.php');


class SubjectTable extends TableManager
{
    private static $subjectToTeacherList;

    public static function getList($arFields)
    {
        $subjectList = parent::getList($arFields);
        if (isset($arFields['select']) && !array_search('TEACHER_IDS', $arFields['select'])) {
            return $subjectList;
        }

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
                FieldAttributeType::READ_ONLY
            ),
            'NAME' => array(),
            'TEACHER_IDS' => array(
                FieldAttributeType::SELECT_ONLY,
                FieldAttributeType::ARRAY_VALUE
            )
        );
    }

//    protected static function getJoinQuery()
//    {
//        $subjectToTeacherTableName = SubjectToTeacherTable::getTableName();
//        return 'INNER JOIN `' . $subjectToTeacherTableName . '` ON ' . self::getTableName() . '.id = `' . $subjectToTeacherTableName . '`.subject_id';
//    }
}
