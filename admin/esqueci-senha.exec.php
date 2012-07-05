<?php

	$res['email'] = isset($_REQUEST['email']) ? trim($_REQUEST['email']) : null;
	if (empty($res['email']) || !validaEmail($res['email']))
		echo divAlert('Email inválido!');

	else {

		$sql = "SELECT adm_id, adm_nome, adm_email FROM ".TABLE_PREFIX."_administrador";
		$sql.=" WHERE adm_email=?";

		if (!$qry=$conn->prepare($sql))
			echo divAlert('Houve um erro na tentativa de realizar a consulta de cadastro! Contate o desenvolvedor.');
			//die($conn->error);

		else {

			$qry->bind_result($item, $nome, $email);
			$qry->bind_param('s', $res['email']);
			$qry->execute();
			$qry->store_result();
			$num = $qry->num_rows;
			$qry->fetch();
			$qry->close();

			if ($num==0)
				echo divAlert("Usuário não existe!");

			else {

				/*
				 *classe de password
				 */
				include_once '_inc/class.password.php';

				$password = new Password;
				$res['senha']=gera_senha(2);
				$res['senha_md5']= $password->hash($res['senha'], 'mcrypt', SITE_NAME.'salt');


				/*
				 *grava nova senha na tabela
				 */
				$sql= "UPDATE ".TABLE_PREFIX."_administrador SET adm_senha=? WHERE adm_id=?";
				if (!$qry=$conn->prepare($sql))
					echo divAlert($conn->error);

				else {
					$qry->bind_param('si', $res['senha_md5'], $item); 
					$qry->execute();
					$qry->close();
				}


				/*
				 *html do email
				 */
				$email_subject = SITE_NAME.": Senha Alterada!";
				$msg = $user_email_header;
				$msg .= "
					 Olá ".$nome.", abaixo seus dados de acesso:

					 <p><b>Usuário:</b> ".$email."
					 <br><b>Senha:</b> ".$res['senha']." 
					 <br><b>URL:</b> <a href='".PAINEL_URL."' target='_blank'>".PAINEL_URL."</a>
					";
				$msg .= $user_email_footer;


				/*
				 *vars to send a email
				 */
				$htmlMensage= utf8_decode($msg);
				$subject	= utf8_decode($email_subject);
				$fromEmail	= EMAIL;
				$fromName	= utf8_decode(SITE_NAME);
				$toName		= utf8_decode($nome);
				$toEmail	= $email;

				include_once 'inc.sendmail.header.php';

				if ($sended)
					echo divAlert('Senha enviada com sucesso!', 'success');

				else
					echo divAlert('NÃO foi enviada ao email '.$email, 'warning');


			}

		}
	}
