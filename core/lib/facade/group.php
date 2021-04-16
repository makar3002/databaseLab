<?php
namespace core\lib\facade;


use core\lib\orm\Entity;
use core\lib\table\GroupTable;


class Group {
    private Entity $groupEntity;

    public function __construct() {
        $this->groupEntity = GroupTable::getEntity();
    }

    public function add(array $fieldValueList): void {
        $this->groupEntity->add($fieldValueList);
    }

    public function find(array $params) {
        return $this->groupEntity->getList($params);
    }

    public function save(int $id, array $fieldValueList): void {
        $this->groupEntity->update($id, $fieldValueList);
    }

    public function delete(int $id): bool {
        return $this->groupEntity->deleteByPrimary($id);
    }
}