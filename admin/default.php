<?php
	include_once 'helper/default.php';
?>
	<div class="hero-unit no-print">
		<h1>Bem-vindo(a) <b><?=$_SESSION['user']['nome']?></b>!</h1>
		<?php if (isset($_SESSION['user']['plano'])) { ?>
			<hr>
			<h3><?=$_SESSION['user']['plano']?></h3>
			<br/><b>Limite de estoque: </b> <?=$_SESSION['user']['estoque']?> veículos
			<br/><b>Limite de fotos por veículo: </b> <?=$_SESSION['user']['qtd_fotos']?> fotos
			<br/><b>Limite de veículos premium: </b> <?=$_SESSION['user']['premium']?>
		<?php } ?>
	</div>
