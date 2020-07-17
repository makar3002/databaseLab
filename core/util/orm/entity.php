<?php
namespace core\util\orm;

class Entity
{
    protected $tableMap;
    protected $aliasMap;
    protected $fieldConfigMap;
    protected $primaryFieldName;
    private $checkedFieldConfigList;

    public function __construct($tableName, $tableMap)
    {
        $this->tableName = $tableName;
        $this->tableMap = $tableMap;
        $this->initialize();
    }

    public function initialize()
    {
        $this->primaryFieldName = $this->getPrimaryFieldName();
        $this->aliasMap = $this->getAliasMap();
        $this->fieldConfigMap = $this->getTableConfigMap();
    }

    protected function getTableConfigMap()
    {
        return array(
            'IS_REQUIRED' => $this->getRequiredFieldNameList(),
            'CAN_ADD' => $this->getCanAddFieldNameList(),
            'CAN_SELECT' => $this->getCanSelectFieldNameList(),
            'CAN_FILTER' => $this->getCanFilterFieldNameList(),
            'CAN_SORT' => $this->getCanSortFieldNameList(),
            'CAN_UPDATE' => $this->getCanUpdateFieldNameList(),
            'HAS_ARRAY_VALUE' => $this->getHasArrayValueFieldNameList()
        );
    }

    protected function getRequiredFieldNameList()
    {
        $requiredFieldNameList = array();
        foreach ($this->tableMap as $fieldName => $fieldConfig) {
            if (FieldAttributeType::isRequiredField($fieldConfig['ATTRIBUTES'])) {
                $requiredFieldNameList[$fieldName] = true;
                continue;
            }

            $requiredFieldNameList[$fieldName] = false;
        }

        return $requiredFieldNameList;
    }

    protected function getCanSelectFieldNameList()
    {
        $canSelectFieldNameList = array();
        foreach ($this->aliasMap as $fieldName => $fieldAlias) {
            if (
                !isset($this->tableMap[$fieldName])
                || FieldAttributeType::canSelectField($this->tableMap[$fieldName]['ATTRIBUTES'])
            ) {
                $canSelectFieldNameList[$fieldName] = true;
                continue;
            }
            $canSelectFieldNameList[$fieldName] = false;
        }
        return $canSelectFieldNameList;
    }

    protected function getCanFilterFieldNameList()
    {
        $canSelectFieldNameList = array();
        foreach ($this->aliasMap as $fieldName => $fieldAlias) {
            if (
                !isset($this->tableMap[$fieldName])
                || FieldAttributeType::canFilterField($this->tableMap[$fieldName]['ATTRIBUTES'])
            ) {
                $canSelectFieldNameList[$fieldName] = true;
                continue;
            }
            $canSelectFieldNameList[$fieldName] = false;
        }
        return $canSelectFieldNameList;
    }

    protected function getCanSortFieldNameList()
    {
        $canSortFieldNameList = array();
        foreach ($this->aliasMap as $fieldName => $fieldAlias) {
            if (
                !isset($this->tableMap[$fieldName])
                || FieldAttributeType::canSortField($this->tableMap[$fieldName]['ATTRIBUTES'])
            ) {
                $canSortFieldNameList[$fieldName] = true;
                continue;
            }
            $canSortFieldNameList[$fieldName] = false;
        }
        return $canSortFieldNameList;
    }

    protected function getCanAddFieldNameList()
    {
        $canAddFieldNameList = array();
        foreach ($this->tableMap as $fieldName => $fieldConfig) {
            if (FieldAttributeType::canAddField($fieldConfig['ATTRIBUTES'])) {
                $canAddFieldNameList[$fieldName] = true;
                continue;
            }
            $canAddFieldNameList[$fieldName] = false;
        }
        return $canAddFieldNameList;
    }

    protected function getCanUpdateFieldNameList()
    {
        $canUpdateFieldNameList = array();
        foreach ($this->tableMap as $fieldName => $fieldConfig) {
            if (FieldAttributeType::canUpdateField($fieldConfig['ATTRIBUTES'])) {
                $canUpdateFieldNameList[$fieldName] = true;
                continue;
            }
            $canUpdateFieldNameList[$fieldName] = false;
        }
        return $canUpdateFieldNameList;
    }

    protected function getHasArrayValueFieldNameList()
    {
        $hasArrayValueFieldNameList = array();
        foreach ($this->tableMap as $fieldName => $fieldConfig) {
            if (FieldAttributeType::hasArrayValueField($fieldConfig['ATTRIBUTES'])) {
                $hasArrayValueFieldNameList[$fieldName] = true;
                continue;
            }
            $hasArrayValueFieldNameList[$fieldName] = false;
        }
        return $hasArrayValueFieldNameList;
    }

