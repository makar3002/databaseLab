<?php
namespace core\lib\facade;


use core\lib\orm\Entity;
use core\lib\table\DirectionTable;


class Direction {
    private Entity $directionEntity;

    public function __construct() {
        $this->directionEntity = DirectionTable::getEntity();
    }

    public function add(array $fieldValueList): void {
        $this->directionEntity->add($fieldValueList);
    }

    public function find(array $params) {
        return $this->directionEntity->getList($params);
    }

    public function save(int $id, array $fieldValueList): void {
        $this->directionEntity->update($id, $fieldValueList);
    }

    public function delete(int $id): bool {
        return $this->directionEntity->deleteByPrimary($id);
    }
}