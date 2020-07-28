<?
use core\component\tableList\TableListComponent;
use core\lib\table\InstituteTable;
?>

<? /** @var array $arResult*/?>

<? $component = new TableListComponent(
    array(
        'TABLE_NAME' => $arResult['TABLE_NAME'],
        'TABLE_HEADER' => $arResult['TABLE_HEADER'],
        'TABLE_DATA' => $arResult['TABLE_DATA'],
        'TABLE_SORT' => isset($arResult['TABLE_SORT']) ? $arResult['TABLE_SORT'] : null,
        'TABLE_ONLY' => $arResult['TABLE_ONLY'],
        'ENTITY_TABLE_CLASS' => static::class
    )
);
$component->processComponent();
?>