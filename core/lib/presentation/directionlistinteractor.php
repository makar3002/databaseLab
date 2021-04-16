<?php
namespace core\lib\presentation;


use core\lib\facade\Direction;


class DirectionListInteractor implements TableInteractorCompatible {
    private Direction $directionFacadeInstance;

    public function __construct() {
        $this->directionFacadeInstance = new Direction();
    }

    public function addElement($fieldValueList, $multipleFieldValueList = array()): void {
        $this->directionFacadeInstance->add($fieldValueList);
    }

    public function getElementInfo($primary): array {
        $directionList = $this->directionFacadeInstance->find(array(
            'filter' => array('ID' => $primary)
        ));
        return empty($directionList) ? array() : $directionList[0];
    }

    public function updateElement($primary, $fieldValueList): void {
        $this->directionFacadeInstance->save($primary, $fieldValueList);
    }

    public function deleteElement($primary): bool {
        return $this->directionFacadeInstance->delete($primary);
    }

    public function getElementList($sort, $search): array {
        $result = $this->directionFacadeInstance->find(array(
            'order' => $sort,
            'search' => $search
        ));
        return $result;
    }
}
