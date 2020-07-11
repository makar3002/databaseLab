<?php
namespace Core\Orm\General;
use PDO;
use PDOException;
use PDOStatement;

class DB {
    /**
     * @var DB
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
    
    public static function getInstance()
    {
        if (empty(self::$instance))
        {
            $connectionConfig = array(
                'HOST' => 'localhost',
                'DATABASE_NAME' => 'schedule',
                'USER' => 'root',
                'PASSWORD' => '');

            try
            {
                self::$instance = new DB();
                self::$instance->init($connectionConfig);
                self::$instance->query('SET NAMES utf8');
                self::$instance->query('SET CHARACTER SET utf8');

            }
            catch (PDOException $e)
            {
                echo $e->getMessage();
            }
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

    public function getError()
    {
        return $this->pdoStatementInstance->errorInfo();
    }
}
?>
