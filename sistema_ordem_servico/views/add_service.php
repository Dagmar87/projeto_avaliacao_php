<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<title>Cadastrar Novo Serviço</title>
	<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
	<div class="container" style="max-width: 450px; margin-top: 50px;">
		<div class="title-header">Cadastrar Novo Serviço</div>

		<form action="index.php?action=add_service" method="POST" id="formService">
			<div class="form-group">
				<input type="text" name="description" class="form-control" placeholder="descrição" required>
			</div>
			<div class="form-group">
				<input type="text" name="price" id="price" class="form-control" placeholder="preço (ex: 150.00)" required>
			</div>
			<div>
				<button type="submit" class="btn-black">Cadastrar</button>
				<a href="index.php?action=dashboard" class="link-register">Cancelar / Voltar</a>
			</div>
		</form>
	</div>
	<script src="assets/js/script.js"></script>
</body>

</html>