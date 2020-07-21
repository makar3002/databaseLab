<?php
use core\component\authorization\AuthorizationComponent;

require_once($_SERVER['DOCUMENT_ROOT'].'/core/util/loader.php');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Расписание</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/core/template/css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="/core/template/css/style.css">
    <link rel="stylesheet" href="/core/template/css/tablelist.css">
    <link rel="stylesheet" href="/core/template/css/schedule.css">
    <link rel="stylesheet" href="/core/template/css/feedbackform.css">
    <script src="/core/template/js/jquery/jquery-3.4.1.min.js"></script>
    <script src="/core/template/js/util/popper.min.js"></script>
    <script src="/core/template/js/bootstrap/bootstrap.min.js"></script>
    <script src="/core/template/js/util/validator.js"></script>
    <script src="/core/template/js/authorization/authorization.js"></script>
    <script src="/core/template/js/util/ajax.js"></script>
    <script src="/core/template/js/profile/profile.js"></script>
    <script src="/core/template/js/sqlquery/sqlquery.js"></script>
    <script src="/core/template/js/tablelist/tablelist.js"></script>
    <script src="/core/template/js/schedule/schedule.js"></script>
</head>
<body>
<!--<div id="header">-->
    <header class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 border-bottom page-header">
        <a class="main-label my-0 mr-md-auto font-weight-normal text-light" href = "/">Расписание</a>
        <nav class="my-2 my-md-0 mx-md-auto">
            <a class="p-2 text-light" href="/institutes">Институты</a>
            <a class="p-2 text-light" href="/directions">Направления</a>
            <a class="p-2 text-light" href="/groups">Группы</a>
            <a class="p-2 text-light" href="/teachers">Преподаватели</a>
            <a class="p-2 text-light" href="/auditoriums">Аудитории</a>
            <a class="p-2 text-light" href="/subjects">Предметы</a>
            <a class="p-2 text-light" href="/scheduleelements">Пары</a>
        </nav>
        <div class="my-md-0 ml-md-auto d-flex align-items-center">
            <?
            $component = new AuthorizationComponent(array());
            $component->processComponent();
            ?>
        </div>
    </header>
<!--</div>-->
    <main id="main" class="pt-3 text-center">