<?php
abstract class BaseComponent
{
    protected $arParams;
    protected $arResult;
    protected $templatePath;

    public function __construct($arParams)
    {
        $this->arParams = $arParams;
        $this->templatePath = $this->getTemplatePath();
    }

    protected function renderComponent()
    {
        $arParams = $this->arParams;
        $arResult = $this->arResult;

        require_once($this->templatePath);
    }

    protected function getTemplatePath()
    {
        $cl = new ReflectionClass(get_class($this));
        return dirname($cl->getFileName()) . '/template.php';
    }

    public function processComponent()
    {
        ob_start();
        $this->renderComponent();
        return ob_get_clean();
    }
}
