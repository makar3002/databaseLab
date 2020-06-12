<?
use Core\Component\Schedule\ScheduleComponent;

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/template/header.php');?>
<?
$component = new ScheduleComponent(array());
$component->processComponent();
?>
<?require_once($_SERVER['DOCUMENT_ROOT'] . '/core/template/footer.php');?>