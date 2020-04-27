<?php
namespace Core\Ajax;
use Core\Component\Authorization\AuthorizationComponent;
require_once($_SERVER['DOCUMENT_ROOT'].'/core/component/authorization/class.php');

ob_start();
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo 'Неверный метод запроса.';
    $result = ob_get_clean();
    echo json_encode($result);
}

if ($_POST['action'] == 'getSignInUpOutButtons') {
    $component = new AuthorizationComponent($_POST);
    $component->processComponent();
}

if ($_POST['action'] == 'signIn') {
    $component = new AuthorizationComponent($_POST);
    $component->authorizeUserByData();
}

if ($_POST['action'] == 'signUp') {
    $component = new AuthorizationComponent($_POST);
    $component->registerUserByData();
}

if ($_POST['action'] == 'signOut') {
    $component = new AuthorizationComponent($_POST);
    $component->logoutUser();
}

$result = ob_get_clean();
echo json_encode($result);