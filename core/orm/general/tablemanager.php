<?php
namespace Core\Orm\General;

use PDO;

class TableManager
{
    public static function add($arFields)
    {
        $query = 'INSERT INTO ' . static::getTableName() . ' ';

        $arKeys = array();
        $arValues = array();

        $tableMap = static::getTableMap();

        foreach ($tableMap as $key => $value) {
            if (!isset($arFields[$key])) {
                if(FieldAttributeType::isRequiredField($value)) {
                    throw new \RuntimeException('Поле ' . $key . ' является обязательным в таблице ' . static::getTableName() . '.');
                } else {
                    continue;
                }
            }

            if (!FieldAttributeType::canAddField($value)) {
                throw new \RuntimeException('Поле ' . $key . ' в таблице ' . static::getTableName() . ' нельзя добавлять.');
            }

            $arKeys[] = $key;
            $arValues[] = $arFields[$key];
        }

        $query .= '(' . implode(', ', $arKeys) . ') VALUES (\'' .
            implode('\', \'', $arValues) . '\');';

        $connection = DB::getInstance();
        $sdh = $connection->prepare($query);
        $sdh->execute();
        $errorInfo = $sdh->errorInfo();
        if ($errorInfo[0] != '00000' && !empty($errorInfo[2])) {
            throw new \RuntimeException($errorInfo[2]);
        }
        $sdh = $connection->prepare('SELECT LAST_INSERT_ID();');
        $sdh->execute();
        return $sdh->fetch()['LAST_INSERT_ID()'];
    }

    public static function getList($arFields)
    {
        $query = 'SELECT ';
        $joinQuery = static::getJoinQuery();

        $namePrefix = '';
        if (!empty($joinQuery)) {
            $namePrefix = static::getTableName() . '.';
        }
        $tableMap = static::getTableMap();
        if (!isset($arFields['select'])) {
            $arFields['select'] = array_keys($tableMap);
        }

        if (isset($arFields['select']) && !empty($arFields['select'])) {
            foreach ($arFields['select'] as $key => &$columnId) {
                if (!array_key_exists($columnId, $tableMap) || !FieldAttributeType::canSelectField($tableMap[$columnId])) {
                    unset($arFields['select'][$key]);
                    continue;
                }

                $columnId = $namePrefix . $columnId;
            }

            $query .= implode(', ', $arFields['select']) . ' ';
        } else {
            $query .= '* ';
        }

        $query .= 'FROM `' . static::getTableName() . '` ';

        $joinQuery = static::getJoinQuery();
        if (!empty($joinQuery)) {
            $query .= $joinQuery . ' ';
        }

        if (isset($arFields['filter']) && !empty($arFields['filter'])) {
            $arFilter = array();

            foreach ($arFields['filter'] as $key => $value) {
                if (array_key_exists($key, $tableMap)) {
                    $fieldName = $key;
                } else if (array_key_exists(substr($key, 1), $tableMap)) {
                    $fieldName = substr($key, 1);
                } else {
                    continue;
                }

                if (!FieldAttributeType::canFilterField($tableMap[$fieldName])) {
                    throw new \RuntimeException('Таблицу ' . static::getTableName() . ' нельзя фильтровать по полю ' . $fieldName . '.');
                }

                if ($key[0] == '@' && is_array($value)) {
                    $arFilter[] = $namePrefix . substr($key, 1) . ' IN (\'' . implode('\', \'', $value) . '\')';
                } else {
                    $arFilter[] = $namePrefix . substr($key, $key[0] == '=' ? 1 : 0) . ' = \'' . $value . '\'';
                }
            }

            if (!empty($arFilter)) {
                $query .= 'WHERE ' . implode(' AND ', $arFilter) . ' ';
            }
        }

        if (isset($arFields['search']) && !empty($arFields['search'])) {
            $arSearch = array();

            foreach ($tableMap as $key => $value) {
                if (FieldAttributeType::isJoinTableField($tableMap[$key])) {
                    $arSearch[] = $key . ' LIKE \'%' . $arFields['search'] . '%\'';
                } else if (FieldAttributeType::canFilterField($tableMap[$key])) {
                    $arSearch[] = $namePrefix . $key . ' LIKE \'%' . $arFields['search'] . '%\'';
                }
            }

            if (!empty($arSearch)) {
                $query .= 'WHERE ' . implode(' OR ', $arSearch) . ' ';
            }
        }

        if (isset($arFields['order']) && !empty($arFields['order'])) {
            $arOrder = array();

            foreach ($arFields['order'] as $key => $value) {
                if (!in_array($key, $tableMap) && !in_array($value, array('ASC', 'DESC'))) {
                    continue;
                }

                if (FieldAttributeType::isJoinTableField($tableMap[$key])) {
                    $arOrder[] = $key . ' ' . $value;
                } else {
                    $arOrder[] = $namePrefix . $key . ' ' . $value;
                }
            }

            if (!empty($arOrder)) {
                $query .= 'ORDER BY ' . implode(', ', $arOrder) . ' ';
            }
        }

        if (isset($arFields['limit'])) {
            $value = intval($arFields['limit']);

            if ($value > 0) {
                $query .= 'LIMIT ' . $value;
            }
        }

        $connection = DB::getInstance();
        $sdh = $connection->prepare($query);
        $sdh->execute();
        return $sdh->fetchAll(PDO::FETCH_ASSOC);
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

    public static function update($id, $arFields)
    {
        if (empty($arFields)) {
            return false;
        }

        $element = static::getById($id);
        if (empty($element)) {
            return false;
        }

        $query = 'UPDATE ' . static::getTableName() . ' ';

        $arUpdate = array();
        $tableMap = static::getTableMap();

        foreach ($arFields as $key => $value) {
            if (!array_key_exists($key, $tableMap)) {
                continue;
            }

            if (!FieldAttributeType::canUpdateField($tableMap[$key])) {
                throw new \RuntimeException('В таблице ' . static::getTableName() . ' нельзя обновлять поле ' . $key . '.');
            }

            $arUpdate[] = $key . ' = \'' . $value . '\'';
        }

        if (empty($arUpdate)) {
            return false;
        }

        $query .= 'SET ' . implode(', ', $arUpdate) . ' WHERE id = \'' . $id . '\'';

        $connection = DB::getInstance();
        $sdh = $connection->prepare($query);
        $sdh->execute();
        $errorInfo = $sdh->errorInfo();
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

        $connection = DB::getInstance();
        $sdh = $connection->prepare($query);
        return $sdh->execute();
    }

    protected static function getJoinQuery()
    {
        return '';
    }

    public static function getTableName()
    {
        return '';
    }

    protected static function getTableMap()
    {
        return array();
    }
}


