 <div class='alert alert-error completeform-error hide'>
	<a class="close" data-dismiss="alert">×</a>
	Antes de prosseguir preencha corretamente o formulário e revise os campos abaixo:
	<br/><br/>
	<ol> 
		<li><label for="nome" class="error-validate">Digite o nome</label></li> 
		<li><label for="email" class="error-validate">Informe um <b>email</b> válido</label></li> 
	</ol> 
</div>



<form method='post' action='?<?=$_SERVER['QUERY_STRING']?>' id='form_<?=$p?>' class='form-horizontal cmxform' enctype="multipart/form-data">
 <input type='hidden' name='act' value='<?=$act?>'>
<?php
  if ($act=='update') {
    echo "<input type='hidden' name='item' value='${_GET['item']}'>";
  }
?>

<h1>
<?php 
  if ($act=='insert') echo $var['insert'];
   else echo $var['update'];
?>
</h1>
<p class='header'>Todos os campos com <b>- * -</b> são obrigatórios.</p>

  <fieldset>
    <div class="control-group">
      <label class="control-label" for="nome">* Nome</label>
      <div class="controls">
        <input type="text" class="input-xlarge required" placeholder='Nome' name='nome' id='nome' value='<?=$val['nome']?>'>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="login">Login</label>
      <div class="controls">
        <input type="text" class="input-xlarge" placeholder='Login do usuário' name='login' id='login' value='<?=$val['login']?>'>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="email">* Email</label>
      <div class="controls">
        <input type="text" class="input-xlarge required email" placeholder='email@provedor.com' name='email' id='email' value='<?=$val['email']?>'>
        <p class="help-block">Informe um email válido</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="email_secundario">Email Secundário</label>
      <div class="controls">
        <input type="text" class="input-xlarge email" placeholder='email@provedor.com' name='email_secundario' id='email_secundario' value='<?=$val['email_secundario']?>'>
        <p class="help-block">Por segurança informe o email alternativo</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="cpf">* CPF</label>
      <div class="controls">
        <input type="text" class="input-xlarge required cpf" placeholder='999.999.999-99' name='cpf' id='cpf' value='<?=$val['cpf']?>'>
        <p class="help-block">Informe um CPF para o usuário</p>
      </div>
    </div>


    <div class="control-group">
      <label class="control-label" for="senha">Senha</label>
      <div class="controls">
        <input type="text" class="input-xlarge" placeholder='Nova senha' name='senha' id='senha'>
        <p class="help-block">Mudar a senha do usuário, deixe em branco caso continue a mesma</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="telefone1">Telefone 1</label>
      <div class="controls">
        <input type="text" class="input-xlarge phone" placeholder='(99) 9999-9999' name='telefone1' id='telefone1' value='<?=$val['telefone1']?>'>
        <p class="help-block">Telefone principal</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="telefone2">Telefone 2</label>
      <div class="controls">
        <input type="text" class="input-xlarge" placeholder='Telefone Secundário' name='telefone2' id='telefone2' value='<?=$val['telefone2']?>'>
        <p class="help-block">Telefone secundário</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="cep">CEP</label>
      <div class="controls">
        <input type="text" class="input-xlarge cep" placeholder='99.999-999' name='cep' id='cep' value='<?=$val['cep']?>'>
        <p class="help-block">CEP de residência</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="aniversario">Aniversario</label>
      <div class="controls">
        <input type="text" class="input-xlarge data" placeholder='99/99/9999' name='aniversario' id='aniversario' value='<?=$val['aniversario']?>'>
        <p class="help-block">Data de nascimento</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="pais">País</label>
      <div class="controls">
        <input type="text" class="input-xlarge" placeholder='Brasil' name='pais' id='pais' value='<?=$val['pais']?>'>
        <p class="help-block">País de residência</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="bio">Bio</label>
      <div class="controls">
        <textarea name='bio' id='bio'><?=$val['bio']?></textarea>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="nota">Notas</label>
      <div class="controls">
        <textarea name='nota' id='nota'><?=$val['nota']?></textarea>
        <p class="help-block">Notas extras e observações</p>
      </div>
    </div>

  </fieldset>

    <div class='form-actions'>
		<input type='submit' value='ok' class='btn btn-primary'>
		<input type='button' id='form-back' value='voltar' class='btn'>
	</div>



</form>


