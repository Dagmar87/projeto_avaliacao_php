<?php

require_once __DIR__ . '/../config/database.php';

class Service
{

	private $db;

	public function __construct()
	{
		$this->db = Database::getInstance();
	}

	public function create($description, $price, $userId)
	{
		$stmt = $this->db->prepare("INSERT INTO service (description, price, created_at, update_at, user_id_user) VALUES (:description, :price, NOW(), NOW(), :user_id)");
		return $stmt->execute([
			':description' => $description,
			':price' => $price,
			':user_id' => $userId
		]);
	}

	public function getAll($filters = [])
	{
		$sql = "SELECT s.*, u.name as user_name 
                FROM service s 
                INNER JOIN user u ON s.user_id_user = u.id_user 
                WHERE 1=1";
		$params = [];

		if (!empty($filters['date_initial'])) {
			$sql .= " AND DATE(s.created_at) >= :date_initial";
			$params[':date_initial'] = $filters['date_initial'];
		}
		if (!empty($filters['date_final'])) {
			$sql .= " AND DATE(s.created_at) <= :date_final";
			$params[':date_final'] = $filters['date_final'];
		}

		if (!empty($filters['service_name'])) {
			$sql .= " AND s.description LIKE :service_name";
			$params[':service_name'] = "%" . $filters['service_name'] . "%";
		}

		if (!empty($filters['status'])) {
			if ($filters['status'] === 'PENDENTE') {
				$sql .= " AND s.finished_at IS NULL";
			} elseif ($filters['status'] === 'FINALIZADO') {
				$sql .= " AND s.finished_at IS NOT NULL";
			}
		}

		if (!empty($filters['user_name'])) {
			$sql .= " AND u.name LIKE :user_name";
			$params[':user_name'] = "%" . $filters['user_name'] . "%";
		}

		$sql .= " ORDER BY s.created_at DESC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute($params);
		return $stmt->fetchAll();
	}

	public function getTotalValueByUser($userId)
	{
		$stmt = $this->db->prepare("SELECT SUM(price) as total FROM service WHERE user_id_user = :user_id");
		$stmt->execute([':user_id' => $userId]);
		$row = $stmt->fetch();
		return $row['total'] ?? 0;
	}

	public function getPendingByUser($userId, $limit = 5)
	{
		$stmt = $this->db->prepare("SELECT * FROM service WHERE user_id_user = :user_id AND finished_at IS NULL ORDER BY created_at DESC LIMIT :limit");
		$stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
		$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getRecentServices($limit = 3)
	{
		$stmt = $this->db->prepare("SELECT * FROM service ORDER BY created_at DESC LIMIT :limit");
		$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function findById($id)
	{
		$stmt = $this->db->prepare("SELECT * FROM service WHERE id_service = :id LIMIT 1");
		$stmt->execute([':id' => $id]);
		return $stmt->fetch();
	}

	public function update($id, $description, $price)
	{
		$stmt = $this->db->prepare("UPDATE service SET description = :description, price = :price, update_at = NOW() WHERE id_service = :id");
		return $stmt->execute([
			':description' => $description,
			':price' => $price,
			':id' => $id
		]);
	}

	public function delete($id)
	{
		$stmt = $this->db->prepare("DELETE FROM service WHERE id_service = :id");
		return $stmt->execute([':id' => $id]);
	}

	public function finalize($id, $commission)
	{
		$stmt = $this->db->prepare("UPDATE service SET finished_at = NOW(), commission_user = :commission, update_at = NOW() WHERE id_service = :id");
		return $stmt->execute([
			':commission' => $commission,
			':id' => $id
		]);
	}
}
