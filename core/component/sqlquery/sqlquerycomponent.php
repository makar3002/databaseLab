<?php
namespace Core\Component\SqlQuery;
use Core\Component\General\BaseComponent;
use Core\Orm\General\DB;
use Core\Orm\UserTable;
use Core\Util\Request;
use Core\Util\User;
use Core\Util\Validator;


class  SqlQueryComponent extends BaseComponent
{
    public function processComponent()
    {
        $this->renderComponent();
    }

    public function executeQueryAction()
    {
        $pdo = DB::getInstance();
        $sdh = $pdo->prepare($this->arParams['QUERY']);
        $sdh->execute();
        $result = $sdh->fetchAll(\PDO::FETCH_ASSOC);
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
