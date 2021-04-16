<?php
namespace core\lib\presentation;


use core\lib\facade\Teacher;


class TeacherListInteractor implements TableInteractorCompatible {
    private Teacher $teacherFacadeInstance;

    public function __construct() {
        $this->teacherFacadeInstance = new Teacher();
    }

    public function addElement($fieldValueList, $multipleFieldValueList = array()): void {
        $this->teacherFacadeInstance->add($fieldValueList);
    }

    public function getElementInfo($primary): array {
        $teacherList = $this->teacherFacadeInstance->find(array(
            'filter' => array('ID' => $primary)
        ));
        return empty($teacherList) ? array() : $teacherList[0];
    }

    public function updateElement($primary, $fieldValueList): void {
        $this->teacherFacadeInstance->save($primary, $fieldValueList);
    }

    public function deleteElement($primary): bool {
        return $this->teacherFacadeInstance->delete($primary);
    }

    public function getElementList($sort, $search): array {
        $result = $this->teacherFacadeInstance->find(array(
            'order' => $sort,
            'search' => $search
        ));
        return $result;
    }
}
