<?php
namespace Core\Util;

use Core\Orm\RightActionTable;
use Core\Orm\RightActionToGroupTable;
use Core\Orm\RightGroupTable;

class Right {
    private $rightGroupList;
    private $rightActionList;

    public function __construct(array $rightGroupList) {
        $this->rightGroupList = $rightGroupList;
        $rightActionIdsList = RightGroupTable::getList(array(
            'select' => array('ID', 'ACTION_IDS'),
            'filter' => array(
                '@ID' => $this->rightGroupList,
            )
        ));

        $rightActionIdList = array();
        foreach ($rightActionIdsList as $actionIdList) {
            $rightActionIdList = array_merge($rightActionIdList, $actionIdList['ACTION_IDS']);
        }

        $rightActionList = RightActionTable::getList(array(
            'filter' => array(
                '@ID' => array_unique($rightActionIdList)
            )
        ));
        $this->rightActionList = array_combine(array_column(
            $rightActionList,
            'CODE'
        ), $rightActionList);
    }

    public function checkActionByCode($actionCode): bool {
        return array_key_exists($actionCode, $this->rightActionList);
    }
}
