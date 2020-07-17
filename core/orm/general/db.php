<?php
namespace Core\Orm\General;
use PDO;
use PDOException;
use PDOStatement;

class DB {
    /**
     * @var DB - объект класса
     */
    protected static $instance;
    /**
     * @var PDO - объект класса PDO для работы с БД.
     */
    protected $pdoInstance;
    /**
     * @var PDOStatement - объект выражения обращения к БД.
     */
    protected $pdoStatementInstance;
    
    protected function __construct () {
    }

    protected function __clone () {
    }
    
    public static function getInstance()
    {
        if (empty(self::$instance))
        {

            //TODO: Вынести конфиг в отдельный класс, наследующийся от интерфейса конфига БД,
            // и передавать его как объект при создании инстанса.
            $connectionConfig = array(
                'HOST' => 'localhost',
                'DATABASE_NAME' => 'schedule',
                'USER' => 'root',
                'PASSWORD' => '');

            self::$instance = new DB();
            self::$instance->init($connectionConfig);
            self::$instance->query('SET NAMES utf8');
            self::$instance->query('SET CHARACTER SET utf8');
        }

        return self::$instance;
    }

    protected function init($connectionConfig) {
        $databaseConfig = 'mysql:host='.$connectionConfig['HOST'].';dbname='.$connectionConfig['DATABASE_NAME'];
        $user = $connectionConfig['USER'];
        $password = $connectionConfig['PASSWORD'];
        $this->pdoInstance = new PDO($databaseConfig, $user, $password);
    }

    protected function query($query)
    {
        $this->pdoInstance->query($query);
    }

    public function prepare($query)
    {
        $this->pdoStatementInstance = $this->pdoInstance->prepare($query);
    }

    public function execute()
    {
        if (!isset($this->pdoStatementInstance)) {
            throw new \RuntimeException('Не выполена подготовка запроса.');
        }
        return $this->pdoStatementInstance->execute();
    }

    public function fetch()
    {
        return $this->pdoStatementInstance->fetch();
    }

    public function fetchAll()
    {
        return $this->pdoStatementInstance->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastInsertId()
    {
        return $this->pdoInstance->lastInsertId();
    }

    public function getError()
    {
        return $this->pdoStatementInstance->errorInfo();
    }
}
?>
