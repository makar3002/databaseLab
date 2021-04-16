<?php
namespace core\component\scheduleelementlist;


use core\component\tablelist\DefaultUseTableListComponent;
use core\lib\presentation\ScheduleElementListInteractor;
use core\lib\table\AuditoriumTable;
use core\lib\table\GroupTable;
use core\lib\table\SubjectTable;
use core\lib\table\TeacherTable;
use core\lib\presentation\TableInteractorCompatible;


class ScheduleElementListComponent extends DefaultUseTableListComponent
{
    const DEFAULT_TABLE_NAME = 'Пары';
    const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 10
        ),
        'SUBJECT_ID' => array(
            'NAME' => 'Предмет',
            'WIDTH' => 10,
            'SORT_CODE' => 'SUBJECT_NAME'
        ),
        'TYPE' => array(
            'NAME' => 'Числитель/знаменатель',
            'WIDTH' => 10,
            'VALUES' => array(
                '0' => 'Еженедельная',
                '1' => 'Числитель',
                '2' => 'Знаменатель'
            ),
            'DEFAULT_VALUE' => 'Еженедельная'
        ),
        'SUBGROUP' => array(
            'NAME' => 'Подгруппа',
            'WIDTH' => 10,
            'VALUES' => array(
                '0' => 'Обе',
                '1' => 'Первая',
                '2' => 'Вторая',
            ),
            'DEFAULT_VALUE' => 'Обе'
        ),
        'KIND' => array(
            'NAME' => 'Тип предмета',
            'WIDTH' => 10,
            'VALUES' => array(
                '1' => 'Лекция',
                '2' => 'Практика',
                '3' => 'Лабораторная',
            )
        ),
        'DAY_OF_WEEK' => array(
            'NAME' => 'День недели',
            'WIDTH' => 10,
            'VALUES' => array(
                '1' => 'Понедельник',
                '2' => 'Вторник',
                '3' => 'Среда',
                '4' => 'Четверг',
                '5' => 'Пятница',
                '6' => 'Суббота',
            )
        ),
        'NUMBER' => array(
            'NAME' => 'Время',
            'WIDTH' => 10,
            'VALUES' => array(
                '1' => '8:30 - 10:00',
                '2' => '10:10 - 11:40',
                '3' => '12:00 - 13:30',
                '4' => '13:40 - 15:10',
                '5' => '15:20 - 16:50',
                '6' => '17:00 - 18:30',
            )
        ),
        'TEACHER_ID' => array(
            'NAME' => 'Преподаватель',
            'WIDTH' => 10,
            'SORT_CODE' => 'TEACHER_NAME'
        ),
        'GROUP_ID' => array(
            'NAME' => 'Группа',
            'WIDTH' => 10,
            'SORT_CODE' => 'GROUP_NAME'
        ),
        'AUDITORIUM_ID' => array(
            'NAME' => 'Аудитория',
            'WIDTH' => 10,
            'SORT_CODE' => 'AUDITORIUM_NAME'
        ),
    );

    protected function getHeader(): array {
        $header = self::HEADER_COLUMN_MAP;
        $subjectList = SubjectTable::getList(array(
                'order' => array('NAME' => 'ASC')
        ));

        $header['SUBJECT_ID']['VALUES'] = array();
        foreach ($subjectList as $value) {
            $header['SUBJECT_ID']['VALUES'][$value['ID']] = $value['NAME'];
        }

        $teacherList = TeacherTable::getList(array(
                'order' => array('NAME' => 'ASC')
        ));

        $header['TEACHER_ID']['VALUES'] = array();
        foreach ($teacherList as $value) {
            $header['TEACHER_ID']['VALUES'][$value['ID']] = $value['NAME'];
        }

        $groupList = GroupTable::getList(array(
                'order' => array('NAME' => 'ASC')
        ));

        $header['GROUP_ID']['VALUES'] = array();
        foreach ($groupList as $value) {
            $header['GROUP_ID']['VALUES'][$value['ID']] = $value['NAME'];
        }

        $auditoriumList = AuditoriumTable::getList(array(
                'order' => array('NAME' => 'ASC')
        ));

        $header['AUDITORIUM_ID']['VALUES'] = array();
        foreach ($auditoriumList as $value) {
            $header['AUDITORIUM_ID']['VALUES'][$value['ID']] = $value['NAME'];
        }

        return $header;
    }

    protected function getTableName(): string {
        return self::DEFAULT_TABLE_NAME;
    }

    protected function getListInteractorInstance(): TableInteractorCompatible {
        return new ScheduleElementListInteractor();
    }
}
