<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/php/general/database_connection.php');

class Table
{
    public static function add($arFields)
    {
        $query = 'INSERT INTO ' . self::getTableName() . ' ';

        $arKeys = array();
        $arValues = array();

        foreach ($arFields as $key => $value) {
            if ($key == 'ID') {
                unset($arFields[$key]);
                continue;
            } else if(!in_array($key, self::getTableMap()) || is_array($value)) {
                return false;
            } else {
                $arKeys[] = strtolower($key);
                $arValues[] = $value;
            }
        }

        $query .= '(' . implode('. ', $arKeys) . ') VALUES (\'' .
            implode('\', \'', $arValues) . '\'); ' .
            'SELECT SCOPE_IDENTITY()';

        //return $query;

        $connection = DB::getInstance();
        $sdh = $connection->prepare($query);
        $id = $sdh->execute();
        $sdh->fetchAll();
        return $id;
    }

    public static function getList($arFields)
    {
        $query = 'SELECT ';
        if (isset($arFields['select']) && !empty($arFields['select'])) {
            foreach ($arFields['select'] as $key => $columnId) {
                if (!in_array($columnId, self::getTableMap())) {
                    unset($arFields['select'][$key]);
                } else {
                    $arFields['select'][$key] = strtolower($arFields['select'][$key]);
                }
            }

            $query .= implode(', ', $arFields['select']) . ' ';
        } else {
            $query .= '* ';
        }

        $query .= 'FROM `' . self::getTableName() . '` ';

        if (isset($arFields['filter']) && !empty($arFields['filter'])) {
            $arFilter = array();

            foreach ($arFields['filter'] as $key => $value) {
                if (!in_array($key, self::getTableMap()) && !in_array(substr($key, 1), self::getTableMap())) {
                    continue;
                }

                if ($key[0] == '@' && is_array($value)) {
                    $arFilter[] = strtolower(substr($key, 1)) . ' IN (\'' . implode('\', \'', $value) . '\')';
                } else {
                    $arFilter[] = strtolower(substr($key, $key[0] == '=' ? 1 : 0)) . ' = \'' . $value . '\'';
                }
            }

            if (!empty($arFilter)) {
                $query .= 'WHERE ' . implode(' AND ', $arFilter) . ' ';
            }
        }

        if (isset($arFields['order']) && !empty($arFields['order'])) {
            $arOrder = array();

            foreach ($arFields['order'] as $key => $value) {
                if (!in_array($key, self::getTableMap()) && !in_array($value, array('ASC', 'DESC'))) {
                    continue;
                }

                $arOrder[] = strtolower($key) . ' ' . $value;
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

        //return $query;

        $connection = DB::getInstance();
        $sdh = $connection->prepare($query);
        $sdh->execute();
        return $sdh->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id)
    {
        return self::getList(array(
           'filter' => array('ID' => $id),
           'order' => array('ID' => 'ASC')
        ));
    }

    public static function update($id, $arFields)
    {
        if (empty($arFields)) {
            return false;
        }

        $element = self::getById($id);
        if (empty($element)) {
            return false;
        }

        $query = 'UPDATE ' . self::getTableName() . ' ';

        $arUpdate = array();

        foreach ($arFields as $key => $value) {
            if (!in_array($key, self::getTableMap())) {
                continue;
            }

            $arUpdate[] = strtolower($key) . ' = \'' . $value . '\'';
        }

        if (empty($arUpdate)) {
            return false;
        }

        $query .= 'SET ' . implode(', ', $arUpdate) . ' WHERE id = \'' . $id . '\'';

        $connection = DB::getInstance();
        $sdh = $connection->prepare($query);
        $sdh->execute();
        return true;
    }

    public static function delete($id)
    {
        $element = self::getById($id);
        if (empty($element)) {
            return false;
        }

        $query = 'DELETE FROM ' . self::getTableName() . ' WHERE id = \'' . $id . '\'';
    }

    protected static function getTableName()
    {
        return 'institute';
    }

    protected static function getTableMap()
    {
        return array('ID', 'VALUE');
    }
}


