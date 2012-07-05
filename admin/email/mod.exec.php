<?php

  foreach($_POST as $chave=>$valor) {
   $res[$chave] = trim($valor);
  }


# include de mensagens do arquivo atual
 include_once 'inc.exec.msg.php';


 ## verifica se existe um titulo/nome/email com o mesmo nome do que esta sendo inserido
 $sql_valida = "SELECT ${var['pre']}email retorno FROM ".TABLE_PREFIX."_${var['table']} WHERE ${var['pre']}email=?";
 $qry_valida = $conn->prepare($sql_valida);
 $qry_valida->bind_param('s', $res['email']); 
 $qry_valida->execute();
 $qry_valida->store_result();

  #se existe um titulo/nome/email assim nao passa
  if ($qry_valida->num_rows<>0 && $act=='insert') {
   echo $msgDuplicado;
   $qry_valida->close();


  #se nao existe faz a inserção
  } else {

     #autoinsert
     include_once $rp.'inc.autoinsert.php';

     $sql= "UPDATE ".TABLE_PREFIX."_${var['table']} SET

  		  ${var['pre']}nome=?,
  		  ${var['pre']}email=?
	";
     $sql.=" WHERE ${var['pre']}id=?";
	 if (!$qry=$conn->prepare($sql))
		 echo divAlert($conn->error);

	 else {

		$res['email'] = mb_strtolower($res['email'], 'utf8');
		$qry->bind_param('ssi',
			$res['nome'],
			$res['email'],
			$res['item']
		);
		$qry->execute();
		$qry->close();

		echo $msgSucesso;

		//listagem
		include_once 'list.php';
	 }

 }

