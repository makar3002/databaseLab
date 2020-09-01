<?php
namespace core\component\authorization;

use core\component\general\BaseComponent;
use core\util\Request;
use core\lib\facade\User;
use core\util\Validator;
use Exception;

class AuthorizationComponent extends BaseComponent implements AuthorizationAjaxControllable
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
        try {
            $userData = $this->checkAndGetUserData();
            User::getInstance()->registerUserByData($userData);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
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
