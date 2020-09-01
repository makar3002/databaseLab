<?php
namespace core\lib\presentation;

interface TableInteractorCompatible {
    public function addElement($fieldValueList, $multipleFieldValueList = array()): void;
    public function getElementInfo($primary): array;
    public function getElementList($sort, $search): array;
    public function updateElement($primary, $fieldValueList): void;
    public function deleteElement($primary): bool;
}
