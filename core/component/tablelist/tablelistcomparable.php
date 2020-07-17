<?php
namespace core\component\tableList;

use core\component\general\BaseComponent;

abstract class TableListComparable extends BaseComponent
{
    public function getTableOnlyAction()
    {
        $this->arResult['TABLE_ONLY'] = true;
        if (isset($this->arParams['SORT'])) {
            $this->arResult['TABLE_SORT'] = json_decode($this->arParams['SORT'], true);
        }
        if (isset($this->arParams['SORT'])) {
            $this->arResult['TABLE_SEARCH'] = $this->arParams['SEARCH'];
        }
        $this->processComponent();
    }

    abstract protected function prepareHeader();
    abstract protected function prepareData();
}