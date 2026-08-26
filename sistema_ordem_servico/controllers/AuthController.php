<?php

require_once __DIR__ . '/../models/User.php';

class AuthController
{

	private $userModel;

	public function __construct()
	{
		$this->userModel = new User();
	}

	public function Login()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$email = trim($_POST['email'] ?? '');
			$password = trim($_POST['password'] ?? '');

			if (empty($email) || empty($password)) {
				$_SESSION['error'] = 'Ops, Email ou Senha inválido';
				header('Location: index.php?action=login');
				exit;
			}

			$user = $this->userModel->findByEmail($email);

			if ($user && password_verify($password, $user['password'])) {
				$_SESSION['user_id'] = $user['id_user'];
				$_SESSION['user_name'] = $user['name'];
				$_SESSION['user_email'] = $user['email'];
				header('Location: index.php?action=dashboard');
				exit;
			} else {
				$_SESSION['error'] = 'Ops, Email ou Senha inválido';
				header('Location: index.php?action=login');
				exit;
			}
		}

		require __DIR__ . '/../views/login.php';
	}

	public function register()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$name = trim($_POST['name'] ?? '');
			$email = trim($_POST['email'] ?? '');
			$password = trim($_POST['password'] ?? '');

			if (empty($name) || empty($email) || empty($password)) {
				$_SESSION['error'] = 'Preencha todos os campos para cadastrar.';
				header('Location: index.php?action=register');
				exit;
			}

			if ($this->userModel->findByEmail($email)) {
				$_SESSION['error'] = 'E-mail já cadastrado.';
				header('Location: index.php?action=register');
				exit;
			}

			if ($this->userModel->create($name, $email, $password)) {
				$_SESSION['success'] = 'Usuário cadastrado com sucesso! Faça login.';
				header('Location: index.php?action=login');
				exit;
			} else {
				$_SESSION['error'] = 'Erro ao cadastrar usuário.';
				header('Location: index.php?action=register');
				exit;
			}
		}
		require __DIR__ . '/../views/register_user.php';
	}

	public function logout()
	{
		session_destroy();
		header('Location: index.php?action=login');
		exit;
	}
}
