<?php

require_once __DIR__ . '/../config/database.php';

class User {

	private $db;

	public function __construct() {
		$this->db = Database::getInstance();
	}

	public function create($name, $email, $password) {
		$hashPassword = password_hash($password, PASSWORD_BCRYPT);
		$stmt = $this->db->prepare("INSERT INTO user (name, email, password, created_at, update_at, ativo) VALUES (:name, :email, :password, NOW(), NOW(), 1)");
		return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashPassword
        ]);
	}

	public function findByEmail($email) {
		$stmt = $this->db->prepare("SELECT * FROM user WHERE email = :email AND ativo = 1 LIMIT 1");
		$stmt->execute([':email' => $email]);
		return $stmt->fetch();
	}

	public function findById($id) {
		$stmt = $this->db->prepare("SELECT id_user, name, email FROM user WHERE id_user = :id AND ativo = 1 LIMIT 1");
		$stmt->execute([':id' => $id]);
		return $stmt->fetch();
	}

}