<?php
namespace core\component\teacherlist;


use core\component\tablelist\DefaultUseTableListComponent;
use core\lib\presentation\TableInteractorCompatible;
use core\lib\presentation\TeacherListInteractor;
use core\lib\table\InstituteTable;


class TeacherListComponent extends DefaultUseTableListComponent
{
    const DEFAULT_TABLE_NAME = 'Преподаватели';
    const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 20
        ),
        'NAME' => array(
            'NAME' => 'ФИО',
            'WIDTH' => 40
        ),
        'INSTITUTE_ID' => array(
            'NAME' => 'Институт',
            'WIDTH' => 40,
            'SORT_CODE' => 'INSTITUTE_NAME'
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

        return $header;
    }

    protected function getTableName(): string {
        return self::DEFAULT_TABLE_NAME;
    }

    protected function getListInteractorInstance(): TableInteractorCompatible {
        return new TeacherListInteractor();
    }
}
