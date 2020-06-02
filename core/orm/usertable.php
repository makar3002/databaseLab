<?php
namespace Core\Orm;
use Core\Orm\General\FieldAttributeType;
use Core\Orm\General\TableManager;
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/orm/general/tablemanager.php');

class UserTable extends TableManager
{
    public static function getTableName()
    {
        return 'user';
    }

    protected static function getTableMap()
    {
        return array(
            'ID' => array(
                FieldAttributeType::READ_ONLY
            ),
            'NAME' => array(),
            'LAST_NAME' => array(),
            'SECOND_NAME' => array(),
            'EMAIL' => array(),
            'PASSWORD' => array(
                FieldAttributeType::SELECT_AND_WHERE_ONLY
            )
        );
    }
}