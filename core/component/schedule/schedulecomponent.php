<?php
namespace Core\Component\Schedule;

use Core\Component\General\BaseComponent;
use Core\Orm\ScheduleElementTable;

class ScheduleComponent extends BaseComponent
{
    private const DEFAULT_LEFT_DAYS_OF_WEEK_HEADER_MAP = array(
        '1' => 'Понедельник',
        '2' => 'Вторник',
        '3' => 'Среда',
        '4' => 'Четверг',
        '5' => 'Пятница',
        '6' => 'Суббота',
    );
    private const DEFAULT_LEFT_NUMBER_HEADER_MAP = array(
        '1' => '8:30 - 10:00',
        '2' => '10:10 - 11:40',
        '3' => '12:00 - 13:30',
        '4' => '13:40 - 15:10',
        '5' => '15:20 - 16:50',
        '6' => '17:00 - 18:30',
    );

    private const SCHEDULE_ELEMENT_DATA_MAP = array(
        'KIND' => array(
            '1' => 'Л',
            '2' => 'Пр',
            '3' => 'Лаб',
        ),
    );

    public function processComponent()
    {
        if (isset($this->arParams['DIRECTION_ID'])) {
            $this->arResult['DIRECTION_ID'] = $this->arParams['DIRECTION_ID'];
        }
        $this->prepareData();
        $this->renderComponent();
    }

    public function refreshTable()
    {
        if (isset($this->arParams['DIRECTION_ID'])) {
            $this->arResult['DIRECTION_ID'] = $this->arParams['DIRECTION_ID'];
        }
        $this->processComponent();
    }

    private function prepareData()
    {
        if (isset($this->arResult['DIRECTION_ID']) && intval($this->arResult['DIRECTION_ID']) > 0) {
            $directionId = intval($this->arResult['DIRECTION_ID']);
            $rawData = ScheduleElementTable::getScheduleByDirectionId($directionId);
            $topHeader = array_unique(array_column($rawData, 'GROUP_NAME'));
            $leftDaysOfWeekHeader = array_values(self::DEFAULT_LEFT_DAYS_OF_WEEK_HEADER_MAP);
            $leftNumberHeader = array_values(self::DEFAULT_LEFT_NUMBER_HEADER_MAP);
            $data = array();
            foreach (self::DEFAULT_LEFT_DAYS_OF_WEEK_HEADER_MAP as $dayOfWeekKey => $dayOfWeekValue) {
                $leftDaysOfWeekBlock = array();
                foreach (self::DEFAULT_LEFT_NUMBER_HEADER_MAP as $numberKey => $numberValue) {
                    $leftNumberBlock = array();
                    foreach ($topHeader as $groupName) {
                        $topGroupBlock = array();
                        foreach ($rawData as $key => $scheduleElement) {
                            if (
                                $scheduleElement['DAY_OF_WEEK'] != $dayOfWeekKey ||
                                $scheduleElement['NUMBER'] != $numberKey ||
                                $scheduleElement['GROUP_NAME'] != $groupName
                            ) {
                                continue;
                            }

                            $topGroupBlock = $this->prepareScheduleElement($scheduleElement);
                            unset($rawData[$key]);
                        }
                        $leftNumberBlock[$groupName] = $topGroupBlock;
                    }
                    $leftDaysOfWeekBlock[$numberValue] = $leftNumberBlock;
                }
                $data[$dayOfWeekValue] = $leftDaysOfWeekBlock;
            }
            $this->arResult['TABLE_DATA'] = $data;
            $this->arResult['TABLE_TOP_HEADER'] = $topHeader;
            $this->arResult['TABLE_LEFT_DAYS_OF_WEEK_HEADER'] = $leftDaysOfWeekHeader;
            $this->arResult['TABLE_LEFT_NUMBER_HEADER'] = $leftNumberHeader;
        } else {
            $this->arResult['TABLE_DATA'] = array();
        }
    }

    private function prepareScheduleElement($scheduleElementData)
    {
        foreach (self::SCHEDULE_ELEMENT_DATA_MAP as $fieldName => $fieldMap) {
            if (!isset($scheduleElementData[$fieldName])) {
                continue;
            }

            $scheduleElementData[$fieldName] = $fieldMap[$scheduleElementData[$fieldName]];
        }

        return $scheduleElementData;
    }
}
