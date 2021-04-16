<?php
namespace core\lib\facade;


use core\lib\orm\Entity;
use core\lib\table\SubjectTable;


class Subject {
    private Entity $subjectEntity;

    public function __construct() {
        $this->subjectEntity = SubjectTable::getEntity();
    }

    public function add(array $fieldValueList): void {
        $this->subjectEntity->add($fieldValueList);
    }

    public function find(array $params) {
        return SubjectTable::getList($params);
    }

    public function save(int $id, array $fieldValueList): void {
        $teacherIds = $fieldValueList['TEACHER_IDS'];
        unset($fieldValueList['TEACHER_IDS']);
        $this->subjectEntity->update($id, $fieldValueList);
        SubjectTable::updateBindEntity($id, 'TEACHER_IDS', $teacherIds);
    }

    public function delete(int $id): bool {
        SubjectTable::deleteBindEntity($id, 'TEACHER_IDS');
        return $this->subjectEntity->deleteByPrimary($id);
    }
}