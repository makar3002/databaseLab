<?php
namespace core\component\auditoriumlist;


use core\component\tablelist\DefaultUseTableListComponent;
use core\lib\presentation\AuditoriumListInteractor;
use core\lib\presentation\TableInteractorCompatible;


class AuditoriumListComponent extends DefaultUseTableListComponent
{
    const DEFAULT_TABLE_NAME = 'Аудитории';
    const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 20
        ),
        'NAME' => array(
            'NAME' => 'Номер аудитории',
            'WIDTH' => 40
        ),
        'CAPACITY' => array(
            'NAME' => 'Вместимость',
            'WIDTH' => 40
        ),
    );

    protected function getHeader(): array {
        $header = self::HEADER_COLUMN_MAP;
        return $header;
    }

    protected function getTableName(): string {
        return self::DEFAULT_TABLE_NAME;
    }

    protected function getListInteractorInstance(): TableInteractorCompatible {
        return new AuditoriumListInteractor();
    }
}
