<?

use Core\Component\AuditoriumList\AuditoriumListComponent;
use Core\Component\TeacherList\TeacherListComponent;

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/template/header.php');?>
<?
$component = new AuditoriumListComponent(array());
$component->processComponent();
?>
<? require_once($_SERVER['DOCUMENT_ROOT'] . '/core/template/footer.php');?>