<?php
namespace core\lib\facade;


use core\lib\orm\Entity;
use core\lib\table\InstituteTable;


class Institute {
    private Entity $instituteEntity;

    public function __construct() {
        $this->instituteEntity = InstituteTable::getEntity();
    }

    public function add(array $fieldValueList): void {
        $this->instituteEntity->add($fieldValueList);
    }

    public function find(array $params) {
        return $this->instituteEntity->getList($params);
    }

    public function save(int $id, array $fieldValueList): void {
        $this->instituteEntity->update($id, $fieldValueList);
    }

    public function delete(int $id): bool {
        return $this->instituteEntity->deleteByPrimary($id);
    }
}