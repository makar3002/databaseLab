<?php
namespace core\lib\facade;


use core\lib\orm\Entity;
use core\lib\table\AuditoriumTable;


class Auditorium {
    private Entity $auditoriumEntity;

    public function __construct() {
        $this->auditoriumEntity = AuditoriumTable::getEntity();
    }

    public function add(array $fieldValueList): void {
        $this->auditoriumEntity->add($fieldValueList);
    }

    public function find(array $params) {
        return $this->auditoriumEntity->getList($params);
    }

    public function save(int $id, array $fieldValueList): void {
        $this->auditoriumEntity->update($id, $fieldValueList);
    }

    public function delete(int $id): bool {
        return $this->auditoriumEntity->deleteByPrimary($id);
    }
}