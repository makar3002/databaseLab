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

    public function renderComponent()
    {
        $arParams = $this->arParams;
        $arResult = $this->arResult;

        ob_start();
        require_once($this->templatePath);
        echo ob_get_clean();
    }

    protected function getTemplatePath()
    {
        $cl = new ReflectionClass(get_class($this));
        return dirname($cl->getFileName()) . '/template.php';
    }

    abstract function processComponent();
}
