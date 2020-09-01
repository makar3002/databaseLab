<?php
namespace core\lib\presentation;


use core\lib\facade\Institute;


class InstituteListInteractor implements TableInteractorCompatible {
    private Institute $instituteFacadeInstance;

    public function __construct() {
        $this->instituteFacadeInstance = new Institute();
    }

    public function addElement($fieldValueList, $multipleFieldValueList = array()): void {
        $this->instituteFacadeInstance->add($fieldValueList);
    }

    public function getElementInfo($primary): array {
        $instituteList = $this->instituteFacadeInstance->find(array(
            'filter' => array('ID' => $primary)
        ));
        return empty($instituteList) ? array() : $instituteList[0];
    }

    public function updateElement($primary, $fieldValueList): void {
        $this->instituteFacadeInstance->save($primary, $fieldValueList);
    }

    public function deleteElement($primary): bool {
        return $this->instituteFacadeInstance->delete($primary);
    }

    public function getElementList($sort, $search): array {
        $result = $this->instituteFacadeInstance->find(array(
            'order' => $sort,
            'search' => $search
        ));
        return $result;
    }
}
