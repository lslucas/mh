<?php
   /*
    *cabeçalho para funcoes,variaveis e conexao com a base
    */
	session_start();
	$rp = !isset($rp) ? 'admin/' : $rp;
	include_once $rp.'_inc/global.php';
	require_once $rp.'_inc/db.php';
	include_once $rp.'_inc/global_function.php';


	$uniq = !isset($_SESSION['busca']['uniq']) ? md5(date('YmdHis')) : $_SESSION['busca']['uniq'];
	$host = $_SERVER['HTTP_HOST'];
	$uri  = substr($_SERVER['REQUEST_URI'], 1);

	$backslash_pos = strpos($uri, '/');

	/*
	*verifica se está em localhost
	*/
	if( $host=='localhost' ) {
		define('ABSPATH', '/'.substr($uri, 0, $backslash_pos+1));
		define('ABSPATHMOBILE', '/m/'.substr($uri, 0, $backslash_pos+1));
		$uri = substr($uri, $backslash_pos+1);

	} else {
		define('ABSPATH', '/'); #.substr($uri, 0, $backslash_pos)
		define('ABSPATHMOBILE', '/m/'); #.substr($uri, 0, $backslash_pos)

	}

	$ABSPATH = ABSPATH;


	/*
	*separa tudo que tiver /
	*/
	$url = explode('/', $uri);

	//nome do arquivo = $basename.php
	$basename = $url[0];
	$querystring = array_shift($url);
	$querystring = implode('&', $url);
	$querystring = count($url)>0 ? $querystring: '';

	$QueryString = preg_replace('|[&]|', '/', $querystring);
	$QueryString = str_replace('/p1-Normal', '', $QueryString);
	$QueryString = str_replace('/p1-Thumb', '', $QueryString);
	#echo $basename.$querystring;

	if (strpos($querystring, '&') ) {
		$_tmp = explode('&', $querystring);
		$item = $_tmp[0];
	} else
		$_tmp = array($querystring);



