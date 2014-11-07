<?php

/**
 *		DB class
 *		Работа с базой
 *      В базе должна быть таблица cache, в которой должны быть три столбца: id, json и hit.
 *      Структура таблицы:
 *      CREATE TABLE IF NOT EXISTS `cache` (
 *        `id` int(11) NOT NULL COMMENT 'id фильма на кинопоиске',
 *        `json` text NOT NULL COMMENT 'json, который надо послать',
 *        `hit` int(11) NOT NULL DEFAULT '0' COMMENT 'сколько раз дёрнули из кеша',
 *        PRIMARY KEY (`id`)
 *      ) ENGINE=MyISAM DEFAULT CHARSET=cp1251 COMMENT='Кеш парсера кинопоиска';
 */

// database connection settings
define("DB_NAME","kinopoisk");
define("DB_USER","pistonsky");
define("DB_PASSWORD","");
define("DB_HOST","localhost");
define("DB_PORT","5432");

class DB {

    private static $instance = null; // singleton pattern

	private $DBHandle; // database handle

	private function __construct () 
	{
			// database handle and settings
			try {
				$this->DBHandle = new PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, DB_USER, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
			} catch (PDOException $e) {
				echo 'Connection failed: ' . $e->getMessage();
			}
	}

    public static function getInstance ()
    {
        if (self::$instance == null)
            self::$instance = new DB();
        return self::$instance;
    }

    public static function query($sql)
    {
        if (self::$instance == null)
            self::$instance = new DB();

        $data = self::$instance->DBHandle->query($sql);
        if (strpos($sql, 'INSERT') === 0) {
            return self::$instance->DBHandle->lastInsertId();
        } else if (is_object($data)) {
            $result = $data->fetchAll();
            if (count($result) > 0)
                return $result;
            else
                return false;
        } else {
            return false;
        }
    }
}