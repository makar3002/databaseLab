<?php
namespace Core\Component\TableList;

use Core\Component\General\BaseComponent;
use Core\Orm\General\TableManager;

class TableListComponent extends BaseComponent
{
    const HEADER_COLUMN_FIELD_MAP = array(
        'NAME' => 'Не задано',
        'WIDTH' => null,
        'CLASS' => ''
    );
    const DEFAULT_SORT = array(
        'ID' => 'ASC'
    );
    const DEFAULT_BUTTON_COLUMN_WIDTH = '10';
    const DEFAULT_FIELD_VALUE = 'Не задано';
    const DEFAULT_EMPTY_DATA_TITLE = 'Нет данных';
    /** @var TableManager $entityClass */
    private $entityClass;

    public function processComponent()
    {
        $this->prepareParams();
        $this->prepareHeader();
        $this->prepareData();
        $this->fillEmptyDataTitle();

        $this->renderComponent();
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

    public function updateElementAction()
    {
        $this->prepareParams();
        if (!is_subclass_of($this->entityClass, TableManager::class)) {
            return null;
        }
        $fields = json_decode($this->arParams['FIELDS'], true);

        try {
            foreach ($fields as $fieldName => $value) {
                if (!is_array($value)) {
                    continue;
                }

                $this->entityClass::updateBindEntity($this->arParams['ID'], $fieldName, $value);
                unset($fields[$fieldName]);
            }

            /** @var TableManager this->entityClass*/
            $this->entityClass::update($this->arParams['ID'], $fields);
        } catch (\RuntimeException $e) {
            echo $e->getMessage();
        }
    }

    public function addElementAction()
    {
        $this->prepareParams();
        if (!is_subclass_of($this->entityClass, TableManager::class)) {
            return null;
        }

        $fields = json_decode($this->arParams['FIELDS'], true);
        $multipleFields = array();
        foreach ($fields as $fieldName => $value) {
            if (!is_array($value)) {
                continue;
            }
            $multipleFields[$fieldName] = $value;
            unset($fields[$fieldName]);
        }

        try {
            /** @var TableManager this->entityClass*/
            $elementId = $this->entityClass::add($fields);
        } catch (\RuntimeException $e) {
            echo $e->getMessage();
        }

        foreach ($multipleFields as $fieldName => $value) {
            $this->entityClass::updateBindEntity($elementId, $fieldName, $value);
        }
    }

    public function deleteElementAction()
    {
        $this->prepareParams();
        if (!is_subclass_of($this->entityClass, TableManager::class)) {
            return null;
        }

        /** @var TableManager entityTableClass */
        if (!$this->entityClass::delete($this->arParams['ID'])) {
            echo 'Невозможно удалить элемент';
        }
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

//        if (!isset($this->arParams['TABLE_DATA']) && !is_array($this->arParams['TABLE_DATA'])) {
//            //throw new \InvalidArgumentException('Неправильные данные для таблицы.');
//        }

        $tableData = $this->getRows();
        $tableHeader = $this->arResult['TABLE_HEADER'];
        foreach ($tableData as &$element) {
            foreach ($tableHeader as $fieldName => $fieldParams) {
                if (isset($element[$fieldName]) && !empty($element[$fieldName])) {
                    continue;
                }

                $element[$fieldName] = self::DEFAULT_FIELD_VALUE;
            }
        }

        $this->arResult['TABLE_DATA'] = $tableData;
        $this->arResult['IS_DATA_EMPTY'] = $this->isTableDataEmpty();
    }

    private function getRows()
    {
        $this->arResult['TABLE_SORT'] = self::DEFAULT_SORT;
        if (isset($this->arParams['TABLE_SORT'])) {
            $this->arResult['TABLE_SORT'] = $this->arParams['TABLE_SORT'];
        }

        $elementList = $this->entityClass::getList(array(
            'order' => $this->arResult['TABLE_SORT'],
            'search' => isset($this->arParams['TABLE_SEARCH']) ? $this->arParams['TABLE_SEARCH'] : null
        ));

        $elementIdsList = array_column($elementList, 'ID');
        return array_combine($elementIdsList, $elementList);
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
