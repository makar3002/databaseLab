<?php
namespace core\util;

class Loader
{
    /**
     * Подключает файл по его полному имени.
     * Использует пространство имен для поиска директории, а название класса для поиска файла.
     * Предполагает, что все файлы и директории названы в нижнем регистре.
     *
     * @param $fqn
     */
    public static function load($fqn)
    {
        $fqn = ltrim($fqn, '\\');
        $fqn = strtolower($fqn);
        $fqn = str_replace('\\', DIRECTORY_SEPARATOR, $fqn);

        $absolute = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $fqn . '.php';
        if (file_exists($absolute) && is_file($absolute)) {
            /** @noinspection PhpIncludeInspection */
            require_once ($absolute);
        }
    }
}

spl_autoload_register(array(Loader::class, 'load'));