<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<title>Cadastrar Novo Usuário</title>
	<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
	<div class="container" style="max-width: 450px; margin-top: 50px;">
		<div class="title-header">Cadastrar Novo Usuário</div>

		<?php if (isset($_SESSION['error'])): ?>
			<div class="alert alert-danger"><?= $_SESSION['error'];
																			unset($_SESSION['error']); ?></div>
		<?php endif; ?>

		<form action="index.php?action=register" method="POST">
			<div class="form-group">
				<input type="text" name="name" class="form-control" placeholder="Nome Completo" required>
			</div>
			<div class="form-group">
				<input type="email" name="email" class="form-control" placeholder="email@email.com" required>
			</div>
			<div class="form-group">
				<input type="password" name="password" class="form-control" placeholder="*************" required>
			</div>
			<div>
				<button type="submit" class="btn-black">Cadastrar</button>
				<a href="index.php?action=login" class="link-register">Voltar ao Login</a>
			</div>
		</form>
	</div>
</body>

</html>