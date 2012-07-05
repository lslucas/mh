<?php

  foreach($_POST as $chave=>$valor) {
   $res[$chave] = $valor;
  }


 $res['data']  = datept2en('/',$res['data']);
 # include de mensagens do arquivo atual
 include_once 'inc.exec.msg.php';


 ## verifica se existe um titulo/nome/email com o mesmo nome do que esta sendo inserido
 $sql_valida = "SELECT {$var['pre']}_titulo retorno FROM ".TABLE_PREFIX."_{$var['table']} WHERE {$var['pre']}_titulo=? AND {$var['pre']}_data=?";
 $qry_valida = $conn->prepare($sql_valida);
 $qry_valida->bind_param('ss', $res['titulo'], $res['data']); 
 $qry_valida->execute();
 $qry_valida->store_result();
 $num_valida = $qry_valida->num_rows;
 $qry_valida->close();

  #se existe um titulo/nome/email assim nao passa
  if ($num_valida<>0 && $act=='insert') {
	echo $msgDuplicado;


  #se nao existe faz a inserção
  } else {

	//autoinsert
	include_once $rp.'inc.autoinsert.php';

	$qry=false;
	$res['texto'] = txt_bbcode($res['texto']);
	$sql= "UPDATE ".TABLE_PREFIX."_${var['path']} SET

  		  ${var['pre']}_cat_id=?,
  		  ${var['pre']}_data=?,
  		  ${var['pre']}_titulo=?,
		  ${var['pre']}_resumo=?,
		  ${var['pre']}_texto=?
	";
	$sql.=" WHERE ${var['pre']}_id=?";

	if (!$qry=$conn->prepare($sql))
		 echo $conn->error;

	else {

		$qry->bind_param('issssi',
			 $res['cat_id'],
			 $res['data'],
			 $res['titulo'],
			 $res['resumo'],
			 $res['texto'],
			 $res['item']); 
		$qry->execute();
		$qry->close();

		//insere as fotos/galeria do artigo e opcionais
		include_once 'helper/exec.galeria.php';
		echo $msgSucesso;

    }

 }

	//mostra listagem de carros
	include_once 'list.php';
