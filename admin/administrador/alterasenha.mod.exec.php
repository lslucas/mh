<?php
  
  foreach($_POST as $chave=>$valor) {
   $res[$chave] = $valor;
  }

	/*
	 *classe de password
	 */
	include_once '_inc/class.password.php';


	if(empty($res['senha']) || empty($res['confirma_senha']) || empty($res['senha_atual']))
		echo divAlert('Preencha <b>todos</b> os campos antes de avançar!');

	else {

		$password = new Password;
		$res['senha_atual']= $password->hash($res['senha_atual'], 'mcrypt', SITE_NAME.'salt');
		$res['nova_senha'] = $password->hash($res['senha'], 'mcrypt', SITE_NAME.'salt');


		if ($res['senha_atual']==$_SESSION['user']['senha'] && $res['senha']==$res['confirma_senha']) {

			$sql= "UPDATE ".TABLE_PREFIX."_${var['path']} SET
			  ${var['pre']}_senha=?";
			$sql.=" WHERE ${var['pre']}_id=?";

			if ($qry=$conn->prepare($sql)) {

				$qry->bind_param('si', $res['nova_senha'],$res['item']); 
				$qry->execute();


				if ($qry==false) echo divAlert('Ocorreu algum erro!');
				else {
					# define nome e email para enviar ao include de email
					$res['email'] = $_SESSION['user']['email'];
					$res['nome']  = $_SESSION['user']['nome'];

					echo divAlert('Sua senha foi alterada!', 'success');
					include_once 'inc.email.php';
				} 


				$qry->close();
			}




		} else
			echo divAlert('Sua atual não confere! Tente novamente.');


	}

include_once 'alterasenha.form.php';
