<?php
namespace Core\Util;
use Core\Orm\UserTable;

class User {
    private $session;
    private static $instance;
    private function __construct()
    {
        session_start();
        $this->session = &$_SESSION;
    }

    public static function getInstance()
    {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
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