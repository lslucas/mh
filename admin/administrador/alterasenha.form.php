<?php
 if (isset($_POST) && !empty($_POST)) {
  include_once 'alterasenha.mod.exec.php';
 }

?>
<div class='alert alert-error completeform-error hide'>
	<a class="close" data-dismiss="alert">×</a>
	Antes de prosseguir preencha corretamente o formulário e revise os campos abaixo:
	<br/><br/>
	<ol> 
		<li><label for="senha_atual" class="error-validate">Para sua segurança, digite a sua senha atual</label></li> 
		<li><label for="senha" class="error-validate">Informe a nova senha</label></li> 
		<li><label for="confirma_senha" class="error-validate">O campo "confirma senha" deve ser idêntico ao campo "senha"</label></li>
	</ol> 
</div>




<form method='post' action='?p=<?=$p?>&alterasenha' class='form-horizontal cmxform'>
 <input type='hidden' name='item' value='<?=$_SESSION['user']['id']?>'>


<h1>Alterar senha</h1>
<p class='header'>Preencha todos os campos abaixo para alterar sua senha. Após o preenchimento você receberá um e-mail com sua nova senha e o seu login.</p>


  <fieldset>
    <!--<legend>Legend text</legend>-->
    <div class="control-group">
      <label class="control-label" for="senha_atual">* Senha Atual</label>
      <div class="controls">
        <input type='password' class="input-xlarge required" name='senha_atual' id='senha_atual'>
        <p class="help-block">Por segurança, informe a senha atual</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="senha">* Nova Senha</label>
      <div class="controls">
        <input type='password' class="input-xlarge required" name='senha' id='senha'>
        <p class="help-block">Sua nova senha</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="confirma_senha">* Confirme a senha</label>
      <div class="controls">
        <input type='password' class="input-xlarge required" name='confirma_senha' id='confirma_senha'>
        <p class="help-block">Confirme sua nova senha</p>
      </div>
    </div>

  </fieldset>

    <div class='form-actions'>
		<input type='submit' value='ok' class='btn btn-primary'>
	</div>

</form>


