<?
/** @var array $arResult*/
/** @var array $arParams*/
?>

<?if (!$arResult['TABLE_ONLY']) :?>
<div class="d-inline-flex action-div">
    <button type="button" class="btn btn-primary add-btn p-2 mr-4">Добавить элемент</button>
    <div class="container d-inline-flex h-25">
        <input type="text" id="search-input" class="form-control search-input m-1" placeholder="Поиск">
        <button class="btn btn-primary m-1" type="submit" id="submit-search-btn">Поиск</button>
    </div>
    <button type="button" class="btn btn-primary refresh-btn p-2 mr-4" onclick="TableList.getInstance('<?=str_replace('\\', '\\\\', $arResult['ENTITY_TABLE_CLASS']);?>').refreshTable()">Обновить таблицу</button>
</div>
<div id="table-list">
<?endif;?>
    <article class="mx-5 my-3 entry">
    <?if ($arResult['IS_DATA_EMPTY']) :?>
        <h5><?=$arResult['EMPTY_DATA_TITLE']?></h5>
    <?else :?>
        <table class="table mx-auto mb">
            <caption>
                <h5><?=$arParams['TABLE_NAME']?></h5>
            </caption>
            <tr>
            <?foreach ($arResult['TABLE_HEADER'] as $fieldName => $headerElement) :?>
                <td width="<?=$headerElement['WIDTH']?>%" class="<?=$headerElement['CLASS']?>">
                    <div
                            <?= !(isset($headerElement['IS_MULTIPLE']) && $headerElement['IS_MULTIPLE']) ? 'class="td-header"' : '';?>
                            data-field-name="<?= isset($headerElement['SORT_CODE']) ? $headerElement['SORT_CODE'] : $fieldName?>"
                            <?= (isset($arResult['TABLE_SORT'][$fieldName])) ?
                            'data-sort="' . $arResult['TABLE_SORT'][$fieldName] . '"' :
                                (
                                        (isset($headerElement['SORT_CODE']) && isset($arResult['TABLE_SORT'][$headerElement['SORT_CODE']]))
                                            ? 'data-sort="' . $arResult['TABLE_SORT'][$headerElement['SORT_CODE']] . '"'
                                            : ''
                                )
                            ?>
                    >
                        <span><?=$headerElement['NAME']?></span>
                        <? if (isset($arResult['TABLE_SORT'][$fieldName])) : ?>
                            <span class="arrow-<?=$arResult['TABLE_SORT'][$fieldName]?>"></span>
                        <? elseif (isset($headerElement['SORT_CODE']) && isset($arResult['TABLE_SORT'][$headerElement['SORT_CODE']])) :?>
                            <span class="arrow-<?=$arResult['TABLE_SORT'][$headerElement['SORT_CODE']]?>"></span>
                        <? endif;?>
                    </div>
                </td>
            <?endforeach;?>
            <td width="<?=$arResult['BUTTON_COLUMN_WIDTH']?>%">Изменить</td>
            <td width="<?=$arResult['BUTTON_COLUMN_WIDTH']?>%">Удалить</td>
            </tr>
            <?foreach ($arResult['TABLE_DATA'] as $id => $element) :?>
                <tr>
                <?foreach ($arResult['TABLE_HEADER'] as $fieldName => $headerElement) :?>
                    <td width="<?=$headerElement['WIDTH']?>%" class="<?=$headerElement['CLASS']?>">
                        <?if (isset($headerElement['VALUES'])) {
                            if (isset($headerElement['IS_MULTIPLE']) && $headerElement['IS_MULTIPLE']) {
                                $value = '';
                                if (is_array($element[$fieldName]) && !empty($element[$fieldName])) {
                                    foreach ($headerElement['VALUES'] as $key => $fieldValue) {
                                        if (in_array($key, $element[$fieldName])) {
                                            $value .= ((!empty($value)) ? ', ' : '') . $fieldValue;
                                        }
                                    }
                                } else {
                                    $value = $element[$fieldName];
                                }
                            } else {
                                if (isset($headerElement['VALUES'][$element[$fieldName]])) {
                                    $value = $headerElement['VALUES'][$element[$fieldName]];
                                } elseif (isset($headerElement['DEFAULT_VALUE'])) {
                                    $value = $headerElement['DEFAULT_VALUE'];
                                } else {
                                    $value = $element[$fieldName];
                                }
                            }
                            echo $value;
                        } else {
                            echo $element[$fieldName];
                        }
                        ?>
                    </td>
                <?endforeach;?>
                    <td width="<?=$arResult['BUTTON_COLUMN_WIDTH']?>%"><button type="button" class="btn btn-primary update-btn change px-1" data-element-id="<?=$id?>">Изменить</button></td>
                    <td width="<?=$arResult['BUTTON_COLUMN_WIDTH']?>%"><button type="button" class="btn btn-primary delete-btn delete px-1" data-element-id="<?=$id?>">Удалить</button></td>
                </tr>
            <?endforeach;?>
        </table>
    <?endif;?>
    </article>
