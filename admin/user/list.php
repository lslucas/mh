<?php
	include_once 'helper/list.php';
?>
<h1><?=$var['mono_plural']?></h1>
<p class='header'></p>
<div class='small' align='right'><?=$total?></div>

<div style='display:inline-block; width:100%;'>

	<div style='float:left; margin-right: 20px; width:600px;'>
		<p class='small' style='float:left;'>
		Filtrar por &nbsp; <?=$letras?>
		</p>
	</div>

	<div style='float:right; width:90px; text-align:right; '>

		<div class="btn-group">
		  <button class="btn btn-mini">Ordernar por</button>
		  <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
			<span class="caret"></span>
		  </button>
		  <ul class="dropdown-menu">
			<li><a href="?p=<?=$p.$pag.$letter?>&orderby=<?=$var['pre'].'_timestamp'?> ASC"<?php if($orderby==$var['pre'].'_timestamp ASC') echo ' selected';?>">Data Crescente</a></li>
			<li><a href="?p=<?=$p.$pag.$letter?>&orderby=<?=$var['pre'].'_timestamp'?> DESC"<?php if($orderby==$var['pre'].'_timestamp DESC') echo ' selected';?>">Data Decrescente</a></li>
			<li><a href="?p=<?=$p.$pag.$letter?>&orderby=<?=$var['pre'].'_nome'?> ASC"<?php if($orderby==$var['pre'].'_nome ASC') echo ' selected';?>">Nome Crescente</a></li>
			<li><a href="?p=<?=$p.$pag.$letter?>&orderby=<?=$var['pre'].'_nome'?> DESC"<?php if($orderby==$var['pre'].'_nome DESC') echo ' selected';?>">Nome Decrescente</a></li>
		  </ul>
		</div>

	</div>


</div>
<div align=left>
		<form name='search' action='<?=$_SERVER['PHP_SELF']?>' method='get' class='form form-horizontal'>
			<input type='hidden' name='p' value='<?=$p?>'/>
			<input type='text' name='q' class='input-large' placeholder='Nome, Email, CPF ou Code' value='<?=isset($_GET['q']) ? $_GET['q'] : null?>'>
			<input type='submit' value='buscar' class='btn btn-primary'>
			<div style='float:right;'>
				<a href='<?=$var['path']?>/helper/xls.php' target='_blank' class='btn btn-mini'>Exportar Excel</a>
			</div>

		</form>
</div>
<table class="table table-condensed table-striped">
   <thead> 
      <tr>
        <th width="20px">--</th>
        <th>Nome</th>
        <th>Login</th>
        <th>Telefone</th>
        <th>CEP/País</th>
        <th width="70px">Nascimento</th>
        <th width="70px">Cadastro</th>
      </tr>
   </thead>  
   <tbody>
<?php

    $j=0;

    while ($qry->fetch()) {

$delete_images = null;
//$delete_images = "&prefix=r_${var['pre']}_galeria&pre=rng&col=imagem&folder=${var['imagem_folderlist']}";
$statusOnLabel = "<font color=#000000>Ativo</font>"; 
$statusOnIcon = "<i class=icon-eye-open></i> ";
$statusOffLabel =  "<font color=#999999>Bloqueado</font>";
$statusOffIcon = "<i class=icon-eye-close></i> ";
$altStatus = '{"ativo": "'.$statusOnIcon.$statusOnLabel.'", "inativo": "'.$statusOffIcon.$statusOffLabel.'"}';

if ($status==1)
	$descStatus = $statusOnIcon.$statusOnLabel;
else
	$descStatus = $statusOffIcon.$statusOffLabel;

$row_actions = <<<end
		<div class="btn-group">
          <a class="btn btn-mini" href="javascript:void(0);"><i class="icon-cog"></i></a>
          <a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);" style='line-height:15px;'><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="?p=$p&update&item=$id" class='tip' title='Clique para editar o ítem selecionado'><i class="icon-pencil"></i> Editar</a></li>
            <li><a href="#rm-modal{$id}" class='tip' data-toggle='modal' title="Clique para remover o ítem selecionado"><i class="icon-trash"></i> Deletar</a></li>
			<li><a href="?p=$p&status&item=$id&noVisual" class='tip status status$id'  alt='{$altStatus}' title='Clique para alterar o status do ítem selecionado' id='$id' name='$nome'>{$descStatus}</a></li>
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
		<p>Deseja remover <b><?=$nome?></b>?<div class='alert alert-warning small'>Ele será removido permanentemente!</div></p>
		</div>
		<div class="modal-footer">
			<a href="javascript:void(0);" class="btn" data-dismiss='modal'>Cancelar</a>
			<a href="index.php?p=<?=$p?>&delete&item=<?=$id?>&noVisual<?=$delete_images?>" id='<?=$id?>' class="btn-rm btn btn-danger btn-primary">Remover</a>
		</div>
	</div>
	<tr id="tr<?=$id?>">
		<td>
		</td>
		<td>
			<?php
				echo "<span class='label'>{$code}</span>\t";

				if (!empty($email))
					echo "<a href='mailto:<?=$email?>'>";

				echo $nome;

				if (!empty($email))
					echo "</a>";

				echo "\n<br/>{$cpf}";

				echo $row_actions;
			?>
		</td>
		<td>
			<?=$login?>
		</td>
		<td>
			<?=$telefone1?>
		</td>
		<td>
			<?=$cep.' - '.$pais?>
		</td>
		<td>
			<?=$nascimento?>
		</td>
		<td>
			<?=$dt_cadastro?>
		</td>
	</tr>
<?php
     $j++;
    }

    $qry->close();
?>
    </tbody>
    </table>


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
