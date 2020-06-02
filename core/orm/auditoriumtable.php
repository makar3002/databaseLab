<?php
namespace Core\Orm;
use Core\Orm\General\FieldAttributeType;
use Core\Orm\General\TableManager;

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/orm/general/tablemanager.php');

class AuditoriumTable extends TableManager
{
    public static function getTableName()
    {
        return 'auditorium';
    }

    protected static function getTableMap()
    {
        return array(
            'ID' => array(
                FieldAttributeType::READ_ONLY
            ),
            'NAME' => array(),
            'CAPACITY' => array(),
        );
    }
}