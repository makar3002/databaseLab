<?php
namespace Core\Orm\General;

class TableManager
{
    protected static $tableMap = array();

    public static function add($fields)
    {
        $tableName = static::getTableName();
        $tableMap = static::getTableMap();

        $query = 'INSERT INTO ' . $tableName . ' ';

        $fieldNameList = array();
        $fieldValueList = array();

        foreach ($tableMap as $fieldName => $fieldConfig) {
            if (
                !isset($fields[$fieldName])
                && FieldAttributeType::isRequiredField($fieldConfig['ATTRIBUTES'])
            ) {
                throw new \RuntimeException('Поле ' . $fieldName . ' является обязательным в таблице ' . $tableName . '.');
            }
        }

        foreach ($fields as $fieldName => $fieldValue) {
            if (!array_key_exists($fieldName, $tableMap)) {
                throw new \RuntimeException('В таблице ' . $tableName . ' нет поля ' . $fieldName . '.');
            }

            if (!FieldAttributeType::canAddField($tableMap[$fieldName]['ATTRIBUTES'])) {
                throw new \RuntimeException('В таблице ' . $tableName . ' нельзя обновлять поле ' . $fieldName . '.');
            }

            $fieldNameList[] = $fieldName;
            $fieldValueList[] = $fields[$fieldName];
        }

        $query .= '(' . implode(', ', $fieldNameList) . ') VALUES (\'' .
            implode('\', \'', $fieldValueList) . '\');';

        $db = DB::getInstance();
        $db->prepare($query);
        $db->execute();
        $errorInfo = $db->getError();
        if ($errorInfo[0] != '00000' && !empty($errorInfo[2])) {
            throw new \RuntimeException($errorInfo[2]);
        }
        $db->prepare('SELECT LAST_INSERT_ID();');
        $db->execute();
        return $db->fetch()['LAST_INSERT_ID()'];
    }

