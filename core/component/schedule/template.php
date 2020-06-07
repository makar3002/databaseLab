<?
/** @var array $arResult*/
/** @var array $arParams*/
?>

<?if (!$arResult['TABLE_ONLY']) :?>
    <div class="d-inline-flex action-div">

    </div>
    <div id="table-list">
        <?endif;?>
        <article class="mx-5 my-3 entry">
            <?if (!isset($arResult['DIRECTION_ID'])) :?>
                <h5>Выберите направление.</h5>
            <?else :?>
                <table border="1px" class="table">
                    <caption>
                        <h5>Расписание</h5>
                    </caption>
                    <?$tdWidth = (90) / count($arResult['TABLE_TOP_HEADER']);?>
                    <tr>
                        <td colspan="2" width="10%"></td>
                        <?foreach ($arResult['TABLE_TOP_HEADER'] as $headerElement) :?>
                            <td width="<?=$tdWidth?>%">
                                <span><?=$headerElement?></span>
                            </td>
                        <?endforeach;?>
                    </tr>

                    <?foreach ($arResult['TABLE_LEFT_DAYS_OF_WEEK_HEADER'] as $dayOfWeek) :?>
                        <tr>
                        <td rowspan="6" width="5%"><p class="vertical"><?=$dayOfWeek?></p></td>
                            <?foreach ($arResult['TABLE_LEFT_NUMBER_HEADER'] as $key => $number) :?>
                                <?if ($key != 0 ) : ?>
                                <tr>
                                <?endif;?>
                                <td width="5%"><?=$number?></td>
                                <?foreach ($arResult['TABLE_TOP_HEADER'] as $groupName) :?>
                                    <td width="<?=$tdWidth?>%">
                                        <?$scheduleData = $arResult['TABLE_DATA'][$dayOfWeek][$number][$groupName];?>
                                        <?if (!empty($scheduleData)) :?>
                                        <div>
                                            <span><?=$scheduleData['SUBJECT_NAME'] . ' (' . $scheduleData['KIND'] . ')'?></span>
                                            <br>
                                            <span><?=$scheduleData['TEACHER_NAME'] . ', ' . $scheduleData['AUDITORIUM_NAME']?></span>
                                        </div>
                                        <? else :?>
                                        <div></div>
                                        <?endif;?>
                                    </td>
                                <?endforeach;?>
                                </tr>
                            <?endforeach;?>
                    <?endforeach;?>
                </table>
            <?endif;?>
        </article>
    </div>
<?if (!$arResult['TABLE_ONLY']) :?>
    <script>
        $(document).ready(function () {
            //let tableList = new TableList(
            //    "<?//=str_replace('\\', '\\\\', $arResult['ENTITY_CLASS']);?>//",
            //    "<?//=str_replace('\\', '\\\\', $arResult['ENTITY_TABLE_CLASS']);?>//"
            //);
            //tableList.initialize();
        });
    </script>
<?endif;?>