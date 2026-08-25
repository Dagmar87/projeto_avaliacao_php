<?php

class Database
{

	private static $instance = null;
	private $conn;

	private $host = 'localhost';
	private $dbname = 'jm_informatica';
	private $username = 'root';
	private $password = '';

	private function __construct()
	{
		try {
			$this->conn = new PDO(
				"mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8mb4",
				$this->username,
				$this->password,
				[
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
				]
			);
		} catch (PDOException $e) {
			die("Erro de conexão com o banco de dados: " . $e->getMessage());
		}
	}

	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new Database();
		}
		return self::$instance->conn;
	}
}
