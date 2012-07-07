<?php

	//inc user
	$_GET['p'] = $p = 'user';
	include_once 'admin/user/mod.var.php';


	/*
	 *RESGATA VALORES DE POST
	 */
	$res = array();
	foreach($_POST as $chave=>$valor)
		$val[$chave] = trim($valor);


	// vars
	$act = isset($val['item']) ? 'update' : 'insert';
	$msgErr = $msgExiste = null;


	/*
	 *VALIDATE DATA
	 */
	$err = null;
	if (empty($val['nome']))
		$err .=  '<b>Nome</b> inválido!<br/>';

	if (empty($val['email']) || !validaEmail($val['email']))
		$err .=  '<b>Email</b> inválido!<br/>';
	/*
	if (!empty($val['email_secundario']) && (!validaEmail($val['email_secundario']) || $val['email_secundario']==$val['email']))
	$err .= "<b>Email Secundário</b> inválido ou igual ao email principal (ele tem de ser diferente)!<br/>";
	*/

	if (empty($val['cpf']) || !validaCPF($val['cpf']))
		$err .=  '<b>CPF</b> inválido!<br/>';

	if (empty($val['telefone1']) && empty($val['telefone2']))
		$err .=  'Informe ao menos um <b>Telefone</b>!<br/>';

	if (!isset($val['cidade']) || empty($val['cidade']))
		$err .=  'Preencha sua <b>Cidade</b>!<br/>';

	if (!isset($val['estado']) || empty($val['estado']))
		$err .=  'Preencha sua <b>Estado</b>!<br/>';

	if ($act=='insert' && (empty($val['senha']) || empty($val['senha_confirma'])))
		$err .=  'Precisamos que você preencha a <b>Senha e Confirmação de Senha</b>!<br/>';

	if (!empty($val['senha']) && !empty($val['senha_confirma']) && $val['senha']<>$val['senha_confirma'])
		$err .=  'A <b>Confirmação de Senha</b> deve ser idêntica a senha informada!<br/>';
	else {
		$res['senha'] = $val['senha'];
		$res['senha_confirma'] = $val['senha_confirma'];
		$res['senha_atual'] = isset($val['senha_atual']) ? $val['senha_atual'] : null;;
	}


	if (!empty($err)) {
		$incjQuery .= showModal(array('title'=>'Dados incompletos!', 'content'=>'Preencha todos os campos abaixo antes de continuar:<br/><br/>'.$err));
		 $incMsg .= "<div class='alert alert-error'>Preencha todos os campos abaixo antes de continuar:<br/><br/>{$err}</div>";

	} else {

		include_once 'admin/_inc/class.password.php';

		## verifica se existe um titulo/nome/email com o mesmo nome do que esta sendo inserido
		$sql_valida = "SELECT usr_email retorno FROM ".TP."_user WHERE usr_email=?";
		$qry_valida = $conn->prepare($sql_valida);
		$qry_valida->bind_param('s', $val['email']); 
		$qry_valida->execute();
		$qry_valida->store_result();

		#se existe um titulo/nome/email assim nao passa
		if ($qry_valida->num_rows<>0 && $act=='insert') {
			$incMsg .= "
			   <br/>
				<div class='alert alert-error'>
					<a class='close' data-dismiss='alert'>×</a>
					Já existe um usuário com o e-mail <b>- {$val['email']} -</b>
					<br>
					<p class='small'>
						<a href='javascript:history.back(-1);'>Volte e preencha novamente</a>
					</p>
				</div>\n
		   ";
			$qry_valida->close();


		#se nao existe faz a inserção
		} else {

			 #autoinsert
			 include_once 'admin/inc.autoinsert.php';

			 $qry=false;
			 $sql= "UPDATE ".TABLE_PREFIX."_${var['path']} SET

				  ${var['pre']}_nome=?,
				  ${var['pre']}_nome_limpo=?,
				  ${var['pre']}_email=?,
				  ${var['pre']}_cpf=?,
				  ${var['pre']}_telefone1=?,
				  ${var['pre']}_telefone2=?,
				  ${var['pre']}_pais=?,
				  ${var['pre']}_cidade=?,
				  ${var['pre']}_estado=?,
				  ${var['pre']}_ip=?
			";
			 $sql.=" WHERE ${var['pre']}_id=?";
			 if (!$qry=$conn->prepare($sql))
				 $incMsg .= "<div class='alert alert-error'>".$conn->error."</div>";

			 else {

					$val['nome_limpo'] = removeAcentos($val['nome']);
					$val['email']	   = strtolower($val['email']);
					$val['ip']		   = $_SERVER['REMOTE_ADDR'];
					$val['pais']	   = 'Brasil';

					$qry->bind_param('ssssssssssi',
					 $val['nome'],
					 $val['nome_limpo'],
					 $val['email'],
					 $val['cpf'],
					 $val['telefone1'],
					 $val['telefone2'],
					 $val['pais'],
					 $val['cidade'],
					 $val['estado'],
					 $val['ip'],
					 $res['item']); 
					$qry->execute();
					$qry->close();

					include_once 'inc.code.php';
					include_once 'inc.senha.php';
					//include_once 'sendmail.php';

					$nomeAcao = $act=='insert' ? 'você foi registrado':'seu cadastro foi atualizado';
					$incMsg .= "
					<br/>
					<div class='alert alert-success'>
						<a class='close' data-dismiss='alert'>×</a>
						<b>$val[nome]</b> $nomeAcao com êxito!
					</div>	 
					 ";

					/*
					$_POST['email'] = $val['email'];
					$_POST['senha'] = $senha;
					$from = 'cadastre-se';
					include_once 'auth.php';

					$usr = $_SESSION[TP]['user'];
					unset($_POST['email'], $_POST['senha']);

					include_once 'consultorio-sentimental.php';
					include_once '_inc.footer.php';
					 */

				 }

			}

	}

