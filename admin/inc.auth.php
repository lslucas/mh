<?php
 #inicia a sessao
 session_start();

	#se houver um post da pagina de login
	if (isset($_POST['username'])) {

		$user = (string)$_POST['username'];
		$user = trim($user);;


		/*
		 *verifica login
		 */
		$sql_var = "SELECT
							adm_id,
							adm_nome,
							adm_email,
							adm_tipo,
							adm_senha

						FROM ".TABLE_PREFIX."_administrador
						WHERE adm_status=1 AND adm_email=?
						LIMIT 1
					";

		if (!$qry_var = $conn->prepare($sql_var))
			echo $conn->error;

		else {

			$qry_var->bind_param('s', $user);
			$qry_var->execute();
			$qry_var->bind_result($aid, $anome, $aemail, $atipo, $asenha);
			$qry_var->fetch();
			$qry_var->close();

			/*
			 *classe de password
			 */
			include_once '_inc/class.password.php';

			$password   = new Password;
			$pass = trim($_POST['password']);
			$hash = $password->hash($pass, 'mcrypt', SITE_NAME.'salt');


			if ( $asenha<>$hash ) { 

			 $noMenu = $noLog = 1; 
			 $classNotice = 'error';
			 $msgNotice = "Usuário ou senha invalido! Tente novamente ou entre em contato com o ";
			 $msgNotice.= "<a href=\"mailto:".ADM_EMAIL."?subject=Login inválido\">administrador</a>.";



					 } elseif (empty($user) || empty($hash)) {

				   $noMenu = $noLog = 1; 
				   $msgError = "Preencha <b>todos</b> os campos antes de prosseguir!";


						#usuario ou senha inválidos
				} else {

					$userdata = array(
						'id' => $aid,
						'nome' => $anome,
						'email' => $aemail,
						'senha' => $asenha,
						'tipo' => $atipo,
						'ip' => $_SERVER['REMOTE_ADDR'],
						'host' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
						'useragent' => $_SERVER['HTTP_USER_AGENT']
					);
					$_SESSION['user'] = $userdata;


					/*
					 *OPT AGENCIA
					 */
					$_SESSION['user']['qtd_fotos'] = $_SESSION['user']['estoque'] = $_SESSION['user']['publicar'] = null;
					if ($_SESSION['user']['tipo']=='Agência') {

						$sql_age = "SELECT age_plano, age_publicar FROM ".TABLE_PREFIX."_agencia WHERE age_adm_id='{$_SESSION['user']['id']}'";
						$qry_age = $conn->query($sql_age);
						$agerow = $qry_age->fetch_array();
						$qry_age->close();

						$sql_opt = "SELECT cat_titulo, cat_qtd_fotos, cat_estoque, cat_premium FROM ".TABLE_PREFIX."_categoria WHERE cat_id='{$agerow['age_plano']}'";
						$qry_opt = $conn->query($sql_opt);
						$optrow = $qry_opt->fetch_array();
						$qry_opt->close();

						$_SESSION['user']['plano']	   = $optrow['cat_titulo'];
						$_SESSION['user']['qtd_fotos'] = $optrow['cat_qtd_fotos'];
						$_SESSION['user']['estoque']   = $optrow['cat_estoque'];
						$_SESSION['user']['premium']   = $optrow['cat_premium'];
						$_SESSION['user']['publicar']  = $agerow['age_publicar'];

					}



					if ($_SESSION['user']['tipo']=='Agência')
						$reff = 'index.php?p=auto';
					else
						$reff = (basename($_SERVER['HTTP_REFERER'])<>$rp.'/') ? $_SERVER['HTTP_REFERER'] : 'default.php';
					header("Location: ".$reff);  
					die();



				}
		}


	 } else {

	  	 #CHECA AS PERMISSOES DO USUÁRIO
		 function hasAccess() {
			 global $conn,$p,$sess,$msgNotice,$classNotice,$noMenu,$err;

			 $add=0;
			 if (!isset($_SESSION['user']) && !isset($_GET['act'])) {
			   $noMenu=1;
			   #$classNotice = 'error';
			   #$msgNotice   = 'Você não está logado ou sua sessão expirou!';
			   
			 } 
			 if (!isset($_SESSION['user']) && isset($_GET['act']) && $_GET['act']=='logout') {
			   $noMenu=1;
			   $classNotice = 'success';
			   $msgNotice   = "Você não está mais logado!";
			 }



			if (isset($p) && !empty($p) && $p!='esqueci-senha' && !isset($_GET['esqueci-senha']) && !isset($err)) {

				$sql_cat = "SELECT null,mod_id
						FROM ".TABLE_PREFIX."_r_adm_mod 
					  	  INNER JOIN ".TABLE_PREFIX."_modulo
						    ON ram_mod_id=mod_id
					 	WHERE ram_adm_id=?
					  	  AND mod_nome=?
					";
				$qr_cat = $conn->prepare($sql_cat);
				$qr_cat->bind_param('is',$_SESSION['user']['id'],$p);
				$qr_cat->execute();
				$num_cat = $qr_cat->num_rows();
				$qr_cat->close();

				$add = $add+$num_cat;
				if ( isset($p) && $p=='alterar_senha') $add=1;

				if ($add==0) {
			  	 $msgError = '<center>Você não tem permissão para acessar essa página, consulte o <a href="mailto:'.ADM_EMAIL.'?subject='.SITE_NAME.': Permissões ['.$_SESSION['user']['email'].']">administrador</a> para maiores informações.</center>';
				}
				
		  	     }

				 if ($add==0) return false;
						else return true;
		 }




		# funcao de login
		hasAccess();
		logquery();

 }
