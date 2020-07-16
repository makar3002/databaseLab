<?php
namespace Core\Orm;
use Core\Orm\General\FieldAttributeType;
use Core\Orm\General\TableManager;

class TeacherTable extends TableManager
{
    public static function getTableName()
    {
        return 'teacher';
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
            'INSTITUTE_ID' => array(
                'ATTRIBUTES' => array(),
                'REFERENCE' => array(
                    'TABLE_CLASS' => InstituteTable::class,
                    'SELECT_NAME_MAP' => array(
                        'INSTITUTE_NAME' => 'NAME'
                    )
                ),
            )
        );
    }
}