<?php
namespace core\component\actionlist;


use core\component\tablelist\DefaultUseTableListComponent;
use core\lib\facade\Action;
use core\lib\facade\TableInteraction;


class ActionListComponent extends DefaultUseTableListComponent {
    protected const DEFAULT_TABLE_NAME = 'Действия';
    protected const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 20
        ),
        'CODE' => array(
            'NAME' => 'Код действия',
            'WIDTH' => 40
        ),
        'NAME' => array(
            'NAME' => 'Название',
            'WIDTH' => 40
        ),
    );

    protected function getTableInteractionFacadeInstance(): TableInteraction
    {
        return new Action();
    }

    protected function getHeader(): array
    {
        return self::HEADER_COLUMN_MAP;
    }

    protected function getTableName(): string {
        return self::DEFAULT_TABLE_NAME;
    }
}
