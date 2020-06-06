<?php
namespace Core\Component\Schedule;

use Core\Component\General\BaseComponent;
use Core\Orm\ScheduleElementTable;

class ScheduleComponent extends BaseComponent
{
    public function processComponent()
    {
        if (isset($this->arParams['DIRECTION_ID'])) {
            $this->arResult['DIRECTION_ID'] = $this->arParams['DIRECTION_ID'];
        }
        $this->prepareData();
        $this->renderComponent();
    }

    public function refreshTable()
    {
        if (isset($this->arParams['DIRECTION_ID'])) {
            $this->arResult['DIRECTION_ID'] = $this->arParams['DIRECTION_ID'];
        }
        $this->processComponent();
    }

    private function prepareData()
    {
        if (isset($this->arResult['DIRECTION_ID']) && intval($this->arResult['DIRECTION_ID']) > 0) {
            $directionId = intval($this->arResult['DIRECTION_ID']);
            $this->arResult['TABLE_DATA'] = ScheduleElementTable::getScheduleByDirectionId($directionId);
        } else {
            $this->arResult['TABLE_DATA'] = array();
        }
    }

}
