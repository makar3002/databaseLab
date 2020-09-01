<?

use core\component\scheduleelementlist\ScheduleElementListComponent;

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/template/header.php');?>
<?
$component = new ScheduleElementListComponent(array());
$component->processComponent();
?>
<? require_once($_SERVER['DOCUMENT_ROOT'] . '/core/template/footer.php');?>