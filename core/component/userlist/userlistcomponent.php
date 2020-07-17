<?php
namespace core\component\UserList;

use core\component\tableList\TableListComparable;
use core\table\RightGroupTable;

class UserListComponent extends TableListComparable
{
    const DEFAULT_TABLE_NAME = 'Пользователи';
    const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 5
        ),
        'NAME' => array(
            'NAME' => 'Имя',
            'WIDTH' => 6
        ),
        'LAST_NAME' => array(
            'NAME' => 'Фамилия',
            'WIDTH' => 9,
        ),
        'SECOND_NAME' => array(
            'NAME' => 'Отчество',
            'WIDTH' => 10,
        ),
        'EMAIL' => array(
            'NAME' => 'Почта',
            'WIDTH' => 10,
        ),
        'PASSWORD' => array(
            'NAME' => 'Пароль (зашифрованный)',
            'WIDTH' => 45,
        ),
        'GROUP_IDS' => array(
            'NAME' => 'Группы',
            'WIDTH' => 15,
            'IS_MULTIPLE' => true
        ),
    );

    public function processComponent()
    {
        if (!isset($this->arResult['TABLE_ONLY'])) {
            $this->arResult['TABLE_ONLY'] = false;
        }
        $this->prepareHeader();
        $this->prepareData();
        $this->renderComponent();
    }

    protected function prepareHeader()
    {
        $this->arResult['TABLE_HEADER'] = self::HEADER_COLUMN_MAP;

        $groupList = RightGroupTable::getList(array(
            'order' => array('NAME' => 'ASC')
        ));

        $this->arResult['TABLE_HEADER']['GROUP_IDS']['VALUES'] = array();
        foreach ($groupList as $value) {
            $this->arResult['TABLE_HEADER']['GROUP_IDS']['VALUES'][$value['ID']] = $value['NAME'];
        }
    }

    protected function prepareData()
    {
        $this->arResult['TABLE_NAME'] = self::DEFAULT_TABLE_NAME;
    }
}
