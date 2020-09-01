<?php
namespace core\component\feedbacklist;


use core\component\tablelist\DefaultUseTableListComponent;
use core\lib\presentation\FeedbackListInteractor;
use core\lib\presentation\TableInteractorCompatible;


class FeedbackListComponent extends DefaultUseTableListComponent {
    protected const DEFAULT_TABLE_NAME = 'Данные обратной связи';
    protected const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 8
        ),
        'NAME' => array(
            'NAME' => 'ФИО',
            'WIDTH' => 12
        ),
        'EMAIL' => array(
            'NAME' => 'Почта',
            'WIDTH' => 15
        ),
        'NEED_ANSWER' => array(
            'NAME' => 'Нужен ответ',
            'WIDTH' => 5,
            'VALUES' => array(
                'Y' => 'Да',
                'N' => 'Нет',
            )
        ),
        'SEX' => array(
            'NAME' => 'Пол',
            'WIDTH' => 5,
            'VALUES' => array(
                '0' => 'Не знаю',
                '1' => 'Мужской',
                '2' => 'Женский'
            )
        ),
        'MESSAGE' => array(
            'NAME' => 'Сообщение',
            'WIDTH' => 40
        ),
        'DATE_CREATE' => array(
            'NAME' => 'Дата подачи',
            'WIDTH' => 15
        ),
    );
    protected const DEFAULT_SORT = array(
        'DATE_CREATE' => 'DESC'
    );

    protected function getListInteractorInstance(): TableInteractorCompatible {
        return new FeedbackListInteractor();
    }

    protected function getHeader(): array {
        return self::HEADER_COLUMN_MAP;
    }

    protected function getTableName(): string {
        return self::DEFAULT_TABLE_NAME;
    }
}
