<?php
namespace Core\Component\InstituteList;
use core\component\general\BaseComponent;
use core\orm\InstituteTable;

class InstituteListComponent extends BaseComponent
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
        $this->arResult['TABLE_DATA'] = $this->getRows();
    }

    private function getRows()
    {
        $instituteList = InstituteTable::getList(array());
        $instituteIdsList = array_column($instituteList, 'ID');

        return array_combine($instituteIdsList, $instituteList);
    }
}
