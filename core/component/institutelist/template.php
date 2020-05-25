<?
use Core\Component\TableList\TableListComponent;
use core\orm\InstituteTable;

?>

<? /** @var array $arResult*/?>

<? $component = new TableListComponent(
    array(
        'TABLE_NAME' => $arResult['TABLE_NAME'],
        'TABLE_HEADER' => $arResult['TABLE_HEADER'],
        'TABLE_DATA' => $arResult['TABLE_DATA'],
        'TABLE_ONLY' => $arResult['TABLE_ONLY'],
        'ENTITY_TABLE_CLASS' => static::class,
        'ENTITY_CLASS' => InstituteTable::class
    )
);
$component->processComponent();
?>