<div class='alert alert-error completeform-error hide'>
	<a class="close" data-dismiss="alert">×</a>
	Antes de prosseguir preencha corretamente o formulário e revise os campos abaixo:
	<br/><br/>
	<ol> 
		<li><label for="galeria" class="error-validate">Envia alguma imagem/foto</label></li> 
		<li><label for="titulo" class="error-validate">Informe o título</label></li> 
		<li><label for="area" class="error-validate">Informe a area</label></li> 
		<li><label for="ano" class="error-validate">Informe um ano válido</label></li> 
	</ol> 
</div>



<form method='post' action='?<?=$_SERVER['QUERY_STRING']?>' id='form_<?=$p?>' class='form-horizontal cmxform' enctype="multipart/form-data">
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
<?php /*
    <div class="control-group">
      <label class="control-label" for="imagem">Imagem</label>
      <div class="controls">
	  <?php
		  
	    if ($act=='update') {
				  
		    $sql_gal = "SELECT rcg_id,rcg_imagem,rcg_pos FROM ".TABLE_PREFIX."_r_${var['pre']}_galeria WHERE rcg_${var['pre']}_id=? AND rcg_imagem IS NOT NULL ORDER BY rcg_pos ASC;"; 
		    $qr_gal = $conn->prepare($sql_gal);
		    $qr_gal->bind_param('s',$_GET['item']);
		    $qr_gal->execute();
		    $qr_gal->bind_result($g_id,$g_imagem,$g_pos);
		    $i=0;

		      echo '<table id="posGaleria" cellspacing="0" cellpadding="2">';
		      while ($qr_gal->fetch()) {
	  ?>
		<tr id="tr<?=$g_id?>">
		  <td width='20px' title='Clique e arraste para mudar a posição da foto' class='tip'></td>

		  <td class='small'>
		    [<a href='?p=<?=$p?>&delete_galeria&item=<?=$g_id?>&prefix=r_<?=$var['pre']?>_galeria&pre=rcg&col=imagem&folder=<?=$var['imagem_folderlist']?>&noVisual' title="Clique para remover o ítem selecionado" class='tip trash-galeria' style="cursor:pointer;" id="<?=$g_id?>">remover</a>]
		  </td>

		  <td>

		    <a href='$imagThumb<?=$i?>?width=100%' id='imag<?=$i?>' class='betterTip'>
		     <img src='images/lupa.gif' border='0' style='background-color:none;padding-left:10px;cursor:pointer'></a>

			 <div id='imagThumb<?=$i?>' style='float:left;display:none;'>
			 <?php 
			 
			    if (file_exists(substr($var['path_thumb'],0)."/".$g_imagem))
			     echo "<img src='".substr($var['path_thumb'],0)."/".$g_imagem."'>";

			       else echo "<center>imagem não existe.</center>";
			  ?>
			 </div>

		  </td>
		</tr>

	      <?php
		      $i++;	

			}
		   echo '</table><br>';

	       }
	       ?>
		 <div class='divImagem'>
		   <input class="galeria" type='file' name='galeria0' id='galeria' alt='0' style="height:18px;font-size:7pt;margin-bottom:8px;">
		   <br><span class='small'>- JPEG, PNG ou GIF;<?=$var['imagemWidth_texto'].$var['imagemHeight_texto']?></span>
		 </div>
        <!--<p class="help-block"></p>-->
      </div>
    </div>
*/ ?>

    <div class="control-group">
      <label class="control-label" for="titulo">* Título</label>
      <div class="controls">
        <input type="text" class="input-xlarge required" placeholder='Título' name='titulo' id='titulo' value='<?=$val['titulo']?>'>
        <p class="help-block">Informe o título</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="idrel">* Relacionado a </label>
      <div class="controls">
		  <select name='idrel' id='idrel'>
			<option>Selecione</option>
			<?php
			  $sql_idrel = "SELECT cat_titulo, cat_id FROM ".TABLE_PREFIX."_categoria WHERE cat_status=1 AND cat_area='Categoria Principal' ORDER BY cat_titulo";
			  $qry_idrel = $conn->prepare($sql_idrel);
			  $qry_idrel->bind_result($nome, $id);
			  $qry_idrel->execute();
			 
				  while ($qry_idrel->fetch()) {
			?>
		   <option value='<?=$id?>'<?php if ($act=='update' && $val['idrel']==$id) echo ' selected';?>> <?=$nome?></option>
		<?php } $qry_idrel->close(); ?>
		  </select>
		<p class="help-block">Item relacionado a qual categoria principal?</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="area">* Área</label>
      <div class="controls">
		  <select name='area' id='area' class='required'>
			<option>Selecione</option>
			<option value='Categoria Principal' <?php if($val['area']=='Categoria Principal') echo ' selected';?>>Categoria Principal</option>
			<option value='Categoria Secundária' <?php if($val['area']=='Categoria Secundária') echo ' selected';?>>Categoria Secundária</option>
			<option value='Categoria Outra' <?php if($val['area']=='Categoria Outra') echo ' selected';?>>Categoria Outra</option>
		  </select>
		<p class="help-block">Para onde vai esse ítem?</p>
      </div>
    </div>

	<div class='divMarca' class='hide'>

<?php /*
		<div class="control-group">
		  <label class="control-label" for="idrel">* Marca</label>
		  <div class="controls">
			  <select name='idrel' id='idrel' class='required'>
				<option>Selecione</option>
				<?php
				  $sql_marca = "SELECT cat_titulo, cat_id FROM ".TABLE_PREFIX."_categoria WHERE cat_area='Marca'";
				  $qry_marca = $conn->prepare($sql_marca);
				  $qry_marca->bind_result($nome, $id);
				  $qry_marca->execute();
				 
					  while ($qry_marca->fetch()) {
				?>
				   <option value='<?=$id?>'<?php if ($act=='update' && $val['idrel']==$id) echo ' selected';?>> <?=$nome?></option>
				<?php } $qry_marca->close(); ?>
			  </select>
			<p class="help-block">Marca do modelo</p>
		  </div>
		</div>
*/
?>

	</div>

  </fieldset>

    <div class='form-actions'>
		<input type='submit' value='ok' class='btn btn-primary'>
		<input type='button' id='form-back' value='voltar' class='btn'>
	</div>

</form>
