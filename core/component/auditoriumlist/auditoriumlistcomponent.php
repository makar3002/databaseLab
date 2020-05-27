<?php
namespace Core\Component\AuditoriumList;
use core\component\general\BaseComponent;
use core\orm\InstituteTable;

class AuditoriumListComponent extends BaseComponent
{
    const DEFAULT_TABLE_NAME = 'Аудитории';
    const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 20
        ),
        'NAME' => array(
            'NAME' => 'Номер аудитории',
            'WIDTH' => 40
        ),
        'CAPACITY' => array(
            'NAME' => 'Вместимость',
            'WIDTH' => 40
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
    }

    private function prepareData()
    {
        $this->arResult['TABLE_NAME'] = self::DEFAULT_TABLE_NAME;
    }
}
