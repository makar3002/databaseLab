<?php
namespace core\component\grouplist;

use core\component\tablelist\TableListComparable;
use core\lib\table\DirectionTable;

class GroupListComponent extends TableListComparable
{
    const DEFAULT_TABLE_NAME = 'Студентческие группы';
    const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 10
        ),
        'NAME' => array(
            'NAME' => 'Группа',
            'WIDTH' => 20
        ),
        'COUNT' => array(
            'NAME' => 'Количество студентов',
            'WIDTH' => 40
        ),
        'DIRECTION_ID' => array(
            'NAME' => 'Направление',
            'WIDTH' => 30,
            'SORT_CODE' => 'DIRECTION_NAME'
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
        $directionList = DirectionTable::getList(array(
            'order' => array('NAME' => 'ASC')
        ));

        $this->arResult['TABLE_HEADER']['DIRECTION_ID']['VALUES'] = array();
        foreach ($directionList as $value) {
            $this->arResult['TABLE_HEADER']['DIRECTION_ID']['VALUES'][$value['ID']] = $value['NAME'];
        }
    }

    protected function prepareData()
    {
        $this->arResult['TABLE_NAME'] = self::DEFAULT_TABLE_NAME;
    }
}