    public static function getList($params)
    {
        $query = 'SELECT ';

        $tableMap = static::getTableMap();
        $aliasMap = static::getFieldAliasMap($tableMap);
        $tableName = static::getTableName();

        if (
            !isset($params['select'])
            || !is_array($params['select'])
            || array_search('*', $params['select'])
        ) {
            $params['select'] = array_keys($aliasMap);
        }

        $selectQueryList = array();
        foreach ($params['select'] as $key => $fieldName) {
            if (
                !array_key_exists($fieldName, $aliasMap)
            ) {
                throw new \RuntimeException('В таблице ' . $tableName . ' невозможно выбрать поле ' . $fieldName . '.');
            }

            if (
                array_key_exists($fieldName, $tableMap)
                && !FieldAttributeType::canSelectField($tableMap[$fieldName]['ATTRIBUTES'])
            ) {
                if (FieldAttributeType::hasArrayValueField($tableMap[$fieldName]['ATTRIBUTES'])) {
                    continue;
                }
                throw new \RuntimeException('В таблице ' . $tableName . ' нельзя выбирать поле ' . $fieldName . '.');
            }

            $selectQueryList[] = $aliasMap[$fieldName] . ' AS ' . $fieldName;
        }

        $query .= implode(', ', $selectQueryList) . ' ';
        $query .= 'FROM `' . $tableName . '` ';

        $joinQuery = static::getJoinQuery($tableMap);
        if (!empty($joinQuery)) {
            $query .= $joinQuery . ' ';
        }

        if (isset($params['filter']) && !empty($params['filter'])) {
            $filterQueryList = array();
            foreach ($params['filter'] as $filterKey => $value) {
                if (array_key_exists($filterKey, $aliasMap)) {
                    $fieldName = $filterKey;
                } else if (array_key_exists(substr($filterKey, 1), $aliasMap)) {
                    $fieldName = substr($filterKey, 1);
                } else {
                    throw new \RuntimeException('Некорректный ключ фильтрации для таблицы ' . $tableName . ': ' . $filterKey . '.');
                }

                if (!FieldAttributeType::canFilterField($tableMap[$fieldName]['ATTRIBUTES'])) {
                    throw new \RuntimeException('Таблицу ' . $tableName . ' нельзя фильтровать по полю ' . $fieldName . '.');
                }

                if ($filterKey[0] == '@' && is_array($value)) {
                    $filterQueryList[] = $aliasMap[$fieldName] . ' IN (\'' . implode('\', \'', $value) . '\')';
                } else {
                    $filterQueryList[] = $aliasMap[$fieldName] . ' = \'' . $value . '\'';
                }
            }

            if (!empty($filterQueryList)) {
                $query .= 'WHERE ' . implode(' AND ', $filterQueryList) . ' ';
            }
        }

        if (isset($params['search']) && !empty($params['search'])) {
            $searchValue = $params['search'];
            $searchQueryList = array();

            foreach ($aliasMap as $fieldAlias => $fieldName) {
                if (
                    array_key_exists($fieldAlias, $tableMap)
                    && !FieldAttributeType::canFilterField($tableMap[$fieldAlias]['ATTRIBUTES'])
                ) {
                    continue;
                }
                $searchQueryList[] = $fieldName . ' LIKE \'%' . $searchValue . '%\'';
            }

            if (!empty($searchQueryList)) {
                $query .= 'WHERE ' . implode(' OR ', $searchQueryList) . ' ';
            }
        }

        if (isset($params['order']) && !empty($params['order'])) {
            $orderQueryList = array();
            foreach ($params['order'] as $fieldName => $orderValue) {
                if (
                    !array_key_exists($fieldName, $aliasMap)
                    || array_key_exists($fieldName, $tableMap)
                    && !FieldAttributeType::canSortField($tableMap[$fieldName]['ATTRIBUTES'])
                ) {
                    throw new \RuntimeException('Таблицу ' . $tableName . ' нельзя сортировать по полю ' . $fieldName . '.');
                }

                if (
                    !in_array($orderValue, array('ASC', 'DESC'))
                ) {
                    throw new \RuntimeException('Некорректное значение сортировки: ' . $orderValue . '.');
                }
                $orderQueryList[] = $aliasMap[$fieldName] . ' ' . $orderValue;
            }

            if (!empty($orderQueryList)) {
                $query .= 'ORDER BY ' . implode(', ', $orderQueryList) . ' ';
            }
        }

        if (isset($params['limit'])) {
            $limitValue = intval($params['limit']);

            if ($limitValue > 0) {
                $query .= 'LIMIT ' . $limitValue;
            }
        }

        $db = DB::getInstance();
        $db->prepare($query);
        $db->execute();
        return $db->fetchAll();
    }

    public static function getById($id)
    {
        $element = static::getList(array(
            'filter' => array('ID' => $id),
            'order' => array('ID' => 'ASC')
        ));
        if (!empty($element)) {
            return $element[0];
        }
        return null;
    }

    public static function update($id, $fields)
    {
        if (empty($fields)) {
            return false;
        }

        $element = static::getById($id);
        if (empty($element)) {
            return false;
        }

        $tableName = static::getTableName();
        $tableMap = static::getTableMap();

        $query = 'UPDATE ' . $tableName . ' ';

        $updateQueryList = array();
        foreach ($fields as $fieldName => $fieldValue) {
            if (!array_key_exists($fieldName, $tableMap)) {
                throw new \RuntimeException('В таблице ' . $tableName . ' нет поле ' . $fieldName . '.');
            }

            if (!FieldAttributeType::canUpdateField($tableMap[$fieldName]['ATTRIBUTES'])) {
                throw new \RuntimeException('В таблице ' . $tableName . ' нельзя обновлять поле ' . $fieldName . '.');
            }

            $updateQueryList[] = $fieldName . ' = \'' . $fieldValue . '\'';
        }

        if (empty($updateQueryList)) {
            return false;
        }

        $query .= 'SET ' . implode(', ', $updateQueryList) . ' WHERE id = \'' . $id . '\'';

        $db = DB::getInstance();
        $db->prepare($query);
        $db->execute();
        $errorInfo = $db->getError();
        if ($errorInfo[0] != '00000' && !empty($errorInfo[2])) {
            throw new \RuntimeException($errorInfo[2]);
        }
        return static::getById($id);
    }

