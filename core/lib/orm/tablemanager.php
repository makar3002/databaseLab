<?php
namespace core\lib\orm;

abstract class TableManager
{
    private static $entityList = array();

    /**
     * @return Entity
     */
    public static function getEntity()
    {
        $entityClass = static::getEntityClass();
        if (isset(static::$entityList[$entityClass])) {
            return static::$entityList[$entityClass];
        }

        $tableName = static::getTableName();
        $tableMap = static::getTableMap();
        static::$entityList[$entityClass] = new Entity($tableName, $tableMap);
        return static::$entityList[$entityClass];
    }

    public static function getEntityClass()
    {
        $tableClass = get_called_class();
        if (!is_subclass_of($tableClass, TableManager::class)) {
            throw new \Exception('Класс ' . $tableClass . ' не наследуется от класса ' . TableManager::class);
        }
        return $tableClass;
    }

    public static function add($fields)
    {
        $entity = static::getEntity();
        return $entity->add($fields);
    }

    public static function update($id, $fields)
    {
        $entity = static::getEntity();
        return $entity->update($id, $fields);
    }

    public static function delete($id)
    {
        $entity = static::getEntity();
        return $entity->deleteByPrimary($id);
    }

    public static function getList($params)
    {
        $entity = static::getEntity();
        return $entity->getList($params);
    }

    public static function getById($params)
    {
        $entity = static::getEntity();
        return $entity->getByPrimary($params);
    }

    public static function getPrimaryFieldName()
    {
        $entity = static::getEntity();
        return $entity->getPrimaryFieldName();
    }

    public abstract static function getTableName();
    public abstract static function getTableMap();
}


