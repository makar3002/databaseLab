<?php
namespace Core\Component\DirectionList;

use Core\Component\TableList\TableListComparable;
use Core\Orm\InstituteTable;

class DirectionListComponent extends TableListComparable
{
    const DEFAULT_TABLE_NAME = 'Направления';
    const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 20
        ),
        'NAME' => array(
            'NAME' => 'Название направления',
            'WIDTH' => 40
        ),
        'INSTITUTE_ID' => array(
            'NAME' => 'Институт',
            'WIDTH' => 40,
            'SORT_CODE' => 'INSTITUTE_NAME'
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
    }

    protected function prepareData()
    {
        $this->arResult['TABLE_NAME'] = self::DEFAULT_TABLE_NAME;
    }
}
