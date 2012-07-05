<div class='alert alert-error completeform-error hide'>
	<a class="close" data-dismiss="alert">×</a>
	Antes de prosseguir preencha corretamente o formulário e revise os campos abaixo:
	<br/><br/>
	<ol> 
		<li><label for="nome" class="error-validate">Informe o nome</label></li> 
		<li><label for="nome_singular" class="error-validate">Informe o singular de "nome"</label></li> 
		<li><label for="nome_plural" class="error-validate">Informe o plural de "nome"</label></li> 
		<li><label for="genero" class="error-validate">Informe o gênero de "nome"</label></li> 
		<li><label for="path" class="error-validate">Entre com o diretório do módulo</label></li> 
		<li><label for="pre" class="error-validate">Entre com o prefixo ou abreviação do módulo</label></li> 
	</ol> 
</div>



<form method='post' action='?<?=$_SERVER['QUERY_STRING']?>' class='form-horizontal cmxform'>
 <input type='hidden' name='act' value='insert'>
<?php
  if ($act=='update')
    echo "<input type='hidden' name='item' value='${_GET['item']}'>";
?>

<h1>
<?php 
  if ($act=='insert') echo $var['insert'];
   else echo $var['update'];
?>
</h1>
<p class='header'>Todos os campos com <b>- * -</b> são obrigatórios.</p>



  <fieldset>
    <!--<legend>Legend text</legend>-->
    <div class="control-group">
      <label class="control-label" for="nome">* Nome</label>
      <div class="controls">
        <input type="text" class="input-xlarge required" placeholder='Nome' name='nome' id='nome' value='<?=$val['nome']?>'>
        <p class="help-block">Informe o nome do módulo</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="nome_singular">* Nome Singular</label>
      <div class="controls">
        <input type="text" class="input-xlarge required" placeholder='Nome Singular' name='nome_singular' id='nome_singular' value='<?=$val['nome_singular']?>'>
        <p class="help-block">Nome do módulo em singular</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="nome_plural">* Nome Plural</label>
      <div class="controls">
        <input type="text" class="input-xlarge required" placeholder='Nome Plural' name='nome_plural' id='nome_plural' value='<?=$val['nome_plural']?>'>
        <p class="help-block">Nome do módulo em plural</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="genero">* Gênero</label>
      <div class="controls">
        <input type="text" class="input-xlarge required" placeholder='Genero (o, a)' name='genero' id='genero' value='<?=$val['genero']?>'>
        <p class="help-block">Gênero do módulo, exe: nov<b>o</b> "Menu" ou nov<b>a</b> "Roda"</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="path">* Pasta</label>
      <div class="controls">
        <input type="text" class="input-xlarge required" placeholder='pasta' name='path' id='path' value='<?=$val['path']?>'>
        <p class="help-block">Pasta onde está instalado o módulo, exe: <b>modulo</b></p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="pre">* Prefixo</label>
      <div class="controls">
        <input type="text" class="input-xlarge required" placeholder='pre' name='pre' id='pre' value='<?=$val['pre']?>'>
        <p class="help-block">Prefixo para uso na tabela de banco de dados</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="icone">Icone</label>
      <div class="controls">
        <input type="text" class="input-xlarge" placeholder='icone' name='icone' id='icone' value='<?=$val['icone']?>'>
        <p class="help-block">Prefixo para uso na tabela de banco de dados</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="display">* Visivel?</label>
      <div class="controls">
		<select name='display' id='display'>
			<option value='1'<?php if ($act=='update' && $val['display']==1) echo ' selected';?>>Sim</option>
			<option value='0'<?php if ($act=='update' && $val['display']==0) echo ' selected';?>>Não</option>
		</select>
        <p class="help-block">Prefixo para uso na tabela de banco de dados</p>
      </div>
    </div>


    </div>

  </fieldset>


    <div class='form-actions'>
		<input type='submit' value='ok' class='btn btn-primary'>
		<input type='button' id='form-back' value='voltar' class='btn'>
	</div>


</form>


