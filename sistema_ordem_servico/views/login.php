<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<title>Sistema de Controle de Serviços - Login</title>
	<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
	<div class="container" style="max-width: 450px; margin-top: 50px;">
		<div class="title-header">Sistema de Controle de Serviços</div>
		<?php if (isset($_SESSION['error'])): ?>
			<div class="alert alert-danger"><?= $_SESSION['error'];
																			unset($_SESSION['error']); ?></div>
		<?php endif; ?>

		<?php if (isset($_SESSION['success'])): ?>
			<div class="alert alert-success"><?= $_SESSION['success'];
																				unset($_SESSION['success']); ?></div>
		<?php endif; ?>

		<form action="index.php?action=login" method="POST">
			<div class="form-group">
				<input type="email" name="email" class="form-control" placeholder="email@email.com" required>
			</div>
			<div class="form-group">
				<input type="password" name="password" class="form-control" placeholder="*************" required>
			</div>
			<div>
				<button type="submit" class="btn-black">Entrar</button>
				<a href="index.php?action=register" class="link-register">Cadastrar usuário</a>
			</div>
		</form>
	</div>
</body>

</html>