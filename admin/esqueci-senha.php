<?php
	include_once '_inc/global.php';
	include_once '_inc/db.php';
	include_once '_inc/global_function.php';
	include 'mod.var.php';
	include 'mod.header.php';
	include_once 'inc.header.php';

	if (isset($_POST['email']) && validaEmail($_POST['email']))
		include_once 'esqueci-senha.exec.php';

?>
<h1>Esqueci a senha</h1>
<p class='header'>Informe seu email no campo abaixo e em instantes você receberá uma nova senha no email informado.</p>
<br/>


<form action="<?=$_SERVER['PHP_SELF']?>" method="post" class='cmxform form-horizontal'>

  <fieldset>
    <div class="control-group">
      <label class="control-label" for="email">* Email</label>
      <div class="controls">
        <input type='text' class="input-xlarge required" name='email' id='email'>
        <p class="help-block">Informe seu email!</p>
      </div>
    </div>
  </fieldset>

    <div class='form-actions'>
		<input type='submit' value='Recuperar Senha' class='btn btn-primary'>
	</div>


</form>
<?php

	include_once 'inc.footer.php';
