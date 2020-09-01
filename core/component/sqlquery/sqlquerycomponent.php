<?php
namespace core\component\sqlquery;

use core\component\general\BaseComponent;
use core\lib\db\DB;

class  SqlQueryComponent extends BaseComponent
{
    public function processComponent()
    {
        $this->renderComponent();
    }

    public function executeQueryAction()
    {
        $db = DB::getInstance();
        $db->prepare($this->arParams['QUERY']);
        $result = $db->execute();
        $resultList = $result->fetchAll();
        ob_start();
        var_dump($result);
        $this->arResult['QUERY_RESULT'] = ob_get_clean();
        if ($result) {
            $this->arResult['QUERY_RESULT'] .= '<br>Успешно!';
        } else {
            $this->arResult['QUERY_RESULT'] = 'Произошла ошибка';
        }
        $this->processComponent();
    }
}
