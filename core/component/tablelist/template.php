<? /** @var array $arResult*/

use Core\Component\TableList\TableListComponent;
use core\orm\InstituteTable; ?>
<? /** @var array $arParams*/?>

<?if (!$arResult['TABLE_ONLY']) :?>
<button type="button" class="btn btn-primary add-btn p-3">
    Добавить элемент
</button>
<div id="table-list">
<?endif;?>
    <article class="m-5 entry">
    <?if ($arResult['IS_DATA_EMPTY']) :?>
        <h5><?=$arResult['EMPTY_DATA_TITLE']?></h5>
    <?else :?>
        <table class="table">
            <caption>
                <h5><?=$arParams['TABLE_NAME']?></h5>
            </caption>
            <tr>
            <?foreach ($arResult['TABLE_HEADER'] as $fieldName => $headerElement) :?>
                <td width="<?=$headerElement['WIDTH']?>%" class="<?=$headerElement['CLASS']?>">
                    <div class="td-header" data-field-name="<?=$fieldName?>"<?= (isset($arResult['TABLE_SORT'][$fieldName])) ? 'data-sort="' . $arResult['TABLE_SORT'][$fieldName] . '"' : ''?>>
                        <span><?=$headerElement['NAME']?></span>
                        <? if (isset($arResult['TABLE_SORT'][$fieldName])) : ?>
                            <span class="arrow-<?=$arResult['TABLE_SORT'][$fieldName]?>"></span>
                        <?endif;?>
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
                            if (isset($headerElement['IS_MULTIPLE']) && $headerElement['IS_MULTIPLE'] && is_array($element[$fieldName])) {
                                $value = '';
                                foreach ($headerElement['VALUES'] as $key => $fieldValue) {
                                    if (in_array($key, $element[$fieldName])) {
                                        $value .= ((!empty($value)) ? ', ' : '') . $fieldValue;
                                    }
                                }
                            } else {
                                $value = $headerElement['VALUES'][$element[$fieldName]];
                            }
                            echo $value;
                        } else {
                            echo $element[$fieldName];
                        }
                        ?>
                    </td>
                <?endforeach;?>
                    <td width="<?=$arResult['BUTTON_COLUMN_WIDTH']?>%"><button type="button" class="btn btn-primary update-btn change p-0" data-element-id="<?=$id?>">Изменить</button></td>
                    <td width="<?=$arResult['BUTTON_COLUMN_WIDTH']?>%"><button type="button" class="btn btn-primary delete-btn delete p-0" data-element-id="<?=$id?>">Удалить</button></td>
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
                            <select class="form-control" name="<?=$fieldName?>" id="update-<?=$fieldName?>" <?if ($headerElement['IS_MULTIPLE']) echo 'multiple'?>>
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
                <h5 class="modal-title p-0" id="add-popup-title">Изменить запись</h5>
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
                            <select class="form-control" name="<?=$fieldName?>" id="update-<?=$fieldName?>" <?if ($headerElement['IS_MULTIPLE']) echo 'multiple'?>>
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
        let tableList = new TableList(
            "<?=str_replace('\\', '\\\\', $arResult['ENTITY_CLASS']);?>",
            "<?=str_replace('\\', '\\\\', $arResult['ENTITY_TABLE_CLASS']);?>"
        );
        tableList.initialize();
    });
</script>
<?endif;?>