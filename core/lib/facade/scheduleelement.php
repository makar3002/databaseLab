<?php
namespace core\lib\facade;


use core\lib\orm\Entity;
use core\lib\table\ScheduleElementTable;


class ScheduleElement {
    private Entity $scheduleElementEntity;

    public function __construct() {
        $this->scheduleElementEntity = ScheduleElementTable::getEntity();
    }

    public function add(array $fieldValueList): void {
        $this->scheduleElementEntity->add($fieldValueList);
    }

    public function find(array $params) {
        return $this->scheduleElementEntity->getList($params);
    }

    public function save(int $id, array $fieldValueList): void {
        $this->scheduleElementEntity->update($id, $fieldValueList);
    }

    public function delete(int $id): bool {
        return $this->scheduleElementEntity->deleteByPrimary($id);
    }
}