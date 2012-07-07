<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
	<title><?=isset($pg_title) ? $pg_title : SITE_NAME?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Lucas Serafim - lucasserafim.com.br | lslucas@gmail.com">

	<link href="<?=$rph?>public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=CSS?>application.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	<link rel="shortcut icon" href="<?=STATIC_PATH?>favicon.png">
  </head>
  <body>


    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
		  <a class="brand" href="<?=$rph?>"><span id='brand-spacer'></span><?=SITE_NAME?></a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="<?=$rph?>">Home</a></li>
              <li><a href="<?=$rph?>cadastre-se">Cadastre-se</a></li>
              <li><a href="<?=$rph?>biz">Empresa</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">


