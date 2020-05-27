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

    public static function updateBindEntity($id, $fieldId, $fieldValues)
    {
        if ($fieldId == 'TEACHER_IDS') {
            $currentTeachers = array_column(SubjectToTeacherTable::getList(array(
                'select' => array('ID'),
                'filter' => array('SUBJECT_ID' => $id)
            )), 'ID');

            foreach ($currentTeachers as $subjectToTeacherId) {
                SubjectToTeacherTable::delete($subjectToTeacherId);
            }

            $arNewBind = array(
                'SUBJECT_ID' => $id
            );

            foreach ($fieldValues as $teacherId) {
                $arNewBind['TEACHER_ID'] = $teacherId;
                SubjectToTeacherTable::add($arNewBind);
            }
        }
    }

    public static function deleteBindEntity($id, $fieldId)
    {
        if ($fieldId == 'TEACHER_IDS') {
            $currentTeachers = SubjectToTeacherTable::getList(array(
                'select' => array('ID'),
                'filter' => array('SUBJECT_ID' => $id)
            ));
            foreach ($currentTeachers as $subjectToTeacherId) {
                SubjectToTeacherTable::delete($subjectToTeacherId);
            }
        }
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
            'INSTITUTE_ID' => array(),
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
