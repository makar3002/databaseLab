<?php
namespace core\lib\table;


use core\lib\orm\FieldAttributeType;
use core\lib\orm\TableManager;


class SubjectTable extends TableManager
{
    private static $subjectToTeacherList;

    public static function getList($arFields)
    {
        $subjectList = parent::getList($arFields);
        if (
            isset($arFields['select']) && !empty($arFields['select']) && (
                !is_int(array_search('TEACHER_IDS', $arFields['select']))
                || !is_int(array_search('ID', $arFields['select']))
            )
        ) {
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

            foreach ($currentTeachers as $subjectToTeacher) {
                SubjectToTeacherTable::delete($subjectToTeacher['ID']);
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

    public static function getTableMap()
    {
        return array(
            'ID' => array(
                'ATTRIBUTES' => array(
                    FieldAttributeType::PRIMARY,
                    FieldAttributeType::READ_ONLY
                )
            ),
            'NAME' => array(
                'ATTRIBUTES' => array()
            ),
            'INSTITUTE_ID' => array(
                'ATTRIBUTES' => array(),
                'REFERENCE' => array(
                    'TABLE_CLASS' => InstituteTable::class,
                    'SELECT_NAME_MAP' => array(
                        'INSTITUTE_NAME' => 'NAME'
                    )
                ),
            ),
            'TEACHER_IDS' => array(
                'ATTRIBUTES' => array(
                    FieldAttributeType::SELECT_ONLY,
                    FieldAttributeType::ARRAY_VALUE
                )
            ),
        );
    }
}
