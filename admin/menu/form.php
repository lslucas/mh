<div class='alert alert-error completeform-error hide'>
	<a class="close" data-dismiss="alert">×</a>
	Antes de prosseguir preencha corretamente o formulário e revise os campos abaixo:
	<br/><br/>
	<ol> 
		<li><label for="modulo_id" class="error-validate">Selecione o módulo</label></li> 
		<li><label for="pai" class="error-validate">Informe o módulo pai</label></li> 
		<li><label for="nome" class="error-validate">Digite o nome do menu</label></li> 
		<li><label for="nivel" class="error-validate">Informe o peso do menu</label></li> 

	</ol> 
</div>



<form method='post' action='?<?=$_SERVER['QUERY_STRING']?>' class='form-horizontal cmxform'>
 <input type='hidden' name='act' value='<?=$act?>'>
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
	<?php
	  if (!isset($_GET['pai'])) {
	?>
      <label class="control-label" for="modulo_id">* Módulo</label>
      <div class="controls">
		  <select name='modulo_id' id='modulo_id' class='required'>
			<option value=''>Selecione</option>
			<?php

			  $sql_mod = "SELECT mod_id,mod_nome FROM ".TABLE_PREFIX."_modulo WHERE mod_status=1";
			  $qry_mod = $conn->prepare($sql_mod);
			  $qry_mod->bind_result($id, $nome);
			  $qry_mod->execute();
			 
				  while ($qry_mod->fetch()) {
			?>
			   <option value='<?=$id?>'<?php if ($act=='update' && $val['modulo_id']==$id) echo ' selected';?>> <?=$nome?></option>
			<?php } $qry_mod->close(); ?>
		  </select> 
        <p class="help-block">Módulo do novo ítem de menu</p>
      </div>
    </div>

	<?php
	  } else {
	?>
    <div class="control-group">
      <label class="control-label" for="pai">* Pai</label>
      <div class="controls">
	  <select name='pai' id='pai' class='required'>
	    <option value=''>Selecione</option>
		<?php

		  $sql_mod = "SELECT men_id,(SELECT mod_nome FROM ".TABLE_PREFIX."_modulo WHERE men_modulo_id=mod_id) men_nome FROM ".TABLE_PREFIX."_menu WHERE men_status=1 AND men_modulo_id IS NOT NULL";
		  $qry_mod = $conn->prepare($sql_mod);
		  $qry_mod->bind_result($id, $nome);
		  $qry_mod->execute();
		 
			  while ($qry_mod->fetch()) {
		?>
		   <option value='<?=$id?>'<?php if ($act=='update' && $val['pai']==$id || isset($_GET['pai']) && $_GET['pai']==$id) echo ' selected';?>> <?=$nome?></option>
		<?php } $qry_mod->close(); ?>
	  </select> 

        <p class="help-block">Selecione um módulo pai</p>
      </div>
    </div>

	<?php
	  }
	?>

	<?php
	  if (isset($_GET['pai'])) {
	?>
    <div class="control-group">
      <label class="control-label" for="nome">* Nome</label>
      <div class="controls">
        <input type="text" class="input-xlarge required" placeholder='Nome' name='nome' id='nome' value='<?=$val['nome']?>'>
        <p class="help-block">Informe o nome</p>
      </div>
    </div>
	<?php
	  }
	?>

    <div class="control-group">
      <label class="control-label" for="link"><?php if (isset($_GET['pai'])) echo " *";?> Link</label>
      <div class="controls">
        <input type="text" class="input-xlarge<?php if (isset($_GET['pai'])) echo " required";?>" placeholder='Link' name='link' id='link' value='<?=$val['link']?>'>
        <p class="help-block">Digite um link válido</p>
      </div>
    </div>

	<?php
	  if (!isset($_GET['pai'])) {
	?>
    <div class="control-group">
      <label class="control-label" for="nivel">* Peso</label>
      <div class="controls">
        <input type="text" class="input-xlarge required" placeholder='Peso 1, 2, 3' name='nivel' id='nivel' value='<?=$val['nivel']?>'>
        <p class="help-block">Posição do ítem no menu de navegação</p>
      </div>
    </div>
	<?php
	  }
	?>
  </fieldset>

    <div class='form-actions'>
		<input type='submit' value='ok' class='btn btn-primary'>
		<input type='button' id='form-back' value='voltar' class='btn'>
	</div>


</form>


