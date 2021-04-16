<?php
namespace core\lib\presentation;


use core\lib\facade\Group;


class GroupListInteractor implements TableInteractorCompatible {
    private Group $groupFacadeInstance;

    public function __construct() {
        $this->groupFacadeInstance = new Group();
    }

    public function addElement($fieldValueList, $multipleFieldValueList = array()): void {
        $this->groupFacadeInstance->add($fieldValueList);
    }

    public function getElementInfo($primary): array {
        $groupList = $this->groupFacadeInstance->find(array(
            'filter' => array('ID' => $primary)
        ));
        return empty($groupList) ? array() : $groupList[0];
    }

    public function updateElement($primary, $fieldValueList): void {
        $this->groupFacadeInstance->save($primary, $fieldValueList);
    }

    public function deleteElement($primary): bool {
        return $this->groupFacadeInstance->delete($primary);
    }

    public function getElementList($sort, $search): array {
        $result = $this->groupFacadeInstance->find(array(
            'order' => $sort,
            'search' => $search
        ));
        return $result;
    }
}
