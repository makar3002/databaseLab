<?php
namespace core\component\institutelist;

use core\component\tablelist\DefaultUseTableListComponent;
use core\lib\facade\Institute;
use core\lib\facade\TableInteraction;

class InstituteListComponent extends DefaultUseTableListComponent
{
    protected const DEFAULT_TABLE_NAME = 'Институты';
    protected const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 20
        ),
        'NAME' => array(
            'NAME' => 'Институт',
            'WIDTH' => 80
        ),
    );
    protected const DEFAULT_SORT = array(
        'ID' => 'ASC'
    );

    protected function getTableInteractionFacadeInstance(): TableInteraction {
        return new Institute();
    }

    protected function getHeader(): array {
        return self::HEADER_COLUMN_MAP;
    }

    protected function getTableName(): string {
        return self::DEFAULT_TABLE_NAME;
    }
}
