<?php
namespace core\component\nonauthorized;
use core\component\general\BaseComponent;
use core\orm\UserTable;
use core\util\User;
use core\util\Validator;
use Exception;


class NonAuthorizedComponent extends BaseComponent
{
    public function processComponent()
    {
        $this->renderComponent();
    }
}
