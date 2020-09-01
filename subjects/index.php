<?
use core\component\subjectlist\SubjectListComponent;

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/template/header.php');?>
<?
$component = new SubjectListComponent(array());
$component->processComponent();
?>
<? require_once($_SERVER['DOCUMENT_ROOT'] . '/core/template/footer.php');?>