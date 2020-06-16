<?php
namespace Core\Component\ActionList;

use Core\Component\TableList\TableListComparable;

class ActionListComponent extends TableListComparable
{

    const DEFAULT_TABLE_NAME = 'Действия';
    const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 20
        ),
        'CODE' => array(
            'NAME' => 'Код действия',
            'WIDTH' => 40
        ),
        'NAME' => array(
            'NAME' => 'Название',
            'WIDTH' => 40
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
    }

    protected function prepareData()
    {
        $this->arResult['TABLE_NAME'] = self::DEFAULT_TABLE_NAME;
    }
}
