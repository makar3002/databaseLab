<?php
namespace core\component\profile;


use core\lib\ajax\AjaxControllable;


interface ProfileAjaxControllable extends AjaxControllable {
    public function saveProfileAction();
    public function getProfileFormAction();
}
