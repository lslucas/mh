<?php

	if (!empty($res['senha']) || $act=='insert') {

      $senha	= !isset($res['senha']) ? gera_senha(3) : $res['senha'];
	  $password = new Password;
	  $res['senha'] = $password->hash($senha, 'mcrypt', SITE_NAME.'salt');
	   /*
		*ALTERA NOME, EMAIL E SENHA
		*/
       $sql_usr= "UPDATE ".TP."_{$var['table']} SET {$var['pre']}_senha=?";
       $sql_usr.=" WHERE {$var['pre']}_id=?";
	   if (!$qry_usr=$conn->prepare($sql_usr))
		   echo $conn->error;

	   else {
		   $qry_usr->bind_param('si', $res['senha'], $res['item']); 
		   $qry_usr->execute();
	   }

	}

	//include_once 'inc.email.php';