</div>
<?if (!$arResult['TABLE_ONLY']) :?>
<!-- Change Modal -->
<div class="modal fade" id="update-popup" tabindex="-1" role="dialog" aria-labelledby="update-popup-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title p-0" id="update-popup-title">Изменить запись</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="update-popup-close-btn">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" id="update-form" enctype="application/x-www-form-urlencoded">
                    <?foreach ($arResult['TABLE_HEADER'] as $fieldName => $headerElement) :?>
                    <div class="form-group <?=($fieldName == 'ID') ? 'd-none' : ''?>">
                        <label for="<?=$fieldName?>" class="col-form-label"><?=$headerElement['NAME']?></label>
                        <? if (isset($headerElement['VALUES'])) : ?>
                            <select class="form-control" name="<?=$fieldName?>" id="update-<?=$fieldName?>" <?if (isset($headerElement['IS_MULTIPLE'])) echo 'multiple'?>>
                                <?foreach ($headerElement['VALUES'] as $value => $description) :?>
                                <option data-type="multiple" value="<?=$value?>"><?=$description?></option>
                                <?endforeach;?>
                            </select>
                        <? else : ?>
                            <input type="text" name="<?=$fieldName?>" id="update-<?=$fieldName?>" class="form-control" placeholder="<?=$headerElement['NAME']?>" required>
                        <?endif;?>
                    </div>
                    <?endforeach;?>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" id="submit-update-btn">Изменить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add-popup" tabindex="-1" role="dialog" aria-labelledby="add-popup-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title p-0" id="add-popup-title">Добавить запись</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="add-popup-close-btn">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" id="add-form" enctype="application/x-www-form-urlencoded">
                    <?foreach ($arResult['TABLE_HEADER'] as $fieldName => $headerElement) :?>
                        <?if ($fieldName == 'ID') continue;?>
                        <div class="form-group">
                            <label for="<?=$fieldName?>" class="col-form-label"><?=$headerElement['NAME']?></label>
                            <? if (isset($headerElement['VALUES'])) : ?>
                            <select class="form-control" name="<?=$fieldName?>" id="update-<?=$fieldName?>" <?if (isset($headerElement['IS_MULTIPLE'])) echo 'multiple'?>>
                                <?foreach ($headerElement['VALUES'] as $value => $description) :?>
                                    <option value="<?=$value?>"><?=$description?></option>
                                <?endforeach;?>
                            </select>
                            <? else : ?>
                            <input type="text" name="<?=$fieldName?>" id="update-<?=$fieldName?>" class="form-control" placeholder="<?=$headerElement['NAME']?>" required>
                            <?endif;?>
                        </div>
                    <?endforeach;?>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" id="submit-add-btn">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Delete Modal -->
<div class="modal fade" id="delete-popup" tabindex="-1" role="dialog" aria-labelledby="delete-popup-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Удалить элемент</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="delete-popup-close-btn">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="modal-title">Вы точно хотите удалить этот элемент?</h5>
                <form class="form" id="delete-form" enctype="application/x-www-form-urlencoded">
                    <input type="text" name="ID" id="delete-ID" class="form-control d-none" required>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-group-lg m-md-auto" id="submit-delete-btn">Да</button>
                <button class="btn btn-secondary btn-group-lg m-md-auto" data-dismiss="modal" id="cancel-delete-btn">Нет</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        let tableList = TableList.getInstance(
            "<?=str_replace('\\', '\\\\', $arResult['ENTITY_TABLE_CLASS']);?>",
            "<?=array_key_first($arResult['TABLE_SORT'])?>",
            "<?=array_pop($arResult['TABLE_SORT'])?>"
        );
        tableList.initialize();
    });
</script>
<?endif;?>
<script>
    $(document).ready(function () {
        document.addEventListener('Authorization::Success', function () {
            location.reload();
        });
    });
</script>
