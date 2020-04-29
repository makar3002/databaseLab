<?php
namespace Core\Component\Authorization;
use core\ORM\UserTable;
use mysql_xdevapi\Exception;
use Validator;

require_once($_SERVER['DOCUMENT_ROOT'].'/core/util/validator.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/component/general/basecomponent.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/ORM/usertable.php');

class AuthorizationComponent extends \BaseComponent
{
    public function processComponent()
    {
        $this->arResult['IS_USER_AUTHORIZED'] = \User::isUserAuthorized();
        $this->renderComponent();
    }

    public function signInAction()
    {
        if (!isset($this->arParams['email']) || !isset($this->arParams['password'])) {
            echo 'Не заполнены обязательные поля';
        }

        try {
            \User::authorizeUserByData($this->arParams['email'], $this->arParams['password']);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function signUpAction()
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

    public function signOutAction()
    {
        \User::logoutUser();
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
