<?
use core\component\auditoriumlist\AuditoriumListComponent;

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/template/header.php');?>
<?
$component = new AuditoriumListComponent(array());
$component->processComponent();
?>
<? require_once($_SERVER['DOCUMENT_ROOT'] . '/core/template/footer.php');?>