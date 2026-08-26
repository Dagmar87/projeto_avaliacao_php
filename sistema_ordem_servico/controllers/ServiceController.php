<?php

require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../models/User.php';

class ServiceController
{
	private $serviceModel;
	private $userModel;

	public function __construct()
	{
		if (!isset($_SESSION['user_id'])) {
			header('Location: index.php?action=login');
			exit;
		}
		$this->serviceModel = new Service();
		$this->userModel = new User();
	}

	public function dashboard()
	{
		$userId = $_SESSION['user_id'];

		$filters = [
			'date_initial' => $_GET['date_initial'] ?? '',
			'date_final' => $_GET['date_final'] ?? '',
			'service_name' => $_GET['service_name'] ?? '',
			'status' => $_GET['status'] ?? '',
			'user_name' => $_GET['user_name'] ?? ''
		];

		$services = $this->serviceModel->getAll($filters);
		$totalValueUser = $this->serviceModel->getTotalValueByUser($userId);
		$pendingServicesUser = $this->serviceModel->getPendingByUser($userId, 5);
		$recentServices = $this->serviceModel->getRecentServices(3);

		require __DIR__ . '/../views/dashboard.php';
	}

	public function addService()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$description = trim($_POST['description'] ?? '');
			$price = trim($_POST['price'] ?? '');
			$price = str_replace(['R$', ' ', '.'], '', $price);
			$price = str_replace(',', '.', $price);

			if (empty($description) || empty($price) || !is_numeric($price) || $price <= 0) {
				$_SESSION['error'] = 'Falha ao adicionar novo serviço. Verifique os dados.';
				header('Location: index.php?action=dashboard');
				exit;
			}

			if ($this->serviceModel->create($description, $price, $_SESSION['user_id'])) {
				$_SESSION['success'] = 'Serviço cadastrado com sucesso!';
				header('Location: index.php?action=dashboard');
				exit;
			} else {
				$_SESSION['error'] = 'Falha ao cadastrar serviço.';
				header('Location: index.php?action=dashboard');
				exit;
			}
		}

		require __DIR__ . '/../views/add_service.php';
	}

	public function finalize()
	{
		$id = $_GET['id'] ?? null;
		if (!$id) {
			header('Location: index.php?action=dashboard');
			exit;
		}

		$service = $this->serviceModel->findById($id);
		if (!$service || $service['finished_at'] !== null) {
			$_SESSION['error'] = 'Serviço não encontrado ou já finalizado.';
			header('Location: index.php?action=dashboard');
			exit;
		}

		$price = (float)$service['price'];
		$commissionRate = 0.05;

		if ($price > 10000) {
			$commissionRate = 0.20;
		} elseif ($price > 1000) {
			$commissionRate = 0.10;
		}

		$commission = $price * $commissionRate;

		if ($this->serviceModel->finalize($id, $commission)) {
			$user = $this->userModel->findById($service['user_id_user']);
			if ($user && !empty($user['email'])) {
				$to = $user['email'];
				$subject = "Servico Finalizado - #" . $service['id_service'];
				$message = "Olá " . $user['name'] . ",\n\n";
				$message .= "O serviço '" . $service['description'] . "' foi marcado como FINALIZADO.\n";
				$message .= "Valor: R$ " . number_format($price, 2, ',', '.') . "\n";
				$message .= "Comissão Gerada: R$ " . number_format($commission, 2, ',', '.') . "\n\n";
				$message .= "JM Informática.";
				$headers = "From: no-reply@jminformatica.com\r\n";

				@mail($to, $subject, $message, $headers);
			}

			$_SESSION['success'] = 'Serviço finalizado com sucesso! Comissão calculada e e-mail enviado.';
		} else {
			$_SESSION['error'] = 'Erro ao finalizar o serviço.';
		}

		header('Location: index.php?action=dashboard');
		exit;
	}

	public function delete()
	{
		$id = $_GET['id'] ?? null;
		if ($id && $this->serviceModel->delete($id)) {
			$_SESSION['success'] = 'Serviço excluído com sucesso!';
		} else {
			$_SESSION['error'] = 'Erro ao excluir serviço.';
		}

		header('Location: index.php?action=dashboard');
		exit;
	}
}
