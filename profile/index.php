<? use Core\Component\profile\ProfileComponent;

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/template/header.php');?>
    <!--Content here-->
<?
$component = new ProfileComponent(array());
$component->processComponent();
?>
<?require_once($_SERVER['DOCUMENT_ROOT'] . '/core/template/footer.php');?>