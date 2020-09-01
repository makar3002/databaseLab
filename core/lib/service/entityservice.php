<?php
namespace core\lib\service;

use core\lib\orm\Entity;

class EntityService {
    private $entity;
    public function __construct(Entity $entity) {
        $this->entity = $entity;
    }

    public function getElementByPrimary($primary): array {
        return $this->entity->getByPrimary($primary);
    }

    public function getRows(array $params): array {
        return $this->entity->getList($params);
    }

    public function addElement($fields, $multipleFields): int {
        $elementId = $this->entity->add($fields);
        foreach ($multipleFields as $fieldName => $value) {
            $this->entity->updateBindEntity($elementId, $fieldName, $value);
        }
        return $elementId;
    }

    public function deleteElement($primary): bool {
        return $this->entity->deleteByPrimary($primary);
    }

    public function updateElement($primary, $fields): bool {
        foreach ($fields as $fieldName => $value) {
            if (!is_array($value)) {
                continue;
            }

            $this->entity->updateBindEntity($primary, $fieldName, $value);
            unset($fields[$fieldName]);
        }
        $this->entity->update($primary, $fields);

        return true;
    }
}
