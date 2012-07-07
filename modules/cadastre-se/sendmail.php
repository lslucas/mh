<?php
$msg = $administrador_email_header;
  if ($act=='insert') {
    $email_subject = SITE_NAME.": Seus dados de acesso";
    $msg .= "
	     Olá ".$val['nome'].", agora você tem dados de acesso ao ".SITE_NAME.":

	     <p><b>Usuário:</b> ".$val['email']."
	     <br><b>Senha:</b> ".$senha."
	     <br><b>Painel:</b> <a href='".SITE_URL."' target='_blank'>".SITE_URL."</a>

	     <p>Lembrando que é possível alterar sua senha!</p>";

   } elseif ($act=='alterasenha') {
    $email_subject = SITE_NAME.": Senha alterada";
    $msg .= "
	     Olá ".$val['nome'].", sua senha foi alterada!

	     <p><b>Usuário:</b> ".$val['email']."
	     <br><b>Senha:</b> ".$senha." 
	     <br><b>Painel:</b> <a href='".SITE_URL."' target='_blank'>".SITE_URL."</a>
	    ";
   } else {
    $email_subject = SITE_NAME.": Dados alterados";
    $msg .= "
	     Olá ".$val['nome'].", seus dados foram atualizados!

	     <p><b>Usuário:</b> ".$val['email']."
	     <br><b>Senha:</b> ".$senha." 
	     <br><b>Painel:</b> <a href='".SITE_URL."' target='_blank'>".SITE_URL."</a>
	    ";
   } 
$msg .= $administrador_email_footer;


		/*
		 *vars to send a email
		 */
		$htmlMensage= utf8_decode($msg);
		$subject	= utf8_decode($email_subject);
		$fromEmail	= EMAIL;
		$fromName	= utf8_decode(SITE_NAME);
		$toName		= utf8_decode($val['nome']);
		$toEmail	= $val['email'];


		include_once 'admin/inc.sendmail.header.php';

		if (!$sended)
			echo '<div class="alert alert-warning">Houve um <b>erro</b> ao enviar o email para '.$toEmail.', sua senha é "'.$senha.'" (sem aspas), depois entre em contato com o <a href="mailto:'.ADM_EMAIL.'">administrador</a> e informe o problema.</div>';
