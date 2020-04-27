<?php
namespace Core\Component\Authorization;
use core\ORM\UserTable;
use Validator;

require_once($_SERVER['DOCUMENT_ROOT'].'/core/util/validator.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/component/general/basecomponent.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/ORM/usertable.php');

class AuthorizationComponent extends \BaseComponent
{
    public function processComponent()
    {
        $this->arResult['IS_USER_AUTHORIZED'] = $this->isUserAuthorized();
        $this->renderComponent();
    }

    private function isUserAuthorized()
    {
        session_start();
        return isset($_SESSION['USER_ID']);
    }

    public function authorizeUserByData()
    {

        $userData = array();
        try {
            $userData = $this->checkAndGetUserData();
        } catch (\Exception $exception) {
            echo $exception->getMessage();
            return;
        }

        $userList = UserTable::getList(array(
            'filter' => array('=EMAIL' => $userData['email'])
        ));

        $userCount = count($userList);
        if ($userCount == 0) {
            echo "Ошибка, неверный email или пароль";
            return;
        } else if ($userCount > 1) {
            echo "Ошибка, что-то пошло не так, повторите попытку позже";
            return;
        }

        $user = $userList[0];
        if (password_verify($userData['password'], $user['PASSWORD']))
        {
            session_start();
            $_SESSION['USER_ID'] = $user['ID'];
        } else {
            echo "Ошибка, неверный email или пароль";
        }
        return;
    }

    public function registerUserByData()
    {
        $userData = array();
        try {
            $userData = $this->checkAndGetUserData();
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }

        $userList = UserTable::getList(array(
            'filter' => array('=EMAIL' => $userData['email'])
        ));

        $userCount = count($userList);
        if ($userCount != 0) {
            echo "Пользователь с такой почтой уже зарегистрирован";
        }

        $password = password_hash($userData['password'],PASSWORD_DEFAULT);
        UserTable::add(array('EMAIL' => $userData['email'], 'PASSWORD' => $password));
    }

    public function logoutUser()
    {
        session_start();
        session_destroy();
    }

    private function checkAndGetUserData()
    {
        if (!isset($this->arParams['email']) || !isset($this->arParams['password'])) {
            throw new \RuntimeException('Не заполнены обязательные поля');
        }

        $email = $this->arParams['email'];
        $password = $this->arParams['password'];
        if (!Validator::checkEmailFormat($email) || !Validator::checkPasswordFormat($password)) {
            throw new \RuntimeException('Не верный формат данных.');
        }

        return array(
            'email' => $email,
            'password' => $password
        );
    }
}
