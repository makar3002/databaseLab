<?php
namespace core\component\tablelist;


use core\lib\ajax\AjaxControllable;


interface TableListAjaxController extends AjaxControllable {
    public function addElementAction(): void;
    public function getElementInfoAction(): array;
    public function getTableOnlyAction(): void;
    public function updateElementAction(): void;
    public function deleteElementAction(): void;
}
