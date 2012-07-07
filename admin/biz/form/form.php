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
    <legend>Contato/Responsável da Empresa</legend>
    <div class="control-group">
      <label class="control-label" for="usr_code">* Responsável</label>
      <div class="controls">
        <select name='usr_code' id='usr_code' class="input-xlarge required">
		<?php
		  $listUsers = getListUsers();
		  foreach ($listUsers as $int=>$usr) {

			  $selected = null;
			  if ($usr['usr_code']==$val['code'])
				  $selected = ' selected';

			  echo "\n\t\t<option value='{$usr['usr_code']}'{$selected}>{$usr['usr_nome']} - {$usr['usr_email']}</option>'";

		  }
		?>
		</select>
		<p class="help-block">Selecione o responsável pela empresa, se ainda não foi cadastrado <a href='<?=$rp?>index.php?p=user&insert' target='_blank'>clique aqui</a></p>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Dados da Empresa</legend>
    <div class="control-group">
      <label class="control-label" for="nome_fantasia">* Nome Fantasia</label>
      <div class="controls">
        <input type="text" class="input-xlarge required" placeholder='Nome Fantasia' name='nome_fantasia' id='nome_fantasia' value='<?=$val['nome_fantasia']?>'>
        <p class="help-block">Informe o nome fantasia da empresa</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="razao_social">Razão Social</label>
      <div class="controls">
        <input type="text" class="input-xlarge" placeholder='Razão Social' name='razao_social' id='razao_social' value='<?=$val['razao_social']?>'>
        <p class="help-block">Informe a razão social da empresa</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="cnpj">CNPJ</label>
      <div class="controls">
        <input type="text" class="input-xlarge cnpj" placeholder='CNPJ' name='cnpj' id='cnpj' value='<?=$val['cnpj']?>'>
        <p class="help-block">Informe o CNPJ da empresa</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="endereco">Endereço</label>
      <div class="controls">
        <textarea name='endereco' id='endereco'><?=$val['endereco']?></textarea>
        <p class="help-block">Informe o endereço, número e complemento</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="estado">UF</label>
      <div class="controls">
        <input type="text" class="input-xlarge uf" placeholder='UF' maxlength=2 name='estado' id='estado' value='<?=$val['estado']?>'>
        <p class="help-block">Informe o estado da empresa</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="cidade">Cidade</label>
      <div class="controls">
        <input type="text" class="input-xlarge" placeholder='Cidade' name='cidade' id='cidade' value='<?=$val['cidade']?>'>
        <p class="help-block">Informe a cidade da empresa</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="site">Site</label>
      <div class="controls">
        <input type="text" class="input-xlarge" placeholder='http://empresa.com.br' name='site' id='site' value='<?=$val['site']?>'>
        <p class="help-block">Informe o site da empresa</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="telefone1">Telefone 1</label>
      <div class="controls">
        <input type="text" class="input-xlarge phone" placeholder='(99) 9999-9999' name='telefone1' id='telefone1' value='<?=$val['telefone1']?>'>
        <p class="help-block">Telefone principal da empresa</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="telefone2">Telefone 2</label>
      <div class="controls">
        <input type="text" class="input-xlarge" placeholder='Telefone Secundário' name='telefone2' id='telefone2' value='<?=$val['telefone2']?>'>
        <p class="help-block">Telefone secundário da empresa</p>
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


