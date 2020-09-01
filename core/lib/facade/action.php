<?php
namespace core\lib\facade;


use core\lib\orm\Entity;
use core\lib\table\RightActionTable;


class Action extends TableInteraction {
    protected function getEntity(): Entity {
        return RightActionTable::getEntity();
    }
}