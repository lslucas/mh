<?php

  foreach($_POST as $chave=>$valor) {
   $res[$chave] = trim($valor);
  }


# include de mensagens do arquivo atual
 include_once 'inc.exec.msg.php';


 ## verifica se existe um titulo/nome/email com o mesmo nome do que esta sendo inserido
 $sql_valida = "SELECT ${var['pre']}_nome_fantasia retorno FROM ".TABLE_PREFIX."_${var['table']} WHERE ${var['pre']}_cnpj=?";
 $qry_valida = $conn->prepare($sql_valida);
 $qry_valida->bind_param('s', $res['cnpj']); 
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
  		  ${var['pre']}_usr_code=?,
  		  ${var['pre']}_nome_fantasia=?,
  		  ${var['pre']}_razao_social=?,
  		  ${var['pre']}_cnpj=?,
  		  ${var['pre']}_endereco=?,
  		  ${var['pre']}_estado=?,
  		  ${var['pre']}_cidade=?,
  		  ${var['pre']}_telefone1=?,
  		  ${var['pre']}_telefone2=?,
  		  ${var['pre']}_site=?,
  		  ${var['pre']}_nota=?
	";
     $sql.=" WHERE ${var['pre']}_id=?";
	 if (!$qry=$conn->prepare($sql))
		 echo divAlert($conn->error);

	 else {

		$qry->bind_param('sssssssssssi',
			$res['usr_code'],
			$res['nome_fantasia'],
			$res['razao_social'],
			$res['cnpj'],
			$res['endereco'],
			$res['estado'],
			$res['cidade'],
			$res['telefone1'],
			$res['telefone2'],
			$res['site'],
			$res['nota'],
			$res['item']
		);
		$qry->execute();
		$qry->close();

		/*
		 *salva codigo
		 */
		if ($act=='insert') {
			$sql= "UPDATE ".TP."_${var['table']} SET ${var['pre']}_code=?";
			$sql.=" WHERE ${var['pre']}_id=?";
			if (!$qry=$conn->prepare($sql))
			 echo divAlert($conn->error);

			else {

				$code = newCode($res['usr_code']);
				$qry->bind_param('si', $code, $res['item']);
				$qry->execute();
				$qry->close();

				saveCode($code);
			}
		}

		echo $msgSucesso;

		//listagem
		include_once 'list.php';
	 }

 }

