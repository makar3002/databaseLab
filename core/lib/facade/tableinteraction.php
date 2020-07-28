<?php
namespace core\lib\facade;

use core\lib\orm\Entity;
use core\lib\service\EntityService;

abstract class TableInteraction {
    private EntityService $entityServiceInstance;
    public function __construct() {
        $this->entityServiceInstance = new EntityService($this->getEntity());
    }

    public function addElement($fieldValueList, $multipleFieldValueList = array()): void {
        $this->entityServiceInstance->addElement($fieldValueList, $multipleFieldValueList);
    }

    public function getElementInfoByPrimary($primary): array {
        return $this->entityServiceInstance->getElementByPrimary($primary);
    }

    public function updateElement($primary, $fieldValueList): void {
        $this->entityServiceInstance->updateElement($primary, $fieldValueList);
    }

    public function deleteElement($primary): bool {
        return $this->entityServiceInstance->deleteElement($primary);
    }

    public function getElementList($sort, $search): array {
        return $this->entityServiceInstance->getRows(array(
            'order' => $sort,
            'search' => $search
        ));
    }

    abstract protected function getEntity(): Entity;
}
