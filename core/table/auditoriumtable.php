<?php
namespace core\table;

use core\util\orm\FieldAttributeType;
use core\util\orm\TableManager;

class AuditoriumTable extends TableManager
{
    public static function getTableName()
    {
        return 'auditorium';
    }

    public static function getTableMap()
    {
        return array(
            'ID' => array(
                'ATTRIBUTES' => array(
                    FieldAttributeType::PRIMARY,
                    FieldAttributeType::READ_ONLY
                )
            ),
            'NAME' => array(
                'ATTRIBUTES' => array()
            ),
            'CAPACITY' => array(
                'ATTRIBUTES' => array()
            ),
        );
    }
}