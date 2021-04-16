<?php
namespace core\component\schedule;


use core\lib\ajax\AjaxControllable;


interface ScheduleAjaxControllable extends AjaxControllable {
    public function refreshTableAction();
}
