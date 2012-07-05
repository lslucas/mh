<?php

	foreach($_POST as $chave=>$valor) {
		$res[$chave] = $valor;
	}


	# include de mensagens do arquivo atual
	include_once 'inc.exec.msg.php';


	//autoinsert
	include_once $rp.'inc.autoinsert.php';
	$sql= "UPDATE ".TABLE_PREFIX."_${var['path']} SET

		  ${var['pre']}_data=?,
		  ${var['pre']}_titulo=?,
		  ${var['pre']}_url=?
	";
	$sql.=" WHERE ${var['pre']}_id=?";

	if (!$qry=$conn->prepare($sql))
		 echo $conn->error;

	else {

		$res['data']  = datept2en('/', $res['data']);
		$qry->bind_param('sssi',
			 $res['data'],
			 $res['titulo'],
			 $res['url'],
			 $res['item']); 
		$qry->execute();
		$qry->close();

		//insere as fotos/galeria do artigo e opcionais
		include_once 'helper/exec.galeria.php';
		echo $msgSucesso;

	}

	//mostra listagem de carros
	include_once 'list.php';
