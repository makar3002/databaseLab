<?php
namespace core\component\profile;
use core\component\general\BaseComponent;
use core\orm\UserTable;
use core\util\Request;
use core\util\User;
use core\util\Validator;


class  ProfileComponent extends BaseComponent
{
    public function processComponent()
    {
        $userInstance = User::getInstance();
        $isUserAuthorized = $userInstance->isUserAuthorized();
        if ($isUserAuthorized) {
            $user = UserTable::getById($userInstance->getId());
            $this->arResult = array_merge($this->arResult, $user);
        }
        $this->arResult['IS_USER_AUTHORIZED'] = $isUserAuthorized;
        $this->arResult['IS_AJAX_REQUEST'] = Request::isAjaxRequest();

        $this->renderComponent();
    }

    public function saveProfileAction()
    {
        $userInstance = User::getInstance();
        $isUserAuthorized = $userInstance->isUserAuthorized();
        if (!$isUserAuthorized) {
            echo 'Вы не авторизованы.';
            return;
        }

        $userId = $userInstance->getId();
        $arUpdateUser = array(
            'NAME' => $this->arParams['name'],
            'LAST_NAME' => $this->arParams['last_name'],
            'SECOND_NAME' => $this->arParams['second_name'],
            'EMAIL' => $this->arParams['email']
        );

        if (!empty($this->arParams['password'])) {
            if (!Validator::checkPasswordFormat($this->arParams['password'])) {
                echo 'Длина пароля должна быть не меньще 6 символов.';
                return;
            }

            $password = password_hash($this->arParams['password'],PASSWORD_DEFAULT);
            $arUpdateUser['PASSWORD'] = $password;
        }



        $newUser = UserTable::update($userId, $arUpdateUser);
        if (!$newUser) {
            echo 'Пользователь с такой почтой уже зарегистрирован.';
        }
    }

    public function getProfileFormAction()
    {
        $this->processComponent();
    }


}
