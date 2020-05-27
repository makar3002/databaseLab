<?php
namespace Core\Component\ScheduleElementList;

use core\component\general\BaseComponent;
use core\orm\AuditoriumTable;
use core\orm\GroupTable;
use core\orm\SubjectTable;
use core\orm\TeacherTable;

class ScheduleElementListComponent extends BaseComponent
{
    const DEFAULT_TABLE_NAME = 'Пары';
    const HEADER_COLUMN_MAP = array(
        'ID' => array(
            'NAME' => 'ID',
            'WIDTH' => 10
        ),
        'SUBJECT_ID' => array(
            'NAME' => 'Предмет',
            'WIDTH' => 10
        ),
        'TYPE' => array(
            'NAME' => 'Числитель/знаменатель',
            'WIDTH' => 10,
            'VALUES' => array(
                '0' => 'Еженедельная',
                '1' => 'Числитель',
                '2' => 'Знаменатель'
            )
        ),
        'SUBGROUP' => array(
            'NAME' => 'Подгруппа',
            'WIDTH' => 10,
            'VALUES' => array(
                '0' => 'Обе',
                '1' => 'Первая',
                '2' => 'Вторая',
            )
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
            'WIDTH' => 10
        ),
        'GROUP_ID' => array(
            'NAME' => 'Группа',
            'WIDTH' => 10
        ),
        'AUDITORIUM_ID' => array(
            'NAME' => 'Аудитория',
            'WIDTH' => 10
        ),
    );

    public function getTableOnlyAction()
    {
        $this->arResult['TABLE_ONLY'] = true;
        if (isset($this->arParams['SORT'])) {
            $this->arResult['TABLE_SORT'] = json_decode($this->arParams['SORT'], true);
        }
        $this->processComponent();
    }

    public function processComponent()
    {
        if (!isset($this->arResult['TABLE_ONLY'])) {
            $this->arResult['TABLE_ONLY'] = false;
        }
        $this->prepareHeader();
        $this->prepareData();
        $this->renderComponent();
    }

    private function prepareHeader()
    {
        $this->arResult['TABLE_HEADER'] = self::HEADER_COLUMN_MAP;
        $subjectList = SubjectTable::getList(array(
            'order' => array('NAME' => 'ASC')
        ));

        $this->arResult['TABLE_HEADER']['SUBJECT_ID']['VALUES'] = array();
        foreach ($subjectList as $value) {
            $this->arResult['TABLE_HEADER']['SUBJECT_ID']['VALUES'][$value['ID']] = $value['NAME'];
        }

        $teacherList = TeacherTable::getList(array(
            'order' => array('NAME' => 'ASC')
        ));

        $this->arResult['TABLE_HEADER']['TEACHER_ID']['VALUES'] = array();
        foreach ($teacherList as $value) {
            $this->arResult['TABLE_HEADER']['TEACHER_ID']['VALUES'][$value['ID']] = $value['NAME'];
        }

        $groupList = GroupTable::getList(array(
            'order' => array('NAME' => 'ASC')
        ));

        $this->arResult['TABLE_HEADER']['GROUP_ID']['VALUES'] = array();
        foreach ($groupList as $value) {
            $this->arResult['TABLE_HEADER']['GROUP_ID']['VALUES'][$value['ID']] = $value['NAME'];
        }

        $auditoriumList = AuditoriumTable::getList(array(
            'order' => array('NAME' => 'ASC')
        ));

        $this->arResult['TABLE_HEADER']['AUDITORIUM_ID']['VALUES'] = array();
        foreach ($auditoriumList as $value) {
            $this->arResult['TABLE_HEADER']['AUDITORIUM_ID']['VALUES'][$value['ID']] = $value['NAME'];
        }
    }

    private function prepareData()
    {
        $this->arResult['TABLE_NAME'] = self::DEFAULT_TABLE_NAME;
    }
}
