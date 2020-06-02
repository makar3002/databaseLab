<?php
namespace Core\Component\General;

abstract class BaseComponent
{
    protected $arParams;
    protected $arResult;
    protected $templatePath;

    public function __construct($arParams)
    {
        $this->arParams = $arParams;
        $this->arResult = array();
        $this->templatePath = $this->getTemplatePath();
    }

    protected function renderComponent()
    {
        $arParams = $this->arParams;
        $arResult = $this->arResult;

        ob_start();
        if (file_exists($this->templatePath)) {
            require_once($this->templatePath);
        }
        $result = ob_get_clean();
        echo $result;
    }

    protected function getTemplatePath()
    {
        $cl = new \ReflectionClass(get_class($this));
        return dirname($cl->getFileName()) . '/template.php';
    }

    public function processComponent()
    {
        $this->renderComponent();
    }
}
