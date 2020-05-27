<?php
namespace Core\Component\GroupList;
use core\component\general\BaseComponent;
use core\orm\DirectionTable;

class GroupListComponent extends BaseComponent
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
            'WIDTH' => 30
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
        $instituteList = DirectionTable::getList(array(
            'order' => array('NAME' => 'ASC')
        ));

        $this->arResult['TABLE_HEADER']['DIRECTION_ID']['VALUES'] = array();
        foreach ($instituteList as $value) {
            $this->arResult['TABLE_HEADER']['DIRECTION_ID']['VALUES'][$value['ID']] = $value['NAME'];
        }
    }

    private function prepareData()
    {
        $this->arResult['TABLE_NAME'] = self::DEFAULT_TABLE_NAME;
    }
}
