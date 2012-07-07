<?php

	/*
	 *LOAD BASICS
	 */
	include_once 'load.php';



	/**
	  * 404 NOT FOUND
	 */
	if(!empty($basename) && (!file_exists("public/$basename.php") && !file_exists("public/$basename"))){
		header('HTTP/1.0 404 Not Found');
		include_once 'public/header.php';
		echo "<center>";
		echo "<a href='".ABSPATH."'><img src='".STATIC_PATH."logo-mh.png' border=0></a>";
		echo "<p>Página não encontrada!<br/>Voltar para o <a href='".ABSPATH."'>inicio</a> ou voltar para a <a href='javascript:history.back(-1);'>pagina anterior</a><p>";
		echo "</center>";
		include_once 'public/footer.php';
		die();
	}


	/*
	 *HEADERS AND MODULE HEADERS
	*/
	if(!empty($basename)) {

		//remove extension
		if (strpos($basename, '.')===true) {
			$ext = file_extension($basename);
			$basename = str_replace($ext, '', $basename);
		}

		//remove querystring
		if (strpos($basename, '?')!==false) {
			$baseexplode = explode('?', $basename);
			$basename	 = $baseexplode[0];
		}

		if (file_exists("modules/{$basename}/header.php"))
			include_once "modules/{$basename}/header.php";


	} elseif (file_exists('modules/default/header.php'))
		include_once "modules/default/header.php";


	/*
	 *EXEC SUBMIT
	 */
	if (isset($querystring) && strpos($querystring, 'enviar')!==false)
		include_once "modules/{$basename}/exec.php";



	/**
	 *HTML HEADERS
	 */
	include_once 'public/header.php';

	if(!empty($basename) && file_exists("public/{$basename}.php"))
		include_once "public/{$basename}.php";
	elseif(!empty($basename) && file_exists("public/{$basename}"))
		include_once "public/{$basename}";
	else
		include_once 'public/default.php';


	/**
	 *HTML FOOTER
	*/
	include_once 'public/footer.php';