    public function add($fields)
    {
        $query = 'INSERT INTO ' . $this->tableName;

        $fieldNameList = array();
        $fieldValueList = array();

        foreach ($this->fieldConfigMap['IS_REQUIRED'] as $fieldName => $isRequired) {
            if (
                isset($fields[$fieldName])
                && $isRequired
            ) {
                throw new \RuntimeException('Поле ' . $fieldName . ' является обязательным в таблице ' . $this->tableName . '.');
            }
        }

        foreach ($fields as $fieldName => $fieldValue) {
            if (
                !isset($this->fieldConfigMap['CAN_ADD'][$fieldName])
                || !$this->fieldConfigMap['CAN_ADD'][$fieldName]
            ) {
                throw new \RuntimeException('В таблице ' . $this->tableName . ' нельзя определять поле ' . $fieldName . '.');
            }

            $fieldNameList[] = $fieldName;
            $fieldValueList[] = $fields[$fieldName];
        }

        $query .= '(' . implode(', ', $fieldNameList) . ') VALUES (\'' .
            implode('\', \'', $fieldValueList) . '\');';

        $db = DB::getInstance();
        $db->prepare($query);
        $db->execute();

        // Обработка кастомных ошибок MySQL, вызываемых в триггерах.
        $errorInfo = $db->getError();
        if ($errorInfo[0] != '00000' && !empty($errorInfo[2])) {
            throw new \RuntimeException($errorInfo[2]);
        }

        return $db->getLastInsertId();
    }

    public function getList($params)
    {
        $queryList = array('SELECT');
        if (
            !isset($params['select'])
            || !is_array($params['select'])
            || array_search('*', $params['select'])
        ) {
            $params['select'] = array_keys($this->aliasMap);
        }

        $primaryFieldName = $this->primaryFieldName;
        $primaryFieldNameSelectQuery = $this->aliasMap[$primaryFieldName] . ' AS ' . $primaryFieldName;

        $selectQueryList = array();
        foreach ($params['select'] as $fieldName) {
            if (
                !isset($this->fieldConfigMap['CAN_SELECT'][$fieldName])
                || !$this->fieldConfigMap['CAN_SELECT'][$fieldName]
            ) {
                if (
                    isset($this->fieldConfigMap['HAS_ARRAY_VALUE'][$fieldName])
                    && $this->fieldConfigMap['HAS_ARRAY_VALUE'][$fieldName]
                ) {
                    continue;
                }
                throw new \RuntimeException('В таблице ' . $this->tableName . ' нельзя выбирать поле ' . $fieldName . '.');
            }

            $selectQueryList[] = $this->aliasMap[$fieldName] . ' AS ' . $fieldName;
        }

        if (
            !empty($this->fieldConfigMap['HAS_ARRAY_VALUE'])
            && is_bool(array_search($primaryFieldNameSelectQuery, $selectQueryList))
        ) {
            $selectQueryList[] = $primaryFieldNameSelectQuery;
        }

        $queryList[] = implode(', ', $selectQueryList);
        $queryList[] = 'FROM `' . $this->tableName . '`';

        $joinQuery = $this->getJoinQuery();
        if (!empty($joinQuery)) {
            $queryList[] = $joinQuery;
        }

        if (isset($params['filter']) && is_array($params['filter'])) {
            $filterQueryList = array();
            foreach ($params['filter'] as $filterKey => $value) {
                if (isset($this->aliasMap[$filterKey])) {
                    $fieldName = $filterKey;
                } elseif ($this->aliasMap[substr($filterKey, 1)]) {
                    $fieldName = substr($filterKey, 1);
                } else {
                    throw new \RuntimeException('Некорректный ключ фильтрации для таблицы ' . $this->tableName . ': ' . $filterKey . '.');
                }

                if (
                    !isset($this->fieldConfigMap['CAN_FILTER'][$fieldName])
                    || !$this->fieldConfigMap['CAN_FILTER'][$fieldName]
                ) {
                    throw new \RuntimeException('Таблицу ' . $this->tableName . ' нельзя фильтровать по полю ' . $fieldName . '.');
                }

                if ($filterKey[0] == '@' && is_array($value)) {
                    $filterQueryList[] = $this->aliasMap[$fieldName] . ' IN (\'' . implode('\', \'', $value) . '\')';
                } else {
                    $filterQueryList[] = $this->aliasMap[$fieldName] . ' = \'' . $value . '\'';
                }
            }

            if (!empty($filterQueryList)) {
                $queryList[] = 'WHERE ' . implode(' AND ', $filterQueryList);
            }
        }

        if (isset($params['search']) && is_string($params['search'])) {
            $searchValue = $params['search'];
            $searchQueryList = array();
            foreach ($this->aliasMap as $fieldName => $fieldAlias) {
                if (
                    !isset($this->fieldConfigMap['CAN_FILTER'][$fieldName])
                    || !$this->fieldConfigMap['CAN_FILTER'][$fieldName]
                ) {
                    continue;
                }
                $searchQueryList[] = $fieldAlias . ' LIKE \'%' . $searchValue . '%\'';
            }

            if (!empty($searchQueryList)) {
                $queryList[] = 'WHERE ' . implode(' OR ', $searchQueryList);
            }
        }

        if (isset($params['order']) && !empty($params['order'])) {
            $orderQueryList = array();
            foreach ($params['order'] as $fieldName => $orderValue) {
                if (
                    !isset($this->fieldConfigMap['CAN_SORT'][$fieldName])
                    || !$this->fieldConfigMap['CAN_SORT'][$fieldName]
                ) {
                    throw new \RuntimeException('Таблицу ' . $this->tableName . ' нельзя сортировать по полю ' . $fieldName . '.');
                }

                if (!in_array($orderValue, array('ASC', 'DESC'))) {
                    throw new \RuntimeException('Некорректное значение сортировки: ' . $orderValue . '.');
                }
                $orderQueryList[] = $this->aliasMap[$fieldName] . ' ' . $orderValue;
            }

            if (!empty($orderQueryList)) {
                $queryList[] = 'ORDER BY ' . implode(', ', $orderQueryList);
            }
        }

        if (isset($params['limit'])) {
            $limitValue = intval($params['limit']);

            if ($limitValue > 0) {
                $queryList[] = 'LIMIT ' . $limitValue;
            }
        }
        $query = implode(' ', $queryList);

        $db = DB::getInstance();
        $db->prepare($query);
        $db->execute();
        $resultList = $db->fetchAll();
        if (empty($arrayFieldNameList)) {
            return $resultList;
        }

        return $resultList;
    }

