<?php

	/*
	 *classe de password
	 */
	include_once 'class.password.php';
	include_once 'global.php';

?>
<html lang="en">
  <head>
	    <meta charset="utf-8">
		<title>Select Shop</title>
		<link href="<?=$rp?>bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?=$rp?>css/application.css" rel="stylesheet">
	</head>
<body>
<div style='padding:20px;'>
	<h3>Criptografar Senha</h3>
	<form name='senha' method='post' action='<?=$_SERVER['PHP_SELF']?>'>
		<input type='text' class='input-xlarge' name='senha' placeholder='Digite a senha para ser criptografada' size=30 value='<?=isset($_POST['senha']) ? $_POST['senha'] : null?>'>
		<br/>
		<input type='submit' class='btn-primary' value='Criptografar!'/>
	</form>

<?php

	if (isset($_POST['senha'])) {

		$senha = trim($_POST['senha']);
		$password = new Password;
		$pass = $password->hash($senha, 'mcrypt', SITE_NAME.'salt');

		echo "Senha Criptografada/Hash <pre class='prettyprint'>".$pass."</pre>";
	}


?>
</div>
</body>
</html>
