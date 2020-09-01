<?php
namespace core\component\authorization;


use core\lib\ajax\AjaxControllable;


interface AuthorizationAjaxControllable extends AjaxControllable {
    public function getSignInUpOutButtonsAction();
    public function signInAction();
    public function signUpAction();
    public function signOutAction();
}
