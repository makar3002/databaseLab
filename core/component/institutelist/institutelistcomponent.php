<?php
namespace core\component\institutelist;

use core\component\tableList\TableListComparable;

class InstituteListComponent extends TableListComparable
{
    const DEFAULT_TABLE_NAME = 'Институты';
    const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 20
        ),
        'NAME' => array(
            'NAME' => 'Институт',
            'WIDTH' => 80
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
