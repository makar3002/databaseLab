<?php
namespace Core\Component\SubjectList;
use core\component\general\BaseComponent;
use core\orm\InstituteTable;
use core\orm\TeacherTable;

class SubjectListComponent extends BaseComponent
{
    const DEFAULT_TABLE_NAME = 'Преподаватели';
    const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 10
        ),
        'NAME' => array(
            'NAME' => 'ФИО',
            'WIDTH' => 30
        ),
        'INSTITUTE_ID' => array(
            'NAME' => 'Институт',
            'WIDTH' => 20
        ),
        'TEACHER_IDS' => array(
            'NAME' => 'Преподаватели',
            'WIDTH' => 40,
            'IS_MULTIPLE' => true
        ),
    );

    public function getTableOnlyAction()
    {
        $this->arResult['TABLE_ONLY'] = true;
        if (isset($this->arParams['SORT'])) {
            $this->arResult['TABLE_SORT'] = json_decode($this->arParams['SORT'], true);
        }
        $this->processComponent();
    }

    public function processComponent()
    {
        if (!isset($this->arResult['TABLE_ONLY'])) {
            $this->arResult['TABLE_ONLY'] = false;
        }
        $this->prepareHeader();
        $this->prepareData();
        $this->renderComponent();
    }

    private function prepareHeader()
    {
        $this->arResult['TABLE_HEADER'] = self::HEADER_COLUMN_MAP;
        $instituteList = InstituteTable::getList(array(
            'order' => array('NAME' => 'ASC')
        ));

        $this->arResult['TABLE_HEADER']['INSTITUTE_ID']['VALUES'] = array();
        foreach ($instituteList as $value) {
            $this->arResult['TABLE_HEADER']['INSTITUTE_ID']['VALUES'][$value['ID']] = $value['NAME'];
        }

        $instituteList = TeacherTable::getList(array(
            'order' => array('NAME' => 'ASC')
        ));

        $this->arResult['TABLE_HEADER']['TEACHER_IDS']['VALUES'] = array();
        foreach ($instituteList as $value) {
            $this->arResult['TABLE_HEADER']['TEACHER_IDS']['VALUES'][$value['ID']] = $value['NAME'];
        }
    }

    private function prepareData()
    {
        $this->arResult['TABLE_NAME'] = self::DEFAULT_TABLE_NAME;
    }
}
