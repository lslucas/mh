<?php

	if (!empty($val['senha']) || $act=='insert') {

      $senha	= !isset($val['senha']) ? gera_senha(3) : $val['senha'];
	  $password = new Password;
	  $val['senha'] = $password->hash($senha, 'mcrypt', SITE_NAME.'salt');

	   /*
		*ALTERA NOME, EMAIL E SENHA
		*/
		$sql_usr= "UPDATE ".TP."_user SET usr_senha=?";
		$sql_usr.=" WHERE usr_id=?";

		if (!$qry_usr=$conn->prepare($sql_usr))
			echo $conn->error;

		else {
			$qry_usr->bind_param('si', $val['senha'], $val['item']); 
			$qry_usr->execute();
		}

	}

	$val['senha'] = $val['confirma_senha'] = null;
