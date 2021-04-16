<?php
namespace core\component\subjectlist;


use core\component\tablelist\DefaultUseTableListComponent;
use core\lib\presentation\SubjectListInteractor;
use core\lib\presentation\TableInteractorCompatible;
use core\lib\table\InstituteTable;
use core\lib\table\TeacherTable;


class SubjectListComponent extends DefaultUseTableListComponent
{
    const DEFAULT_TABLE_NAME = 'Предметы';
    const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 10
        ),
        'NAME' => array(
            'NAME' => 'Название',
            'WIDTH' => 30
        ),
        'INSTITUTE_ID' => array(
            'NAME' => 'Институт',
            'WIDTH' => 20,
            'SORT_CODE' => 'INSTITUTE_NAME'
        ),
        'TEACHER_IDS' => array(
            'NAME' => 'Преподаватели',
            'WIDTH' => 40,
            'IS_MULTIPLE' => true
        ),
    );

    protected function getHeader(): array {
        $header = self::HEADER_COLUMN_MAP;
        $instituteList = InstituteTable::getList(array(
                'order' => array('NAME' => 'ASC')
        ));

        $header['INSTITUTE_ID']['VALUES'] = array();
        foreach ($instituteList as $value) {
            $header['INSTITUTE_ID']['VALUES'][$value['ID']] = $value['NAME'];
        }

        $teacherList = TeacherTable::getList(array(
                'order' => array('NAME' => 'ASC')
        ));

        $header['TEACHER_IDS']['VALUES'] = array();
        foreach ($teacherList as $value) {
            $header['TEACHER_IDS']['VALUES'][$value['ID']] = $value['NAME'];
        }

        return $header;
    }

    protected function getTableName(): string {
        return self::DEFAULT_TABLE_NAME;
    }

    protected function getListInteractorInstance(): TableInteractorCompatible {
        return new SubjectListInteractor();
    }
}
