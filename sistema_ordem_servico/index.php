<?php

session_start();

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ServiceController.php';

$action = $_GET['action'] ?? 'login';

switch ($action) {
	case 'login':
		$auth = new AuthController();
		$auth->login();
		break;

	case 'register':
		$auth = new AuthController();
		$auth->register();
		break;

	case 'logout':
		$auth = new AuthController();
		$auth->logout();
		break;

	case 'dashboard':
		$service = new ServiceController();
		$service->dashboard();
		break;

	case 'add_service':
		$service = new ServiceController();
		$service->addService();
		break;

	case 'finalize':
		$service = new ServiceController();
		$service->finalize();
		break;

	case 'delete':
		$service = new ServiceController();
		$service->delete();
		break;

	default:
		header('Location: index.php?action=login');
		exit;
}
