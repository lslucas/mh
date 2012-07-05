 
 <div class='alert alert-error completeform-error hide'>
	<a class="close" data-dismiss="alert">×</a>
	Antes de prosseguir preencha corretamente o formulário e revise os campos abaixo:
	<br/><br/>
	<ol> 
		<li><label for="titulo" class="error-validate">Digite o título</label></li> 
		<li><label for="url" class="error-validate">Entre com uma <b>url</b> válida</label></li> 
		<li><label for="midia" class="error-validate">Informe um <b>midia</b></label></li> 
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

	<div class="control-group">
	  <label class="control-label" for="foto">Imagem</label>
	  <div class="controls">
		  <?php
			  
			$num=0;
			if ($act=='update') {

				$sql_s = "SELECT ${var['pre']}_id, ${var['pre']}_midia FROM ".TABLE_PREFIX."_${var['table']} WHERE ${var['pre']}_id=? LIMIT 1";
				if (!$qry_s = $conn->prepare($sql_s))
					echo $conn->error;

				else {

					$qry_s->bind_param('i', $val['id']);
					$qry_s->bind_result($g_id, $g_imagem);
					//$qry_s->store_result();
					$qry_s->execute();
					$qry_s->fetch();
					$num = $qry_s->num_rows();
					$qry_s->close();


					echo '<table id="posGaleria" cellspacing="0" cellpadding="2">';

					if (!empty($g_imagem)) {
						$arquivo = $var['path_original']."/".$g_imagem;
				  ?>
					<tr id="<?=$g_id?>">
					  <td width='20px' title='Clique e arraste para mudar a posição da foto' class='tip'></td>

					  <td>
						<small>
						[<a href='?p=<?=$p?>&delete_galeria&item=<?=$g_id?>&prefix=destaque&pre=des&col=midia&folder=<?=$var['imagem_folderlist']?>&noVisual' title="Clique para remover o ítem selecionado" class='tip trash-galeria' style="cursor:pointer;" id="<?=$g_id?>">remover</a>]
						</small>
					  </td>
					  <td>
						<a href='$imagThumb<?=$i?>?width=100%' id='imag<?=$i?>' class='betterTip' target='_blank'>
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

					?>
					 <div class='divImagem'>
					   <input type='file' name='midia' id='midia' style="margin-bottom:8px; width:500px;">
					   <br><span class='small'>- JPEG, PNG ou GIF;<?=$var['imagemWidth_texto'].$var['imagemHeight_texto']?></span>
					 </div>
					<?php

				}

			   } else {
		   ?>
			 <div class='divImagem'>
			   <input type='file' name='midia' id='midia' style="margin-bottom:8px; width:500px;">
			   <br><span class='small'>- JPEG, PNG ou GIF;<?=$var['imagemWidth_texto'].$var['imagemHeight_texto']?></span>
			 </div>
			<?php
			
			   }
			?>
	  </div>
	</div>
<?php /*
	<div class="control-group principal">
	  <label class="control-label">Ou</label>
	</div>

	<div class="control-group principal">
	  <label class="control-label" for="midia">Mídia Embed (Youtube/Vimeo/Etc)</label>
	  <div class="controls">
		<textarea name='midia' id='midia' style='width:380px; height:100px;' rows=4><?=$val['tipo']=='Youtube' ? stripslashes($val['midia']) : null?></textarea>
		<p class="help-block">Entre com o embed</p>
	  </div>
	</div>
	</fieldset>
 */?>

	<div class="control-group">
	  <label class="control-label" for="data">* Data</label>
	  <div class="controls">
		<input type="text" class="input-xlarge data required" placeholder='Data para ordenação' name='data' id='data' value='<?=$act=='insert' ? date('d/m/Y') : dateen2pt('-',$val['data'],'/')?>'>
		<p class="help-block">Informe uma data para ordenação</p>
	  </div>
	</div>


	<div class="control-group">
	  <label class="control-label" for="titulo">* Título</label>
	  <div class="controls">
		<input type="text" class="input-xlarge required" placeholder='Título descritivo' name='titulo' id='titulo' value='<?=$val['titulo']?>'>
		<p class="help-block">Informe um título descritivo</p>
	  </div>
	</div>

	<div class="control-group">
	  <label class="control-label" for="url">URL</label>
	  <div class="controls">
		<input type="text" class="input-xlarge" placeholder='http://destino.com.br' name='url' id='url' value='<?=$val['url']?>'>
		<p class="help-block">URL ou link do destaque</p>
	  </div>
	</div>
	</fieldset>

	<div class='form-actions'>
		<input type='submit' value='ok' class='btn btn-primary'>
		<input type='button' id='form-back' value='voltar' class='btn'>
	</div>

</form>
