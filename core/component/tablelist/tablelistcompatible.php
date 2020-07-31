<?php
namespace core\component\tablelist;

interface TableListCompatible {
    public function getElementInfoAction(): array;
    public function getTableOnlyAction(): void;
    public function updateElementAction(): void;
    public function addElementAction(): void;
    public function deleteElementAction(): void;
}
