<?php

require_once 'conexaoDefine.php';

class Conexao
{

    private static $pdo;

    private function __construct()
    {
    }

    public static function getInstance()
    {

        if (!isset(self::$pdo)) {

            try {
                $opcoes = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8', PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
                self::$pdo = new PDO("mysql:host=" . HOST . "; dbname=" . DBNAME . "; charset=" . CHARSET . ";", USER, PASSWORD, $opcoes);
            } catch (PDOException $e) {
                print "Erro: " . $e->getMessage();
            }

        }

        return self::$pdo;

    }

}
