<?php
namespace Core\Component\TableList;
use core\component\general\BaseComponent;
use core\orm\general\TableManager;

class TableListComponent extends BaseComponent
{
    const HEADER_COLUMN_FIELD_MAP = array(
        'NAME' => 'Не задано',
        'WIDTH' => null,
        'CLASS' => ''
    );
    const DEFAULT_BUTTON_COLUMN_WIDTH = '10';
    const DEFAULT_FIELD_VALUE = 'Не задано';
    const DEFAULT_EMPTY_DATA_TITLE = 'Нет данных';
    private $entityClass;

    public function processComponent()
    {
        $this->prepareParams();
        $this->prepareHeader();
        $this->prepareData();
        $this->fillEmptyDataTitle();

        $this->renderComponent();
    }

    public function updateElementAction()
    {
        $this->prepareParams();
        if (!is_subclass_of($this->entityClass, TableManager::class)) {
            return null;
        }

        /** @var TableManager entityTableClass */
        $this->entityClass::update($this->arParams['ID'], json_decode($this->arParams['FIELDS'], true));
    }

    public function getElementInfoAction()
    {
        $this->prepareParams();
        if (!is_subclass_of($this->entityClass, TableManager::class)) {
            return null;
        }

        /** @var TableManager entityTableClass */
        $element = $this->entityClass::getById($this->arParams['ID']);
        return $element;
    }

    public function addElementAction()
    {
        $this->prepareParams();
        if (!is_subclass_of($this->entityClass, TableManager::class)) {
            return null;
        }

        /** @var TableManager entityTableClass */
        $this->entityClass::add(json_decode($this->arParams['FIELDS'], true));
    }

    public function deleteElementAction()
    {
        $this->prepareParams();
        if (!is_subclass_of($this->entityClass, TableManager::class)) {
            return null;
        }

        /** @var TableManager entityTableClass */
        $this->entityClass::delete($this->arParams['ID']);
    }

    private function prepareHeader()
    {
        if (!isset($this->arParams['TABLE_HEADER']) && !is_array($this->arParams['TABLE_HEADER'])) {
            throw new \InvalidArgumentException('Неправильные данные для шапки таблицы.');
        }

        $tableHeader = $this->arParams['TABLE_HEADER'];
        foreach ($tableHeader as &$headerElement) {
            foreach (self::HEADER_COLUMN_FIELD_MAP as $fieldName => $defaultValue) {
                if (isset($headerElement[$fieldName])) {
                    continue;
                }

                if (!isset($defaultValue)) {
                    throw new \InvalidArgumentException('Неправильные данные для шапки таблицы.');
                }

                $headerElement[$fieldName] = $defaultValue;
            }
        }

        $this->arResult['TABLE_HEADER'] = $tableHeader;
        $this->arResult['BUTTON_COLUMN_WIDTH'] = self::DEFAULT_BUTTON_COLUMN_WIDTH;
    }

    private function prepareData()
    {
        if (!isset($this->arResult['TABLE_HEADER'])) {
            throw new \RuntimeException('Перед подготовкой данных для таблицы нужно сначала подготовить её шапку.');
        }

        if (!isset($this->arParams['TABLE_DATA']) && !is_array($this->arParams['TABLE_DATA'])) {
            throw new \InvalidArgumentException('Неправильные данные для таблицы.');
        }

        $tableData = $this->arParams['TABLE_DATA'];
        $tableHeader = $this->arResult['TABLE_HEADER'];
        foreach ($tableData as &$element) {
            foreach ($tableHeader as $fieldName => $fieldParams) {
                if (isset($element[$fieldName])) {
                    continue;
                }

                $element[$fieldName] = self::DEFAULT_FIELD_VALUE;
            }
        }

        $this->arResult['TABLE_DATA'] = $tableData;
        $this->arResult['IS_DATA_EMPTY'] = $this->isTableDataEmpty();
    }

    private function isTableDataEmpty()
    {
        return empty($this->arResult['TABLE_DATA']);
    }

    private function fillEmptyDataTitle()
    {
        if(isset($this->arParams['EMPTY_DATA_TITLE'])) {
            $this->arResult['EMPTY_DATA_TITLE'] = $this->arParams['EMPTY_DATA_TITLE'];
            return;
        }

        $this->arResult['EMPTY_DATA_TITLE'] = self::DEFAULT_EMPTY_DATA_TITLE;
    }

    private function prepareClasses()
    {
        $this->entityClass = $this->arParams['ENTITY_CLASS'];
        $this->arResult['ENTITY_CLASS'] = $this->entityClass;
        $this->arResult['ENTITY_TABLE_CLASS'] = $this->arParams['ENTITY_TABLE_CLASS'];
    }

    private function prepareParams()
    {
        $this->prepareClasses();
        $this->arResult['TABLE_ONLY'] = isset($this->arParams['TABLE_ONLY']) ? $this->arParams['TABLE_ONLY'] : false;
    }
}
