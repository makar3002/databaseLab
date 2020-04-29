<?php
use Core\Component\Authorization\AuthorizationComponent;
require_once($_SERVER['DOCUMENT_ROOT'].'/core/util/loader.php');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Автомобильный гараж</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/core/template/css/style.css">
    <link rel="stylesheet" href="/core/template/css/bootstrap/bootstrap.css">
    <script src="/core/template/js/jquery/jquery-3.4.1.min.js"></script>
    <script src="/core/template/js/util/popper.min.js"></script>
    <script src="/core/template/js/bootstrap/bootstrap.min.js"></script>
    <script src="/core/template/js/util/validator.js"></script>
    <script src="/core/template/js/authorization/authorization.js"></script>
    <script src="/core/template/js/util/ajax.js"></script>
</head>
<body>
<div id="header">
    <header class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 border-bottom page-header">
        <a class="main-label my-0 mr-md-auto font-weight-normal text-light" href = "index.php">ГаражQ</a>
        <nav class="my-2 my-md-0 mx-md-auto">
            <a class="p-2 text-light" href="marks.php">Марки</a>
            <a class="p-2 text-light" href="owners.php">Владельцы</a>
            <a class="p-2 text-light" href="list_of_security.php">Список сторожей</a>
            <a class="p-2 text-light" href="journal.php">Журнал</a>
        </nav>
        <div class="my-md-0 ml-md-auto d-flex align-items-center">
            <?
            $component = new AuthorizationComponent(array());
            echo $component->processComponent();
            ?>
        </div>
    </header>
</div>
<main class="pt-5">