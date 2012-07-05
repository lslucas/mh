<?php
	include_once 'helper/list.php';
?>
<h1><?=$var['mono_plural']?></h1>
<p class='header'></p>
<div class='small' align='right'><?=$total?></div>

<div style='display:inline-block; width:100%;'>

	<div class="btn-group btn-mini" style='float:right; padding:0px;'>
	  <button class="btn btn-mini">Ordernar por</button>
	  <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
		<span class="caret"></span>
	  </button>
	  <ul class="dropdown-menu">
		<li><a href="?p=<?=$p.$pag.$letter?>&orderby=<?=$var['pre'].'data'?> ASC"<?php if($orderby==$var['pre'].'_data ASC') echo ' selected';?>">Data Crescente</a></li>
		<li><a href="?p=<?=$p.$pag.$letter?>&orderby=<?=$var['pre'].'_data'?> DESC"<?php if($orderby==$var['pre'].'_data DESC') echo ' selected';?>">Data Decrescente</a></li>
		<li><a href="?p=<?=$p.$pag.$letter?>&orderby=<?=$var['pre'].'_titulo'?> ASC"<?php if($orderby==$var['pre'].'_titulo ASC') echo ' selected';?>">Título Crescente</a></li>
		<li><a href="?p=<?=$p.$pag.$letter?>&orderby=<?=$var['pre'].'_titulo'?> DESC"<?php if($orderby==$var['pre'].'_titulo DESC') echo ' selected';?>">Título Decrescente</a></li>
	  </ul>
	</div>

</div>

<table class="table table-condensed table-striped">
   <thead> 
      <tr>
        <th width="25px"></th>
        <th width="60px">Data</th>
        <th>Título</th>
      </tr>
   </thead>  
   <tbody>
<?php

    $j=0;

    while ($qry->fetch()) {


$delete_images = "&prefix=r_${var['pre']}_galeria&pre=rng&col=imagem&folder=${var['imagem_folderlist']}";

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
			<?php if (!empty($categoria)) { ?>
			<span class='label'><?=$categoria?></span>
			<?php } ?>
			<?=$titulo?>
			<blockquote><small><?=strip_tags($resumo)?></small></blockquote>
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
	<div id='modalDisciplina' class='modal fade hide'></div>


	  <?php
        /*
         *paginação
         */
        #$nav_cat       = isset($catid)?'&cat='.$catid:'';
		$queryString = preg_replace("/(\?|&)?(pg=[0-9]{1,})/",'',$_SERVER['QUERY_STRING']);
        $nav_cat='&'.$queryString;

	      $nav_nextclass = $pg_atual==$n_paginas?'unstyle ':'';
	      $nav_nexturl   = $pg_atual==$n_paginas?'javascript:void(0)':'?pg='.($pg_atual+1).$nav_cat;

		  echo "<div class='spacer' style='height:30px;'></div>";
	      echo "<span style='float:left'>";
	      echo "  <a href='${nav_nexturl}' class='${nav_nextclass}navbar more'>Mais ítens</a>";
	      echo "</span>";


	      echo "<span style='float:right'>";

	      $nav_prevclass = $pg_atual==1?'unstyle ':'';
	      $nav_prevurl   = $pg_atual==1?'javascript:void(0)':'?pg=1'.$nav_cat;
	
	      echo "<a href='${nav_prevurl}' class='${nav_prevclass}navbar prev'>Anterior</a>";
	

	    for($p=1;$p<=$n_paginas;$p++) {

	      $nav_class = $pg_atual<>$p?'':'unstyle ';
	      $nav_url   = $pg_atual==$p?'javascript:void(0)':'?pg='.$p.$nav_cat;
	  ?>
	  <a href='<?=$nav_url?>' class='<?=$nav_class?> navbar'><?=$p?></a>
	  <?php

	    }

	    echo "<a href='${nav_nexturl}' class='${nav_nextclass}navbar next'>Próximo</a>";
	    echo "</span>";
	  ?>
	</div>
