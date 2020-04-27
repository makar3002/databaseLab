<?php
if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    ob_start();
    var_dump($_SERVER);
    $result = json_encode(ob_get_clean());
    return $result;
}