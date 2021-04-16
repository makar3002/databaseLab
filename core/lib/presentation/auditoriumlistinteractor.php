<?php
namespace core\lib\presentation;


use core\lib\facade\Auditorium;


class AuditoriumListInteractor implements TableInteractorCompatible {
    private Auditorium $auditoriumFacadeInstance;

    public function __construct() {
        $this->auditoriumFacadeInstance = new Auditorium();
    }

    public function addElement($fieldValueList, $multipleFieldValueList = array()): void {
        $this->auditoriumFacadeInstance->add($fieldValueList);
    }

    public function getElementInfo($primary): array {
        $auditoriumList = $this->auditoriumFacadeInstance->find(array(
            'filter' => array('ID' => $primary)
        ));
        return empty($auditoriumList) ? array() : $auditoriumList[0];
    }

    public function updateElement($primary, $fieldValueList): void {
        $this->auditoriumFacadeInstance->save($primary, $fieldValueList);
    }

    public function deleteElement($primary): bool {
        return $this->auditoriumFacadeInstance->delete($primary);
    }

    public function getElementList($sort, $search): array {
        $result = $this->auditoriumFacadeInstance->find(array(
            'order' => $sort,
            'search' => $search
        ));
        return $result;
    }
}
