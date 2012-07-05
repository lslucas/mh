<?php
	/*
	 *envio de email
	 */
	$sended = $err = null;
	if (!isset($subject))
		$err .= 'Informe o subject<br/>';
	if (!isset($fromEmail))
		$err .= 'Informe o fromEmail<br/>';
	if (!isset($fromName))
		$err .= 'Informe o fromName<br/>';
	if (!isset($toEmail))
		$err .= 'Informe o toEmail<br/>';
	if (!isset($toName))
		$err .= 'Informe o toName<br/>';
	if (!isset($htmlMensage))
		$err .= 'Informe o htmlMensage<br/>';

	if (!is_null($err))
		echo $err;

	else {

		require_once 'Zend/Loader/Autoloader.php';
		Zend_Loader_Autoloader::getInstance();


		$config = array('auth' => MAIL_SMTPAUTH,
						'username' => MAIL_USER,
						'password' => MAIL_PASS,
						'port' => MAIL_PORT);
		if (MAIL_SMTPSECURE<>null)
			$config['ssl'] = MAIL_SMTPSECURE;

		$transport = new Zend_Mail_Transport_Smtp(MAIL_HOST, $config);
		Zend_Mail::setDefaultTransport($transport);


		$mail = new Zend_Mail();
		$mail->setSubject($subject);
		$mail->setFrom($fromEmail, $fromName);
		$mail->addTo($toEmail, $toName);


		if(isset($bccEmail) && !empty($bccEmail)) $mail->addBcc($bccEmail, $bccName);
		if(isset($bcc2Email) && !empty($bcc2Email)) $mail->addBcc($bcc2Email, $bcc2Name);
		if(isset($bcc3Email) && !empty($bcc3Email)) $mail->addBcc($bcc3Email, $bcc3Name);
		if(BBC1_EMAIL<>'') $mail->addBcc(BBC1_EMAIL, BBC1_NOME);
		if(BBC2_EMAIL<>'') $mail->addBcc(BBC2_EMAIL, BBC2_NOME);
		if(BBC3_EMAIL<>'') $mail->addBcc(BBC3_EMAIL, BBC3_NOME);
		if(BBC4_EMAIL<>'') $mail->addBcc(BBC4_EMAIL, BBC4_NOME);
		$mail->setBodyHtml($htmlMensage);

		if (isset($attach) && is_array($attach)) {
			foreach ($attach as $num=>$file) {
				$at = new Zend_Mime_Part(file_get_contents($file));
				$at->filename = basename($file);
				$at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
				$at->encoding = Zend_Mime::ENCODING_8BIT;
						
				$mail->addAttachment($at);
			}
		}


		$sended = $mail->send();

	}
