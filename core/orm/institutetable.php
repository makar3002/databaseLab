<?php
namespace Core\Orm;
use Core\Orm\General\FieldAttributeType;
use Core\Orm\General\TableManager;

class InstituteTable extends TableManager
{
    public static function getTableName()
    {
        return 'institute';
    }

    protected static $tableMap = array(
        'ID' => array(
            'ATTRIBUTES' => array(
                FieldAttributeType::PRIMARY,
                FieldAttributeType::READ_ONLY
            )
        ),
        'NAME' => array(
            'ATTRIBUTES' => array()
        ),
    );
}