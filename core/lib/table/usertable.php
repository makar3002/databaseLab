<?php
namespace core\lib\table;


use core\lib\orm\FieldAttributeType;
use core\lib\orm\TableManager;


class UserTable extends TableManager
{
    private static $rightUserToGroup;

    public static function getList($arFields)
    {
        $userList = parent::getList($arFields);
        if (
            isset($arFields['select']) && !empty($arFields['select']) && (
                !is_int(array_search('GROUP_IDS', $arFields['select']))
                || !is_int(array_search('ID', $arFields['select']))
            )
        ) {
            return $userList;
        }

        $userIdList = array_column($userList, 'ID');
        self::$rightUserToGroup = RightUserToGroupTable::getList(array(
            'select' => array('USER_ID', 'GROUP_ID'),
            'filter' => array('@USER_ID' => $userIdList)
        ));


        $userList = array_map(
            'self::mergeUserAndItsGroupIds',
            $userList
        );
        return $userList;
    }

    public static function updateBindEntity($id, $fieldId, $fieldValues)
    {
        if ($fieldId == 'GROUP_IDS') {
            $currentGroup = array_column(RightUserToGroupTable::getList(array(
                'select' => array('ID'),
                'filter' => array('USER_ID' => $id)
            )), 'ID');

            foreach ($currentGroup as $userToGroupId) {
                RightUserToGroupTable::delete($userToGroupId);
            }

            $arNewBind = array(
                'USER_ID' => $id
            );

            foreach ($fieldValues as $teacherId) {
                $arNewBind['GROUP_ID'] = $teacherId;
                RightUserToGroupTable::add($arNewBind);
            }
        }
    }

    public static function deleteBindEntity($id, $fieldId)
    {
        if ($fieldId == 'USER_IDS') {
            $currentGroup = RightUserToGroupTable::getList(array(
                'select' => array('ID'),
                'filter' => array('USER_ID' => $id)
            ));
            foreach ($currentGroup as $userToGroupId) {
                RightUserToGroupTable::delete($userToGroupId);
            }
        }
    }

    private static function mergeUserAndItsGroupIds($user)
    {
        $user['GROUP_IDS'] = array();

        $userId = $user['ID'];
        foreach (self::$rightUserToGroup as $key => $userToGroup) {
            if ($userToGroup['USER_ID'] != $userId) {
                continue;
            }

            $user['GROUP_IDS'][] = $userToGroup['GROUP_ID'];
            unset(self::$rightUserToGroup[$key]);
        }

        return $user;
    }
    public static function getTableName()
    {
        return 'user';
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
            'LAST_NAME' => array(
                'ATTRIBUTES' => array()
            ),
            'SECOND_NAME' => array(
                'ATTRIBUTES' => array()
            ),
            'EMAIL' => array(
                'ATTRIBUTES' => array()
            ),
            'PASSWORD' => array(
                'ATTRIBUTES' => array(
                    FieldAttributeType::SELECT_AND_WHERE_ONLY
                )
            ),
            'GROUP_IDS' => array(
                'ATTRIBUTES' => array(
                    FieldAttributeType::SELECT_ONLY,
                    FieldAttributeType::ARRAY_VALUE
                )
            ),
        );
    }
}