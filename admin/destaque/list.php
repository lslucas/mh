<?php
	include_once 'helper/list.php';
?>
<h1><?=$var['mono_plural']?></h1>
<p class='header'></p>
<div class='small' align='right'><?=$total?></div>

<table class="table table-condensed table-striped">
   <thead> 
      <tr>
        <th width="25px"></th>
        <th width="50px">Data</th>
        <th>Título</th>
      </tr>
   </thead>  
   <tbody>
<?php

    $j=0;

    while ($qry->fetch()) {

$delete_images = "&prefix=destaque&pre={$var['pre']}&col=id&folder=${var['imagem_folderlist']}";

$statusOnLabel = "<font color=#000000>Ativo</font>"; 
$statusOnIcon = "<i class=icon-eye-open></i> ";
$statusOffLabel =  "<font color=#999999>Bloqueado</font>";
$statusOffIcon = "<i class=icon-eye-close></i> ";
$altStatus = '{"ativo": "'.$statusOnIcon.$statusOnLabel.'", "inativo": "'.$statusOffIcon.$statusOffLabel.'"}';

if ($status==1)
	$descStatus = $statusOnIcon.$statusOnLabel;
else
	$descStatus = $statusOffIcon.$statusOffLabel;

$row_actions = null; 
$row_actions .= <<<end
		<div class="btn-group">
          <a class="btn btn-mini" href="javascript:void(0);"><i class="icon-cog "></i></a>
          <a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#" style='line-height:15px;'><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="?p=$p&update&item=$id" class='tip' title='Clique para editar o ítem selecionado'><i class="icon-pencil"></i> Editar</a></li>
            <li><a href="#rm-modal{$id}" class='tip' data-toggle='modal' title="Clique para remover o ítem selecionado"><i class="icon-trash"></i> Deletar</a></li>
			<li><a href="?p=$p&status&item=$id&noVisual" class='tip status status$id'  alt='{$altStatus}' title='Clique para alterar o status do ítem selecionado' id='$id' name='$titulo'>{$descStatus}</a></li>
          </ul>
		</div>
end;


?>
	<div class="modal fade" id="rm-modal<?=$id?>">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Remoção</h3>
		</div>
		<div class="modal-body">
		<p>Deseja remover <b><?=$titulo?></b>?<div class='alert alert-warning small'>Ele será removido permanentemente!</div></p>
		</div>
		<div class="modal-footer">
			<a href="javascript:void(0);" class="btn" data-dismiss='modal'>Cancelar</a>
			<a href="index.php?p=<?=$p?>&delete&item=<?=$id?>&noVisual<?=$delete_images?>" id='<?=$id?>' class="btn-rm btn btn-danger btn-primary">Remover</a>
		</div>
	</div>
	<tr id="tr<?=$id?>">
		<td>
			<center>
				<?php 
						$arquivofull = substr($var['path_original'],0).'/'.$imagem;
						$arquivo = substr($var['path_thumb'],0).'/'.$imagem;
				?>
					<a id='ima<?=$j?>' href="$im<?=$j?>?width=100%" class="betterTip" target='_blank' style="cursor:pointer;">
						<img src="images/lupa.gif">
					</a>
					<div id="im<?=$j?>" style="float:left;display:none">
						<?php 
							if (is_file($arquivo)) 
								echo "<img src='{$arquivo}'>";
							else 
								echo 'sem foto';
						?>
					</div>
			</center>
		</td>
		<td>
			<?=$data?>
		</td>
		<td>
			<?=$titulo?>
			<div class='row-actions muted small'><?=$row_actions?></div>
		</td>
	</tr>
<?php
     $j++;
    }

    $qry->close();
?>
    </tbody>
    </table>