    public function getByPrimary($fieldValue)
    {

        $element = $this->getList(array(
            'filter' => array($this->primaryFieldName => $fieldValue),
        ));
        if (!empty($element)) {
            return $element[0];
        }
        return null;
    }

    public function update($primaryFieldValue, $fields)
    {
        if (empty($fields)) {
            return false;
        }

        $queryList = array('UPDATE ' . $this->tableName);

        $updateQueryList = array();
        foreach ($fields as $fieldName => $fieldValue) {
            if (
                !isset($this->fieldConfigMap['CAN_UPDATE'][$fieldName])
                || !$this->fieldConfigMap['CAN_UPDATE'][$fieldName]
            ) {
                throw new \RuntimeException('В таблице ' . $this->tableName . ' нельзя обновлять поле ' . $fieldName . '.');
            }

            $updateQueryList[] = $fieldName . ' = \'' . $fieldValue . '\'';
        }

        if (empty($updateQueryList)) {
            return false;
        }

        $queryList[] = 'SET ' . implode(', ', $updateQueryList);
        $queryList[] = 'WHERE ' . $this->primaryFieldName . ' = \'' . $primaryFieldValue . '\'';

        $query = implode(' ', $queryList);
        $db = DB::getInstance();
        $db->prepare($query);
        $db->execute();

        // Обработка кастомных ошибок MySQL, вызываемых в триггерах.
        $errorInfo = $db->getError();
        if ($errorInfo[0] != '00000' && !empty($errorInfo[2])) {
            throw new \RuntimeException($errorInfo[2]);
        }
        return $this->getByPrimary($primaryFieldValue);
    }

    public function deleteByPrimary($primaryFieldValue)
    {
        $query = 'DELETE FROM ' . $this->tableName . ' WHERE ' . $this->primaryFieldName . ' = ' . $primaryFieldValue . ';';
        $db = DB::getInstance();
        $db->prepare($query);
        return $db->execute();
    }

