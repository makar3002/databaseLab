<?

use Core\Component\DirectionList\DirectionListComponent;

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/template/header.php');?>
<?
$component = new DirectionListComponent(array());
$component->processComponent();
?>
<? require_once($_SERVER['DOCUMENT_ROOT'] . '/core/template/footer.php');?>