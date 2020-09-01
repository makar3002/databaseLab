<?php
namespace core\lib\table;


use core\lib\orm\FieldAttributeType;
use core\lib\orm\TableManager;


class FeedbackTable extends TableManager
{
    public static function getTableName()
    {
        return 'feedback';
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
            'EMAIL' => array(
                'ATTRIBUTES' => array()
            ),
            'NEED_ANSWER' => array(
                'ATTRIBUTES' => array()
            ),
            'SEX' => array(
                'ATTRIBUTES' => array()
            ),
            'MESSAGE' => array(
                'ATTRIBUTES' => array()
            ),
            'DATE_CREATE' => array(
                'ATTRIBUTES' => array(),
                'DEFAULT' => (new \DateTime())->format('yy-m-d h:i:s')
            )
        );
    }
}