    private function checkFieldReference($fieldName, $fieldConfig)
    {
        if (empty($fieldConfig['REFERENCE'])) {
            throw new ReferenceException('У поля не задана информация о связи.');
        }

        $fieldReferenceInfo = $fieldConfig['REFERENCE'];
        if (empty($fieldReferenceInfo['TABLE_CLASS'])) {
            throw new ReferenceException('У связанного с другой таблицей поля должен быть задан ключ TABLE_CLASS.');
        }

        if (!empty($checkErrorMessage = $this->getFieldConfigCheckInfo($fieldName))) {
            throw new ReferenceException($checkErrorMessage);
        }

        try {
            if (
                isset($this->fieldConfigMap['HAS_ARRAY_VALUE'][$fieldName])
                && $this->fieldConfigMap['HAS_ARRAY_VALUE'][$fieldName]
                && empty($fieldReferenceInfo['FIELD_NAME'])
            ) {
                throw new ReferenceException('У связанного с другой таблицей поля должен быть задан ключ FIELD_NAME.');
            }

            if (!is_subclass_of($fieldReferenceInfo['TABLE_CLASS'], TableManager::class)) {
                throw new ReferenceException('Класс TABLE_CLASS должен быть наследником от TableManager.');
            }
        } catch (ReferenceException $exception) {
            $this->setFieldConfigCheckInfo($fieldName, $exception->getMessage());
            throw $exception;
        }

        $this->setFieldConfigCheckInfo($fieldName, '');
    }

    private function setFieldConfigCheckInfo($fieldName, $value)
    {
        $this->checkedFieldConfigList[$fieldName] = $value;
    }

    private function getFieldConfigCheckInfo($fieldName)
    {
        $isGoodFieldConfig = isset($this->checkedFieldConfigList[$fieldName]);
        if ($isGoodFieldConfig) {
            return $this->checkedFieldConfigList[$fieldName];
        }
        return '';
    }

    protected function getJoinQuery()
    {
        $joinQueryList = array();
        foreach ($this->tableMap as $fieldName => $fieldConfig) {
            try {
                $this->checkFieldReference($fieldName, $fieldConfig);
            } catch (\Throwable $exception) {
                continue;
            }

            if (
                isset($this->fieldConfigMap['HAS_ARRAY_VALUE'][$fieldName])
                && $this->fieldConfigMap['HAS_ARRAY_VALUE'][$fieldName]
            ) {
                continue;
            }

            $fieldReferenceInfo = $fieldConfig['REFERENCE'];
            $referenceClass = $fieldReferenceInfo['TABLE_CLASS'];
            if (isset($joinQueryList[$referenceClass])) {
                continue;
            }
            $referencePrimaryFieldName = $referenceClass::getPrimaryFieldName();
            $referenceTableName = $referenceClass::getTableName();
            $joinQueryList[$referenceClass] = 'INNER JOIN ' . $referenceTableName . ' ON ' . $referenceTableName . '.' . $referencePrimaryFieldName . ' = ' . $this->tableName . '.' . $fieldName;
        }
        return implode(' ', $joinQueryList);
    }

    public function getPrimaryFieldName()
    {
        if (isset($this->primaryFieldName)) {
            return $this->primaryFieldName;
        }

        foreach ($this->tableMap as $fieldName => $fieldConfig) {
            if (FieldAttributeType::isPrimaryField($fieldConfig['ATTRIBUTES'])) {
                $this->primaryFieldName = $fieldName;
                return $fieldName;
            }
        }
        throw new \RuntimeException('У таблицы не задан первичный ключ.');
    }

    protected function getAliasMap()
    {
        $aliasMap = array();
        foreach ($this->tableMap as $fieldName => $fieldConfig) {
            if (FieldAttributeType::hasArrayValueField($fieldConfig['ATTRIBUTES'])) {
                continue;
            }
            $aliasMap[$fieldName] = $this->tableName . '.' . $fieldName;

            try {
                self::checkFieldReference($fieldName, $fieldConfig);
            } catch (ReferenceException $exception) {
                continue;
            }

            $fieldReferenceInfo = $fieldConfig['REFERENCE'];
            $referenceSelectNameMap = $fieldReferenceInfo['SELECT_NAME_MAP'];
            $tableClass = $fieldReferenceInfo['TABLE_CLASS'];
            foreach ($referenceSelectNameMap as $aliasFieldName => $referenceFieldInfo) {
                if (is_array($referenceFieldInfo)) {
                    $aliasMap = array_merge($aliasMap, $tableClass::getAliasMap());
                } elseif (is_string($referenceFieldInfo)) {
                    $aliasMap[$aliasFieldName] = $tableClass::getTableName() . '.' . $referenceFieldInfo;
                } else {
                    throw new \RuntimeException();
                }
            }
        }

        return $aliasMap;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    protected function getTableMap()
    {
        return $this->tableMap;
    }
}


