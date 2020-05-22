<?
use Core\Component\TableList\TableListComponent;
?>

<? /** @var array $arResult*/?>

<? $component = new TableListComponent(
    array(
        'TABLE_NAME' => $arResult['TABLE_NAME'],
        'TABLE_HEADER' => $arResult['TABLE_HEADER'],
        'TABLE_DATA' => $arResult['TABLE_DATA']
    )
);
$component->processComponent();
?>