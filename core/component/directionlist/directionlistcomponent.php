<?php
namespace core\component\directionlist;


use core\component\tablelist\DefaultUseTableListComponent;
use core\lib\presentation\DirectionListInteractor;
use core\lib\presentation\TableInteractorCompatible;
use core\lib\table\InstituteTable;


class DirectionListComponent extends DefaultUseTableListComponent
{
    const DEFAULT_TABLE_NAME = 'Направления';
    const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 20
        ),
        'NAME' => array(
            'NAME' => 'Название направления',
            'WIDTH' => 40
        ),
        'INSTITUTE_ID' => array(
            'NAME' => 'Институт',
            'WIDTH' => 40,
            'SORT_CODE' => 'INSTITUTE_NAME'
        ),
    );

    protected function getHeader(): array {
        $header = self::HEADER_COLUMN_MAP;
        $instituteList = InstituteTable::getList(array(
                'order' => array('NAME' => 'ASC')
        ));

        $header['INSTITUTE_ID']['VALUES'] = array();
        foreach ($instituteList as $value) {
            $header['INSTITUTE_ID']['VALUES'][$value['ID']] = $value['NAME'];
        }

        return $header;
    }

    protected function getTableName(): string {
        return self::DEFAULT_TABLE_NAME;
    }

    protected function getListInteractorInstance(): TableInteractorCompatible {
        return new DirectionListInteractor();
    }
}
