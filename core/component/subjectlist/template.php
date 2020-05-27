<?
use Core\Component\TableList\TableListComponent;
use core\orm\SubjectTable;

?>

<? /** @var array $arResult*/?>

<? $component = new TableListComponent(
    array(
        'TABLE_NAME' => $arResult['TABLE_NAME'],
        'TABLE_HEADER' => $arResult['TABLE_HEADER'],
        'TABLE_SORT' => isset($arResult['TABLE_SORT']) ? $arResult['TABLE_SORT'] : null,
        'TABLE_ONLY' => $arResult['TABLE_ONLY'],
        'ENTITY_TABLE_CLASS' => static::class,
        'ENTITY_CLASS' => SubjectTable::class
    )
);
$component->processComponent();
?>