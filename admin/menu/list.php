<?php

$sql = "SELECT men_id,
	       (SELECT mod_nome FROM ".TABLE_PREFIX."_modulo WHERE mod_id=men_modulo_id) modulo,
	       men_nome,
	       men_pai,
	       men_nivel,
	       men_link,
	       men_status FROM ".TABLE_PREFIX."_${var['path']} 
	       WHERE men_pai IS NULL
	    ORDER BY men_nivel,men_nome
	       ";

 if (!$qry = $conn->query($sql)) {
  echo 'Houve algum erro durante a execução da consulta<p class="code">'.$sql.'</p><hr>';

  } else {

?>
<h1><?=$var['mono_plural']?></h1>
<p class='header'></p>

<!--
<select name='actions' class='min'>
  <option value=''>Ações</option>
  <option value='remove'>Remover</option>
  <option value='on'>Ativar</option>
  <option value='off'>Desativar</option>
</select>
<input type='button' value='aplicar' class='min'>
-->
<table class="table table-condensed table-striped">
   <thead> 
      <tr>
        <th width="5px"></th>
        <th>Módulo</th>
        <th width='20px'>Peso</th>
        <th width="55px"></th>
      </tr>
   </thead>  
   <tbody>
<?php

    // Para cada resultado encontrado...
    while ($row=$qry->fetch_assoc()) {
      $id = $row['men_id'];
      $nome = $row['men_nome'];
      $nivel = $row['men_nivel'];
      $link = $row['men_link'];
      $modulo = $row['modulo'];
      $status = $row['men_status'];

# | <a href='$base/$p?item=$id' title="Veja no site" class='tip view' style="cursor:pointer;">Ver</a>

$row_actions = <<<end
<a href='?p=$p&delete&item=$id&noVisual' title="Clique para remover o ítem selecionado" class='tip trash' style="cursor:pointer;" id="${id}" name='$nome'>Remover</a> | <a href="?p=$p&update&item=$id" title='Clique para editar o ítem selecionado' class='tip edit'>Editar</a>
end;


?>
      <tr id="tr<?=$id?>">
        <td><a href='?p=<?=$p?>&insert&pai=<?=$id?>' title="Adicionar um filho para o ítem atual" class='tip'>+</a></td>
        <td><?=$modulo?><div class='row-actions muted small' style='display:inline;margin-left:20px;'><?=$row_actions?></div></td>
        <td><?=$nivel?></td>
        <td align='center'><a href='?p=<?=$p?>&status&item=<?=$id?>&noVisual' title="Clique para alterar o status do ítem selecionado" class='tip status status<?=$id?>' style="cursor:pointer;" id="<?=$id?>" name='<?=$nome?>'><?php if ($status==1) echo'<font color="#000000">Ativo</font>'; else echo '<font color="#999999">Pendente</font>'; ?></a></td>
      </tr>

<?php
      $sql_menu_filho = "SELECT men_id,
			       (SELECT mod_nome FROM ".TABLE_PREFIX."_modulo WHERE mod_id=men_modulo_id) modulo,
			       men_nome,
	       		       men_pai,
			       men_nivel,
			       men_link,
			       men_status
			     FROM ".TABLE_PREFIX."_${var['path']} 
			     WHERE men_pai=".$row['men_id'].' ORDER BY men_nome';
      $qry_menu_filho = $conn->query($sql_menu_filho);

	
	if ($qry_menu_filho->num_rows>0) { 

 	   while ($row_filho = $qry_menu_filho->fetch_assoc()){
	    $id = $row_filho['men_id'];
	    $nome = $row_filho['men_nome'];
	    $nivel = $row_filho['men_nivel'];
	    $link = $row_filho['men_link'];
      	    $status = $row_filho['men_status'];
	    $pai = $row['men_id'];

$row_actions = <<<end
<a href='?p=$p&delete&item=$id&noVisual' title="Clique para remover o ítem selecionado" class='tip trash' style="cursor:pointer;" id="${id}" name='$nome'>Remover</a> | <a href="?p=$p&update&item=$id&pai=$pai" title='Clique para editar o ítem selecionado' class='tip edit'>Editar</a>
end;


?>
      <tr id="tr<?=$id?>">
        <td></td>
        <td>&nbsp;&nbsp;<?=$nome?><div class='row-actions muted small inline'><?=$row_actions?></div></td>
        <td><?=$nivel?></td>
        <td align='center'><a href='?p=<?=$p?>&status&item=<?=$id?>&noVisual' title="Clique para alterar o status do ítem selecionado" class='tip status status<?=$id?>' style="cursor:pointer;" id="<?=$id?>" name='<?=$nome?>'><?php if ($status==1) echo'<font color="#000000">Ativo</font>'; else echo '<font color="#999999">Pendente</font>'; ?></a></td>
      </tr>
<?php
    	} 
   }
?>

<?php
    }

    $qry->close();
?>
    <tbody>
    </table>

<?php

  }
