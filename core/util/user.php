<?php
namespace core\util;
use core\orm\UserTable;

class User {
    public static function validateUserFields($arFields)
    {
        if (!isset($arFields['email']) || !isset($arFields['password'])) {
            throw new \RuntimeException('Не заполнены обязательные поля');
        }

        $email = $arFields['email'];
        $password = $arFields['password'];
        if (!Validator::checkEmailFormat($email) || !Validator::checkPasswordFormat($password)) {
            throw new \RuntimeException('Не верный формат данных.');
        }
    }

    public static function authorizeUserByData($email, $password)
    {
        $userList = UserTable::getList(array(
            'filter' => array('=EMAIL' => $email)
        ));

        $userCount = count($userList);
        if ($userCount == 0) {
            throw new RuntimeException('Ошибка, неверный email или пароль');
        } else if ($userCount > 1) {
            throw new RuntimeException('Ошибка, что-то пошло не так, повторите попытку позже');
        }

        $user = $userList[0];
        if (password_verify($password, $user['PASSWORD']))
        {
            self::setupSessionWithUserId($user['ID']);
        } else {
            throw new RuntimeException('Ошибка, неверный email или пароль');
        }
    }

    public static function authorizeUserById($userId)
    {
        $userList = UserTable::getById($userId);
        $userCount = count($userList);
        if ($userCount == 0) {
            throw new RuntimeException('Пользователя с таким id не существует.');
        }

        self::setupSessionWithUserId($userId);
    }

    public static function logoutUser()
    {
        self::destroySession();
    }

    public static function isUserAuthorized()
    {
        session_start();
        if (!isset($_SESSION['USER_ID'])) {
            return false;
        }

        $userCount = count(UserTable::getById($_SESSION['USER_ID']));
        return boolval($userCount);
    }

    private static function setupSessionWithUserId($id) {
        session_start();
        $_SESSION['USER_ID'] = $id;
    }

    private static function destroySession() {
        session_start();
        session_destroy();
    }
}