<?php
namespace core\component\tablelist;


use core\component\general\BaseComponent;
use core\lib\facade\TableInteraction;


abstract class DefaultUseTableListComponent extends BaseComponent implements TableListCompatible {
    protected const DEFAULT_SORT = array(
        'ID' => 'ASC'
    );

    protected TableInteraction $interactionFacade;
    protected array $sort;
    protected string $search;

    public function __construct($arParams) {
        parent::__construct($arParams);
        $this->interactionFacade = $this->getTableInteractionFacadeInstance();
    }

    abstract protected function getTableInteractionFacadeInstance(): TableInteraction;
    abstract protected function getHeader(): array;
    abstract protected function getTableName(): string;

    public function processComponent(): void {
        if (!isset($this->arResult['TABLE_ONLY'])) {
            $this->arResult['TABLE_ONLY'] = false;
        }
        $header = $this->getHeader();
        $this->prepareData($header);
        $this->renderComponent();
    }

    public function getElementInfoAction(): array {
        $element = $this->interactionFacade->getElementInfoByPrimary($this->arParams['ID']);
        return $element;
    }

    public function getTableOnlyAction(): void {
        $this->arResult['TABLE_ONLY'] = true;
        $this->processComponent();
    }

    public function updateElementAction(): void {
        $fields = json_decode($this->arParams['FIELDS'], true);

        try {
            $this->interactionFacade->updateElement($this->arParams['ID'], $fields);
        } catch (\RuntimeException $e) {
            echo $e->getMessage();
        }
    }

    public function addElementAction(): void {
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
            $this->interactionFacade->addElement($fields, $multipleFields);
        } catch (\RuntimeException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteElementAction(): void {
        $isElementDeleted = $this->interactionFacade->deleteElement($this->arParams['ID']);
        if (!$isElementDeleted) {
            echo 'Невозможно удалить элемент';
        }
    }

    protected function prepareData(array $header): void {
        $this->arResult['TABLE_HEADER'] = $header;
        $this->arResult['TABLE_NAME'] = $this->getTableName();
        $sort = $this->getSort();
        $search = $this->getSearch();
        $this->arResult['TABLE_DATA'] = $this->getRows($sort, $search);
        $this->arResult['TABLE_SORT'] = $sort;
    }

    protected function getRows($sort, $search): array {
        $elementList = $this->interactionFacade->getElementList($sort, $search);

        $elementIdList = array_column($elementList, 'ID');
        return array_combine($elementIdList, $elementList);
    }

    protected function getSort(): array {
        if (isset($this->sort)) {
            return $this->sort;
        }

        if (isset($this->arParams['SORT'])) {
            $sort = json_decode($this->arParams['SORT'], true);
        } else {
            $sort = static::DEFAULT_SORT;
        }
        $this->sort = $sort;
        return $sort;
    }

    protected function getSearch(): string {
        if (isset($this->search)) {
            return $this->search;
        }

        $search = isset($this->arParams['SEARCH']) ? $this->arParams['SEARCH'] : '';
        $this->search = $search;
        return $search;
    }
}