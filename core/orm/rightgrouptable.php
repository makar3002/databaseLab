<?php
namespace Core\Orm;
use Core\Orm\General\FieldAttributeType;
use Core\Orm\General\TableManager;

class RightGroupTable extends TableManager
{
    private static $rightActionToGroup;

    public static function getList($arFields)
    {
        $groupList = parent::getList($arFields);
        if (
            isset($arFields['select']) && !empty($arFields['select']) && (
                !is_int(array_search('ACTION_IDS', $arFields['select']))
                || !is_int(array_search('ID', $arFields['select']))
            )
        ) {
            return $groupList;
        }

        $groupIdList = array_column($groupList, 'ID');
        self::$rightActionToGroup = RightActionToGroupTable::getList(array(
            'select' => array('ACTION_ID', 'GROUP_ID'),
            'filter' => array('@GROUP_ID' => $groupIdList)
        ));

        $groupList = array_map(
            'self::mergeGroupAndItsActionIds',
            $groupList
        );
        return $groupList;
    }

    public static function updateBindEntity($id, $fieldId, $fieldValues)
    {
        if ($fieldId == 'ACTION_IDS') {
            $currentGroup = array_column(RightUserToGroupTable::getList(array(
                'select' => array('ID'),
                'filter' => array('GROUP_ID' => $id)
            )), 'ID');

            foreach ($currentGroup as $userToGroupId) {
                RightActionToGroupTable::delete($userToGroupId);
            }

            $arNewBind = array(
                'GROUP_ID' => $id
            );

            foreach ($fieldValues as $teacherId) {
                $arNewBind['ACTION_ID'] = $teacherId;
                RightActionToGroupTable::add($arNewBind);
            }
        }
    }

    public static function deleteBindEntity($id, $fieldId)
    {
        if ($fieldId == 'ACTION_IDS') {
            $currentGroup = RightActionToGroupTable::getList(array(
                'select' => array('ID'),
                'filter' => array('GROUP_ID' => $id)
            ));
            foreach ($currentGroup as $userToGroupId) {
                RightActionToGroupTable::delete($userToGroupId);
            }
        }
    }

    private static function mergeGroupAndItsActionIds($user)
    {
        $user['ACTION_IDS'] = array();

        $userId = $user['ID'];
        foreach (self::$rightActionToGroup as $key => $userToGroup) {
            if ($userToGroup['GROUP_ID'] != $userId) {
                continue;
            }

            $user['ACTION_IDS'][] = $userToGroup['ACTION_ID'];
            unset(self::$rightActionToGroup[$key]);
        }

        return $user;
    }

    public static function getTableName()
    {
        return 'right_group';
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
            'ACTION_IDS' => array(
                'ATTRIBUTES' => array(
                    FieldAttributeType::SELECT_ONLY,
                    FieldAttributeType::ARRAY_VALUE
                )
            )
        );
    }
}