<?php
namespace core\component\authorization;
use core\component\general\BaseComponent;
use core\orm\UserTable;
use core\util\Request;
use core\util\User;
use core\util\Validator;
use Exception;


class AuthorizationComponent extends BaseComponent
{
    public function processComponent()
    {
        $userInstance = User::getInstance();
        $isUserAuthorized = $userInstance->isUserAuthorized();
        $this->arResult['IS_USER_AUTHORIZED'] = $isUserAuthorized;
        $this->arResult['IS_AJAX_REQUEST'] = Request::isAjaxRequest();

        $this->renderComponent();
    }

    public function getSignInUpOutButtonsAction()
    {
        $this->processComponent();
    }

    public function signInAction()
    {
        if (!isset($this->arParams['email']) || !isset($this->arParams['password'])) {
            echo 'Не заполнены обязательные поля';
        }

        try {
            User::getInstance()->authorizeUserByData($this->arParams['email'], $this->arParams['password']);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function signUpAction()
    {
        $userData = array();
        try {
            $userData = $this->checkAndGetUserData();
        } catch (Exception $exception) {
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
        User::getInstance()->logoutUser();
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
