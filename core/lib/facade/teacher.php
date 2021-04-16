<?php
namespace core\lib\facade;


use core\lib\orm\Entity;
use core\lib\table\TeacherTable;


class Teacher {
    private Entity $teacherEntity;

    public function __construct() {
        $this->teacherEntity = TeacherTable::getEntity();
    }

    public function add(array $fieldValueList): void {
        $this->teacherEntity->add($fieldValueList);
    }

    public function find(array $params) {
        return $this->teacherEntity->getList($params);
    }

    public function save(int $id, array $fieldValueList): void {
        $this->teacherEntity->update($id, $fieldValueList);
    }

    public function delete(int $id): bool {
        return $this->teacherEntity->deleteByPrimary($id);
    }
}