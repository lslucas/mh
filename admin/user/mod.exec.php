<?php

  foreach($_POST as $chave=>$valor) {
   $res[$chave] = trim($valor);
  }


# include de mensagens do arquivo atual
 include_once 'inc.exec.msg.php';


 ## verifica se existe um titulo/nome/email com o mesmo nome do que esta sendo inserido
 $sql_valida = "SELECT ${var['pre']}_email retorno FROM ".TABLE_PREFIX."_${var['table']} WHERE ${var['pre']}_email=? OR ${var['pre']}_login=? OR ${var['pre']}_cpf=?";
 $qry_valida = $conn->prepare($sql_valida);
 $qry_valida->bind_param('sss', $res['cnpj'], $res['login'], $res['cpf']); 
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
	include_once '_inc/class.password.php';

     $sql= "UPDATE ".TP."_${var['table']} SET

  		  ${var['pre']}_nome=?,
  		  ${var['pre']}_login=?,
  		  ${var['pre']}_email=?,
  		  ${var['pre']}_email_secundario=?,
  		  ${var['pre']}_cpf=?,
  		  ${var['pre']}_telefone1=?,
  		  ${var['pre']}_telefone2=?,
  		  ${var['pre']}_cep=?,
  		  ${var['pre']}_aniversario=?,
  		  ${var['pre']}_pais=?,
  		  ${var['pre']}_bio=?,
  		  ${var['pre']}_nota=?
	";
     $sql.=" WHERE ${var['pre']}_id=?";
	 if (!$qry=$conn->prepare($sql))
		 echo divAlert($conn->error);

	 else {

		$res['aniversario'] = datept2en('/', $res['aniversario']);

		$qry->bind_param('ssssssssssssi',
			$res['nome'],
			$res['login'],
			$res['email'],
			$res['email_secundario'],
			$res['cpf'],
			$res['telefone1'],
			$res['telefone2'],
			$res['cep'],
			$res['aniversario'],
			$res['pais'],
			$res['bio'],
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

				$code = newCode($res['cpf']);
				$qry->bind_param('si', $code, $res['item']);
				$qry->execute();
				$qry->close();

				saveCode($code);
			}
		}

		include_once 'helper/exec.senha.php';

		echo $msgSucesso;

		//listagem
		include_once 'list.php';
	 }

 }

