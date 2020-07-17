<?php
namespace core\util;

class Request {
    public static function isAjaxRequest()
    {
        if (!self::isPost()) {
            return false;
        }

        return isset($_POST['AJAX_REQUEST']);
    }

    public static function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }
}
