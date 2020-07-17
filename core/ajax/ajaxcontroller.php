<?php
namespace core\Ajax;

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
$result = $componentObject->$action();
if (!isset($result)) {
    $result = ob_get_clean();
}
echo json_encode($result);