<? /** @var array $arResult*/?>
<? /** @var array $arParams*/?>

<article class="m-5 entry">
<?if ($arResult['IS_DATA_EMPTY']) :?>
    <h5><?=$arResult['EMPTY_DATA_TITLE']?></h5>
<?else :?>
    <table class="table">
        <caption>
            <h5><?=$arParams['TABLE_NAME']?></h5>
        </caption>
        <tr>
        <?foreach ($arResult['TABLE_HEADER'] as $headerElement) :?>
            <td width="<?=$headerElement['WIDTH']?>%" class="<?=$headerElement['CLASS']?>"><?=$headerElement['NAME']?></td>
        <?endforeach;?>
        <td class="update-btn" width="<?=$arResult['BUTTON_COLUMN_WIDTH']?>%">Изменить</td>
        <td class="delete-btn" width="<?=$arResult['BUTTON_COLUMN_WIDTH']?>%">Удалить</td>
        </tr>
        <?foreach ($arResult['TABLE_DATA'] as $id => $element) :?>
            <tr>
            <?foreach ($arResult['TABLE_HEADER'] as $fieldName => $headerElement) :?>
                <td width="<?=$headerElement['COLUMN_WIDTH']?>%" class="<?=$headerElement['COLUMN_CLASS']?>"><?=$element[$fieldName]?></td>
            <?endforeach;?>
                <td width="<?=$arResult['BUTTON_COLUMN_WIDTH']?>%"><button type="button" class="btn btn-primary change p-0" data-element-id="<?=$id?>" data-action="institute-change">Изменить</button></td>
                <td width="<?=$arResult['BUTTON_COLUMN_WIDTH']?>%"><button type="button" class="btn btn-primary delete p-0" data-element-id="<?=$id?>" data-action="institute-change">Удалить</button></td>
            </tr>
        <?endforeach;?>
    </table>
<?endif;?>
</article>