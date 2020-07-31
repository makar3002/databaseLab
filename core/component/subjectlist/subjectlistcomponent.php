<?php
namespace core\component\subjectlist;

use core\component\tablelist\TableListComparable;
use core\lib\table\InstituteTable;
use core\lib\table\TeacherTable;

class SubjectListComponent extends TableListComparable
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

    public function processComponent()
    {
        if (!isset($this->arResult['TABLE_ONLY'])) {
            $this->arResult['TABLE_ONLY'] = false;
        }
        $this->prepareHeader();
        $this->prepareData();
        $this->renderComponent();
    }

    protected function prepareHeader()
    {
        $this->arResult['TABLE_HEADER'] = self::HEADER_COLUMN_MAP;
        $instituteList = InstituteTable::getList(array(
            'order' => array('NAME' => 'ASC')
        ));

        $this->arResult['TABLE_HEADER']['INSTITUTE_ID']['VALUES'] = array();
        foreach ($instituteList as $value) {
            $this->arResult['TABLE_HEADER']['INSTITUTE_ID']['VALUES'][$value['ID']] = $value['NAME'];
        }

        $teacherList = TeacherTable::getList(array(
            'order' => array('NAME' => 'ASC')
        ));

        $this->arResult['TABLE_HEADER']['TEACHER_IDS']['VALUES'] = array();
        foreach ($teacherList as $value) {
            $this->arResult['TABLE_HEADER']['TEACHER_IDS']['VALUES'][$value['ID']] = $value['NAME'];
        }
    }

    protected function prepareData()
    {
        $this->arResult['TABLE_NAME'] = self::DEFAULT_TABLE_NAME;
    }
}