    public static function delete($id)
    {
        $element = static::getById($id);
        if (empty($element)) {
            return false;
        }

        $query = 'DELETE FROM ' . static::getTableName() . ' WHERE ID = ' . $id . ';';

        $db = DB::getInstance();
        $db->prepare($query);
        return $db->execute();
    }

    protected static function getJoinQuery($tableMap)
    {
        $joinQueryList = array();
        foreach ($tableMap as $fieldName => $fieldConfig) {
            if (empty($fieldConfig['REFERENCE'])) {
                continue;
            }

            $fieldReferenceInfo = $fieldConfig['REFERENCE'];
            if (
                empty($fieldReferenceInfo['TABLE_CLASS'])
                || !is_subclass_of($fieldReferenceInfo['TABLE_CLASS'], TableManager::class)
            ) {
                throw new \RuntimeException('У связанного с другой таблицей поля должны быть заданы ключи TABLE_CLASS и FIELD_NAME.');
            }

            if (!is_subclass_of($fieldReferenceInfo['TABLE_CLASS'], TableManager::class)) {
                throw new \RuntimeException('Класс TABLE_CLASS должен быть наследником от TableManager.');
            }

            $currentTableName = static::getTableName();
            $referenceClass = $fieldReferenceInfo['TABLE_CLASS'];
            if (isset($joinQueryList[$referenceClass])) {
                continue;
            }
            $referencePrimaryFieldName = $referenceClass::getPrimaryFieldName($tableMap);
            $referenceTableName = $referenceClass::getTableName();
            $joinQueryList[$referenceClass] = 'INNER JOIN ' . $referenceTableName . ' ON ' . $referenceTableName . '.' . $referencePrimaryFieldName . ' = ' . $currentTableName . '.' . $fieldName;
        }
        $joinQuery = implode(' ', $joinQueryList);
        return $joinQuery;
    }

    protected static function getPrimaryFieldName($tableMap)
    {
        foreach ($tableMap as $fieldName => $fieldConfig) {
            if (FieldAttributeType::isPrimaryField($fieldConfig['ATTRIBUTES'])) {
                return $fieldName;
            }
        }

        throw new \RuntimeException('У таблицы не задан первичный ключ.');
    }

    protected static function getFieldAliasMap($tableMap)
    {
        $tableName = static::getTableName();
        $fieldAliasMap = array();
        foreach ($tableMap as $fieldName => $fieldConfig) {
            $fieldAliasMap[$fieldName] = $tableName . '.' . $fieldName;

            if (!isset($fieldConfig['REFERENCE'])) {
                continue;
            }

            $fieldReferenceInfo = $fieldConfig['REFERENCE'];
            if (
                !is_array($fieldReferenceInfo['SELECT_NAME_MAP'])
                || !is_subclass_of($fieldReferenceInfo['TABLE_CLASS'], TableManager::class)
            ) {
                continue;
            }

            $referenceSelectNameMap = $fieldReferenceInfo['SELECT_NAME_MAP'];
            $tableClass = $fieldReferenceInfo['TABLE_CLASS'];
            foreach ($referenceSelectNameMap as $aliasFieldName => $referenceFieldInfo) {
                if (is_array($referenceFieldInfo)) {
                    $fieldAliasMap = array_merge($fieldAliasMap, static::getFieldAliasMap($referenceFieldInfo));
                } else if (is_string($referenceFieldInfo)) {
                    $fieldAliasMap[$aliasFieldName] = $tableClass::getTableName() . '.' . $referenceFieldInfo;
                }
            }
        }

        return $fieldAliasMap;
    }

    public static function getTableName()
    {
        return '';
    }

    protected static function getTableMap()
    {
        return static::$tableMap;
    }
}


