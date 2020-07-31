<?php
namespace core\lib\facade;


use core\lib\orm\Entity;
use core\lib\table\InstituteTable;


class Institute extends TableInteraction {
    protected function getEntity(): Entity {
        return InstituteTable::getEntity();
    }
}