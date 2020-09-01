<?php
namespace core\lib\ajax;


require_once($_SERVER['DOCUMENT_ROOT'] . '/core/util/loader.php');


ob_start();
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo 'Неверный метод запроса.';
    $result = ob_get_clean();
    echo json_encode($result);
    return;
}
if (!isset($_POST['action']) || !isset($_POST['componentClass'])) {
    echo 'Не указано действие или класс компонента.';
    $result = ob_get_clean();
    echo json_encode($result);
    return;
}

$componentClass = $_POST['componentClass'];
if (!is_subclass_of($componentClass, AjaxControllable::class)) {
    echo 'Указанный класс не является ajax-контроллером.';
    $result = ob_get_clean();
    echo json_encode($result);
    return;
}

$action = $_POST['action'] . 'Action';
$reflectionClass = new \ReflectionClass($componentClass);
$componentInterfaces = $reflectionClass->getInterfaces();
$hasControllerMethod = false;
foreach ($componentInterfaces as $interfaceName => $reflectionInterface) {
    if (!method_exists($interfaceName, $action)) {
        continue;
    }

    $hasControllerMethod = true;
    break;
}

if (!$hasControllerMethod) {
    echo 'Указанный класс может выполнить указанное действие.';
    $result = ob_get_clean();
    echo json_encode($result);
    return;
}

$componentObject = new $componentClass($_POST);
$result = $componentObject->$action();
if (!isset($result)) {
    $result = ob_get_clean();
}
echo json_encode($result);
return;