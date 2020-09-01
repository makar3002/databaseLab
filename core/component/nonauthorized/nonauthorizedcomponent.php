<?php
namespace core\component\nonauthorized;

use core\component\general\BaseComponent;

class NonAuthorizedComponent extends BaseComponent
{
    public function processComponent()
    {
        $this->renderComponent();
    }
}
