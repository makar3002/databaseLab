<?php
namespace Core\Component\nonauthorized;

use Core\Component\General\BaseComponent;

class NonAuthorizedComponent extends BaseComponent
{
    public function processComponent()
    {
        $this->renderComponent();
    }
}
