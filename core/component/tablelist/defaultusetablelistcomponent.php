<?php
namespace core\component\tableList;

use core\component\general\BaseComponent;
use core\lib\facade\TableInteraction;

abstract class DefaultUseTableListComponent extends BaseComponent
{
    protected $interactionFacade;
    public function __construct($arParams)
    {
        parent::__construct($arParams);
        $this->interactionFacade = $this->getTableInteractionFacadeInstance();
    }

    protected function getTableInteractionFacadeInstance(): TableInteraction {
        throw new \Exception('Method not implemented.');
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

    abstract protected function prepareHeader();
    abstract protected function prepareData();
}