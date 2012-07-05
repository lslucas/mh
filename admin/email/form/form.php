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
    <legend>Dados Pessoais</legend>
    <div class="control-group">
      <label class="control-label" for="nome">* Nome</label>
      <div class="controls">
        <input type="text" class="input-xlarge required" placeholder='Nome' name='nome' id='nome' value='<?=$val['nome']?>'>
        <p class="help-block">Informe o nome</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="email">* Email</label>
      <div class="controls">
        <input type="text" class="input-xlarge email required" placeholder='email@provedor.com.br' name='email' id='email' value='<?=$val['email']?>'>
        <p class="help-block">Email do cadastrado</p>
      </div>
    </div>

  </fieldset>

    <div class='form-actions'>
		<input type='submit' value='ok' class='btn btn-primary'>
		<input type='button' id='form-back' value='voltar' class='btn'>
	</div>



</form>


