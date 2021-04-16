<?php
namespace core\lib\presentation;


use core\lib\facade\ScheduleElement;


class ScheduleElementListInteractor implements TableInteractorCompatible {
    private ScheduleElement $scheduleElementFacadeInstance;

    public function __construct() {
        $this->scheduleElementFacadeInstance = new ScheduleElement();
    }

    public function addElement($fieldValueList, $multipleFieldValueList = array()): void {
        $this->scheduleElementFacadeInstance->add($fieldValueList);
    }

    public function getElementInfo($primary): array {
        $scheduleElementList = $this->scheduleElementFacadeInstance->find(array(
            'filter' => array('ID' => $primary)
        ));
        return empty($scheduleElementList) ? array() : $scheduleElementList[0];
    }

    public function updateElement($primary, $fieldValueList): void {
        $this->scheduleElementFacadeInstance->save($primary, $fieldValueList);
    }

    public function deleteElement($primary): bool {
        return $this->scheduleElementFacadeInstance->delete($primary);
    }

    public function getElementList($sort, $search): array {
        $result = $this->scheduleElementFacadeInstance->find(array(
            'order' => $sort,
            'search' => $search
        ));
        return $result;
    }
}
