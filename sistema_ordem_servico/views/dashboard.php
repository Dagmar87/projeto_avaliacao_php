<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<title>Dashboard - JM Informática</title>
	<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
	<div class="container">
		<div class="top-bar">
			<div>
				<a href="index.php?action=add_service" class="btn-black" style="text-decoration:none; display:inline-block;">Cadastrar Serviço</a>
			</div>
			<h2>DASHBOARD</h2>
			<div class="user-info">
				<strong>Logado como:</strong><br>
				<?= htmlspecialchars($_SESSION['user_name']); ?><br>
				<small>Data Atual: <?= date('d/m/Y'); ?></small><br>
				<a href="index.php?action=logout" style="color:red; font-size:12px;">Sair</a>
			</div>
		</div>

		<?php if (isset($_SESSION['error'])): ?>
			<div class="alert alert-danger"><?= $_SESSION['error'];
																			unset($_SESSION['error']); ?></div>
		<?php endif; ?>
		<?php if (isset($_SESSION['success'])): ?>
			<div class="alert alert-success"><?= $_SESSION['success'];
																				unset($_SESSION['success']); ?></div>
		<?php endif; ?>

		<div class="dashboard-grid">
			<div class="dashboard-box">
				<h4>Últimos Serviços</h4>
				<ul class="list-unstyled">
					<?php foreach ($recentServices as $rs): ?>
						<li><?= $rs['id_service'] ?> - <?= htmlspecialchars($rs['description']) ?></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="dashboard-box">
				<h4>Serviços Pendentes (Seus)</h4>
				<ul class="list-unstyled">
					<?php foreach ($pendingServicesUser as $pu): ?>
						<li><?= $pu['id_service'] ?> - <?= htmlspecialchars($pu['description']) ?></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="dashboard-box" style="text-align: center; background-color: #e9ecef;">
				<h4>Total dos Seus Serviços</h4>
				<h2 style="margin: 10px 0; color: #28a745;">R$ <?= number_format($totalValueUser, 2, ',', '.') ?></h2>
			</div>
		</div>

		<div class="filters-box">
			<form method="GET" action="index.php">
				<input type="hidden" name="action" value="dashboard">

				<input type="date" name="date_initial" value="<?= htmlspecialchars($_GET['date_initial'] ?? '') ?>" title="Data Inicial">
				<input type="date" name="date_final" value="<?= htmlspecialchars($_GET['date_final'] ?? '') ?>" title="Data Final">

				<input type="text" name="service_name" placeholder="Nome do Serviço" value="<?= htmlspecialchars($_GET['service_name'] ?? '') ?>">

				<select name="status">
					<option value="">-- Todos Status --</option>
					<option value="PENDENTE" <?= (($_GET['status'] ?? '') === 'PENDENTE') ? 'selected' : '' ?>>PENDENTE</option>
					<option value="FINALIZADO" <?= (($_GET['status'] ?? '') === 'FINALIZADO') ? 'selected' : '' ?>>FINALIZADO</option>
				</select>

				<input type="text" name="user_name" placeholder="Nome Usuário" value="<?= htmlspecialchars($_GET['user_name'] ?? '') ?>">

				<button type="submit" class="btn-black" style="padding: 5px 15px;">Filtrar</button>
				<a href="index.php?action=dashboard" style="padding: 5px; font-size:12px;">Limpar</a>
			</form>
		</div>

		<table class="table-services">
			<thead>
				<tr>
					<th>ID</th>
					<th>DESCRIÇÃO</th>
					<th>VALOR</th>
					<th>USUÁRIO</th>
					<th>STATUS</th>
					<th>AÇÕES</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($services)): ?>
					<tr>
						<td colspan="6" style="text-align:center;">Nenhum serviço encontrado.</td>
					</tr>
				<?php else: ?>
					<?php foreach ($services as $s): ?>
						<?php $status = $s['finished_at'] ? 'FINALIZADO' : 'PENDENTE'; ?>
						<tr>
							<td><?= $s['id_service'] ?></td>
							<td><?= htmlspecialchars($s['description']) ?></td>
							<td>R$ <?= number_format($s['price'], 2, ',', '.') ?></td>
							<td><?= htmlspecialchars($s['user_name']) ?></td>
							<td class="<?= $status === 'FINALIZADO' ? 'status-finalizado' : 'status-pendente' ?>"><?= $status ?></td>
							<td>
								<?php if ($status === 'PENDENTE'): ?>
									<a href="index.php?action=finalize&id=<?= $s['id_service'] ?>" onclick="return confirm('Deseja finalizar este serviço e gerar comissão?')" style="color:green; font-weight:bold;">[Finalizar]</a>
								<?php endif; ?>
								<a href="index.php?action=delete&id=<?= $s['id_service'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')" style="color:red; margin-left:5px;">[Excluir]</a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</body>

</html>