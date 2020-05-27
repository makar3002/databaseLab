<?php
namespace core\orm\general;

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
        $sdh = $connection->prepare('SELECT LAST_INSERT_ID();');
        $sdh->execute();
        return $sdh->fetch()['LAST_INSERT_ID()'];
    }

    public static function getList($arFields)
    {
        $query = 'SELECT ';
        $tableMap = static::getTableMap();
        if (isset($arFields['select']) && !empty($arFields['select'])) {
            foreach ($arFields['select'] as $key => $columnId) {
                if (!array_key_exists($columnId, $tableMap) || !FieldAttributeType::canSelectField($tableMap[$columnId])) {
                    continue;
                }
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
                    $arFilter[] = substr($key, 1) . ' IN (\'' . implode('\', \'', $value) . '\')';
                } else {
                    $arFilter[] = substr($key, $key[0] == '=' ? 1 : 0) . ' = \'' . $value . '\'';
                }
            }

            if (!empty($arFilter)) {
                $query .= 'WHERE ' . implode(' AND ', $arFilter) . ' ';
            }
        }

        if (isset($arFields['order']) && !empty($arFields['order'])) {
            $arOrder = array();

            foreach ($arFields['order'] as $key => $value) {
                if (!in_array($key, $tableMap) && !in_array($value, array('ASC', 'DESC'))) {
                    continue;
                }

                $arOrder[] = $key . ' ' . $value;
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
        return static::getList(array(
           'filter' => array('ID' => $id),
           'order' => array('ID' => 'ASC')
        ))[0];
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

        if ($sdh->execute()) {
            return static::getById($id);
        };

        return false;
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
        $sdh->execute();
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


