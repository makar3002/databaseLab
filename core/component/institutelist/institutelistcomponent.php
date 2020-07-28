<?php
namespace core\component\institutelist;

use core\component\tableList\DefaultUseTableListComponent;
use core\lib\facade\Institute;
use core\lib\facade\TableInteraction;

class InstituteListComponent extends DefaultUseTableListComponent
{
    private const DEFAULT_TABLE_NAME = 'Институты';
    private const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 20
        ),
        'NAME' => array(
            'NAME' => 'Институт',
            'WIDTH' => 80
        ),
    );
    private const DEFAULT_SORT = array(
        'ID' => 'ASC'
    );

    private $sort;
    private $search;

    protected function getTableInteractionFacadeInstance(): TableInteraction
    {
        return new Institute();
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

    protected function prepareHeader()
    {
        $this->arResult['TABLE_HEADER'] = self::HEADER_COLUMN_MAP;
    }

    protected function prepareData()
    {
        $this->arResult['TABLE_NAME'] = self::DEFAULT_TABLE_NAME;
        $this->arResult['TABLE_DATA'] = $this->getRows();
        $this->arResult['TABLE_SORT'] = $this->getSort();
    }

    private function getRows(): array {
        $sort = $this->getSort();
        $search = $this->getSearch();


        $elementList = $this->interactionFacade->getElementList($sort, $search);

        $elementIdList = array_column($elementList, 'ID');
        return array_combine($elementIdList, $elementList);
    }

    private function getSort(): array {
        if (isset($this->sort)) {
            return $this->sort;
        }

        if (isset($this->arParams['SORT'])) {
            $sort = json_decode($this->arParams['SORT'], true);
        } else {
            $sort = self::DEFAULT_SORT;
        }
        $this->sort = $sort;
        return $sort;
    }

    private function getSearch(): string {
        if (isset($this->search)) {
            return $this->search;
        }

        $search = isset($this->arParams['SEARCH']) ? $this->arParams['SEARCH'] : '';
        $this->search = $search;
        return $search;
    }
}
