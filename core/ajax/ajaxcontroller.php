<?php
namespace core\ajax;
require_once($_SERVER['DOCUMENT_ROOT'].'/core/util/loader.php');

ob_start();
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo 'Неверный метод запроса.';
    $result = ob_get_clean();
    echo json_encode($result);
}
if (!isset($_POST['action']) || !isset($_POST['componentClass'])) {
    echo 'Не указано действие.';
    $result = ob_get_clean();
    echo json_encode($result);
}

$action = $_POST['action'] . 'Action';
$componentClass = $_POST['componentClass'];
$componentObject = new $componentClass($_POST);
$componentObject->$action();

//if ($_POST['action'] == 'getSignInUpOutButtons') {
//    $component = new AuthorizationComponent($_POST);
//    $component->processComponent();
//}
//
//if ($_POST['action'] == 'signIn') {
//    $component = new AuthorizationComponent($_POST);
//    $component->$action();
//}
//
//if ($_POST['action'] == 'signUp') {
//    $component = new AuthorizationComponent($_POST);
//    $component->registerUser();
//}
//
//if ($_POST['action'] == 'signOut') {
//    $component = new AuthorizationComponent($_POST);
//    $component->logoutUser();
//}

$result = ob_get_clean();
echo json_encode($result);