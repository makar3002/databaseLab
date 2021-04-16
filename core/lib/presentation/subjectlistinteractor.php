<?php
namespace core\lib\presentation;


use core\lib\facade\Subject;


class SubjectListInteractor implements TableInteractorCompatible {
    private Subject $subjectFacadeInstance;

    public function __construct() {
        $this->subjectFacadeInstance = new Subject();
    }

    public function addElement($fieldValueList, $multipleFieldValueList = array()): void {
        $this->subjectFacadeInstance->add($fieldValueList);
    }

    public function getElementInfo($primary): array {
        $subjectList = $this->subjectFacadeInstance->find(array(
            'select' => array('ID', 'TEACHER_IDS', 'NAME', 'INSTITUTE_ID', 'INSTITUTE_NAME'),
            'filter' => array('ID' => $primary)
        ));
        return empty($subjectList) ? array() : $subjectList[0];
    }

    public function updateElement($primary, $fieldValueList): void {
        $this->subjectFacadeInstance->save($primary, $fieldValueList);
    }

    public function deleteElement($primary): bool {
        return $this->subjectFacadeInstance->delete($primary);
    }

    public function getElementList($sort, $search): array {
        $result = $this->subjectFacadeInstance->find(array(
            'select' => array('ID', 'TEACHER_IDS', 'NAME', 'INSTITUTE_ID', 'INSTITUTE_NAME'),
            'order' => $sort,
            'search' => $search
        ));

        return $result;
    }
}
