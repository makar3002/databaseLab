<?php
namespace core\component\grouplist;


use core\component\tablelist\DefaultUseTableListComponent;
use core\lib\presentation\GroupListInteractor;
use core\lib\presentation\TableInteractorCompatible;
use core\lib\table\DirectionTable;


class GroupListComponent extends DefaultUseTableListComponent
{
    const DEFAULT_TABLE_NAME = 'Студентческие группы';
    const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 10
        ),
        'NAME' => array(
            'NAME' => 'Группа',
            'WIDTH' => 20
        ),
        'COUNT' => array(
            'NAME' => 'Количество студентов',
            'WIDTH' => 40
        ),
        'DIRECTION_ID' => array(
            'NAME' => 'Направление',
            'WIDTH' => 30,
            'SORT_CODE' => 'DIRECTION_NAME'
        ),
    );

    protected function getHeader(): array {
        $header = self::HEADER_COLUMN_MAP;
        $directionList = DirectionTable::getList(array(
                'order' => array('NAME' => 'ASC')
        ));

        $header['DIRECTION_ID']['VALUES'] = array();
        foreach ($directionList as $value) {
            $header['DIRECTION_ID']['VALUES'][$value['ID']] = $value['NAME'];
        }

        return $header;
    }

    protected function getTableName(): string {
        return self::DEFAULT_TABLE_NAME;
    }

    protected function getListInteractorInstance(): TableInteractorCompatible {
        return new GroupListInteractor();
    }
}
