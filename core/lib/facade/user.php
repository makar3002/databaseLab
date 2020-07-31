<?php
namespace core\lib\facade;

use core\lib\table\UserTable;
use core\util\Right;

class User {
    private $session;
    private static $instance;
    private $right;
    private $user;
    private function __construct()
    {
        session_start();
        $this->session = &$_SESSION;
        if (isset($this->session['USER_ID'])) {
            $this->user = UserTable::getList(array(
                'filter' => array('ID' => $this->session['USER_ID'])
            ))[0];
            $this->right = new Right($this->user['GROUP_IDS']);
        }
    }

    public static function getInstance()
    {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getUserInfo()
    {
        if(isset($this->user)) {
            return $this->user;
        } else {
            return array();
        }
    }

    public function updateUserInfo($userId, $fields)
    {
        if(isset($fields['PASSWORD'])) {
            $password = password_hash($fields['PASSWORD'],PASSWORD_DEFAULT);
            $fields['PASSWORD'] = $password;
        }

        return UserTable::update($userId, $fields);
    }

    public function registerUserByData($userData)
    {
        $userList = UserTable::getList(array(
            'filter' => array('=EMAIL' => $userData['email'])
        ));

        $userCount = count($userList);
        if ($userCount != 0) {
            throw new \RuntimeException('Пользователь с такой почтой уже зарегистрирован');
        }

        $password = password_hash($userData['password'],PASSWORD_DEFAULT);
        UserTable::add(array('EMAIL' => $userData['email'], 'PASSWORD' => $password));
    }

    public function authorizeUserByData($email, $password)
    {
        $userList = UserTable::getList(array(
            'filter' => array('=EMAIL' => $email)
        ));

        $userCount = count($userList);
        if ($userCount == 0) {
            throw new \RuntimeException('Ошибка, неверный email или пароль');
        } else if ($userCount > 1) {
            throw new \RuntimeException('Ошибка, что-то пошло не так, повторите попытку позже');
        }

        $user = $userList[0];
        if (password_verify($password, $user['PASSWORD']))
        {
            $this->setupSessionWithUserId($user['ID']);
        } else {
            throw new \RuntimeException('Ошибка, неверный email или пароль');
        }
    }

    public function authorizeUserById($userId)
    {
        $userList = UserTable::getById($userId);
        $userCount = count($userList);
        if ($userCount == 0) {
            throw new \RuntimeException('Пользователя с таким id не существует.');
        }
        $this->setupSessionWithUserId($userId);
    }

    public function getRights()
    {
        return $this->right;
    }

    public function logoutUser()
    {
        $this->destroySession();
    }

    public function isUserAuthorized()
    {
        $this->getSessionIfNeeded();
        if (!isset($this->session['USER_ID'])) {
            return false;
        }

        $userCount = count(UserTable::getById($this->session['USER_ID']));
        return boolval($userCount);
    }

    public function getId()
    {
        $this->getSessionIfNeeded();
        if (!isset($this->session['USER_ID'])) {
            return 0;
        }

        return $this->session['USER_ID'];
    }

    private function setupSessionWithUserId($id) {
        $this->getSessionIfNeeded();
        $this->session['USER_ID'] = $id;
    }

    private function destroySession() {
        $this->getSessionIfNeeded();
        session_destroy();
        unset($this->session);
    }

    private function getSessionIfNeeded()
    {
        if (!isset($this->session)) {
            $this->session = session_start();
        }
    }
}