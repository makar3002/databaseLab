<?
/** @var array $arResult*/
/** @var array $arParams*/
?>

<?if (!$arResult['TABLE_ONLY']) :?>
    <div class="d-inline-flex action-div">
        <?if (isset($arResult['DIRECTION_ID_LIST'])) : ?>
        <select class="form-control" id="direction-id-select">
            <option value="">Не выбрано</option>
            <?foreach ($arResult['DIRECTION_ID_LIST'] as $id => $description) :?>
                <option value="<?=$id?>"><?=$description?></option>
            <?endforeach;?>
        </select>
        <?endif;?>
    </div>
    <div id="table-list">
<?endif;?>
<article class="mx-5 my-3 entry">
    <?if (empty($arResult['DIRECTION_ID'])) :?>
        <h5>Выберите направление.</h5>
    <?else :?>
    <table border="1px" class="schedule-table table mx-auto mb-2">
        <caption>
            <h5>Расписание</h5>
        </caption>
        <?$groupCount = count($arResult['TABLE_TOP_HEADER']);?>
        <?$tdWidth = (90) / $groupCount;?>
        <tr>
            <th colspan="2" width="10%"></th>
            <?foreach ($arResult['TABLE_TOP_HEADER'] as $headerElement) :?>
                <th width="<?=$tdWidth?>%" colspan="2">
                    <span><?=$headerElement?></span>
                </th>
            <?endforeach;?>
        </tr>
        <?$daysCount = count($arResult['TABLE_LEFT_DAYS_OF_WEEK_HEADER'])?>
        <?foreach ($arResult['TABLE_LEFT_DAYS_OF_WEEK_HEADER'] as $dayOfWeek) :?>
            <tr>
            <td rowspan="<?=$daysCount * 2?>"><p class="vertical"><?=$dayOfWeek?></p></td>
            <?foreach ($arResult['TABLE_LEFT_NUMBER_HEADER'] as $key => $number) :?>
                <?if ($key != 0 ) : ?>
                    <tr>
                <?endif;?>
                <td rowspan="2"><?=$number?></td>
                <?foreach ($arResult['TABLE_TOP_HEADER'] as $groupName) :?>
                    <?$scheduleDataList = $arResult['TABLE_DATA'][$dayOfWeek][$number][$groupName];?>
                    <?if (empty($scheduleDataList)) :?>
                        <td rowspan="2" colspan="2">
                            <div></div>
                        </td>
                        <?continue;?>
                    <?endif;?>
                    <?if (isset($scheduleDataList[1])) :?>
                        <?$scheduleElement = $scheduleDataList[1];?>
                        <?$isKindElement = isset($scheduleDataList[3]) || $scheduleElement['TYPE'] == 1;?>
                        <?$isSubgroupElement = (isset($scheduleDataList[2]) || $scheduleElement['SUBGROUP'] == 1) || (!$isKindElement && isset($scheduleDataList[4]));?>
                        <td <?= !$isKindElement ? 'rowspan="2" ' : ''?> <?= !$isSubgroupElement ? 'colspan="2" ' : ''?>>
                            <?if (!empty($scheduleElement)) :?>
                                <div>
                                    <span><?=$scheduleElement['SUBJECT_NAME'] . ' (' . $scheduleElement['KIND'] . ')'?></span>
                                    <br>
                                    <span><?=$scheduleElement['TEACHER_NAME'] . ', ' . $scheduleElement['AUDITORIUM_NAME']?></span>
                                </div>
                            <? else :?>
                                <div></div>
                            <?endif;?>
                        </td>
                        <?if ($isSubgroupElement && !isset($scheduleDataList[2])) :?>
                            <td>
                                <div></div>
                            </td>
                        <?endif;?>
                    <?else :?>
                        <?$isSubgroupElement = isset($scheduleDataList[2]);?>
                        <td <?= !$isSubgroupElement ? 'colspan="2" ' : ''?>>
                            <div></div>
                        </td>
                    <?endif;?>
                    <?if (isset($scheduleDataList[2])) :?>
                        <?$scheduleElement = $scheduleDataList[2];?>
                        <?$isKindElement = isset($scheduleDataList[4]) || $scheduleElement['TYPE'] == 1;?>
                        <?$isSubgroupElement = (isset($scheduleDataList[1]) || $scheduleElement['SUBGROUP'] == 2);?>
                        <td <?= !$isKindElement ? 'rowspan="2" ' : ''?>>
                            <?if (!empty($scheduleElement)) :?>
                                <div>
                                    <span><?=$scheduleElement['SUBJECT_NAME'] . ' (' . $scheduleElement['KIND'] . ')'?></span>
                                    <br>
                                    <span><?=$scheduleElement['TEACHER_NAME'] . ', ' . $scheduleElement['AUDITORIUM_NAME']?></span>
                                </div>
                            <? else :?>
                                <div></div>
                            <?endif;?>
                        </td>
                    <?endif;?>
                <?endforeach;?>
                </tr>
                <tr>
                <?foreach ($arResult['TABLE_TOP_HEADER'] as $groupName) :?>

                    <?$scheduleDataList = $arResult['TABLE_DATA'][$dayOfWeek][$number][$groupName];?>
                    <?if (empty($scheduleDataList)) {
                        continue;
                    }?>
                    <?if (isset($scheduleDataList[3])) :?>
                        <?$scheduleElement = $scheduleDataList[3];?>
                        <?$isKindElement = isset($scheduleDataList[1]) || isset($scheduleDataList[2]) || $scheduleElement['TYPE'] == 2;?>
                        <?$isSubgroupElement = (isset($scheduleDataList[4]) || $scheduleElement['SUBGROUP'] == 1);?>
                        <td <?= !$isSubgroupElement ? 'colspan="2" ' : ''?>>
                            <?if (!empty($scheduleElement)) :?>
                                <div>
                                    <span><?=$scheduleElement['SUBJECT_NAME'] . ' (' . $scheduleElement['KIND'] . ')'?></span>
                                    <br>
                                    <span><?=$scheduleElement['TEACHER_NAME'] . ', ' . $scheduleElement['AUDITORIUM_NAME']?></span>
                                </div>
                            <? else :?>
                                <div></div>
                            <?endif;?>
                        </td>
                        <?if ($isSubgroupElement && !isset($scheduleDataList[4])) :?>
                            <td>
                                <div></div>
                            </td>
                        <?endif;?>
                    <?elseif (!(!empty($scheduleDataList[1]) && $scheduleDataList[1]['TYPE'] != 1)):?>
                        <?$isSubgroupElement = isset($scheduleDataList[4]);?>
                        <td <?= !$isSubgroupElement ? 'colspan="2" ' : ''?>>
                            <div></div>
                        </td>
                    <?endif;?>
                    <?if (isset($scheduleDataList[4])) :?>
                        <?$scheduleElement = $scheduleDataList[4];?>
                        <?$isKindElement = isset($scheduleDataList[1]) || isset($scheduleDataList[2]) || $scheduleElement['TYPE'] === 2;?>
                        <?$isSubgroupElement = (isset($scheduleDataList[3]) || $scheduleElement['SUBGROUP'] === 2);?>
                        <td>
                            <?if (!empty($scheduleElement)) :?>
                                <div>
                                    <span><?=$scheduleElement['SUBJECT_NAME'] . ' (' . $scheduleElement['KIND'] . ')'?></span>
                                    <br>
                                    <span><?=$scheduleElement['TEACHER_NAME'] . ', ' . $scheduleElement['AUDITORIUM_NAME']?></span>
                                </div>
                            <? else :?>
                                <div></div>
                            <?endif;?>
                        </td>
                    <?endif;?>
                <?endforeach;?>
            </tr>
            <?endforeach;?>
        <?endforeach;?>
    </table>
<?endif;?>
</article>
<?if (!$arResult['TABLE_ONLY']) :?>
    </div>
    <script>
        $(document).ready(function () {
            let schedule = new Schedule();
            schedule.initialize();
        });
    </script>
<?endif;?>