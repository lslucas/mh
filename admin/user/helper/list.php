<?php

  /*
   *busca total de itens e faz variaveis de paginação
   */
  $sql_letras = "SELECT UPPER(LEFT({$var['pre']}_nome, 1)) FROM ".TABLE_PREFIX."_{$var['table']} WHERE 1 GROUP BY LEFT({$var['pre']}_nome, 1) ORDER BY {$var['pre']}_nome";

  if($qry_letras = $conn->prepare($sql_letras)) {

    $qry_letras->execute();
    $qry_letras->bind_result($letra);

	$letras = "<div class='btn-group'>";
    if(!isset($_GET['letra']) || empty($_GET['letra'])) {
    $letras .= '<button class="btn btn-mini">Todos</button>';
    $countLetra = '';

    } else {
      $letras .= "<a class='btn btn-mini' href='?p={$var['path']}'>Todos</a>";
      $countLetra = ' com a letra '.$_GET['letra'];
    }


      while($qry_letras->fetch()) {
        if(!isset($_GET['letra']) || $letra<>$_GET['letra'])
	        $letras .= "<a class='btn btn-mini' href='?p={$var['path']}&letra=${letra}'>";
		else
	        $letras .= "<a class='btn btn-mini' href='javascript:void(0);'>";

        $letras .= $letra;
        $letras .= "</a>  ";
      }

    $letras = substr($letras, 0, -2);
    $qry_letras->close();

  }
  $letras .= "</div>";


  $where = ' WHERE 1';
  $join = null;
  if( isset($_GET['letra']) && !empty($_GET['letra']) ) {
    $where.= " AND ${var['pre']}_nome LIKE '".$_GET['letra']."%' ";
  }

  if( isset($_GET['q']) && !empty($_GET['q']) ) {
	$where.= " AND ( ";
	$where.= " ${var['pre']}_nome LIKE '%".$_GET['q']."%' ";
	$where.= " OR ${var['pre']}_cpf LIKE '%".$_GET['q']."%' ";
	$where.= " OR ${var['pre']}_email LIKE '%".$_GET['q']."%' ";
	$where.= " OR ${var['pre']}_code LIKE '%".$_GET['q']."%' ";
	$where.= ")";
  }

/*
 *busca total de itens e faz variaveis de paginação
 */
$total_itens = 0;
$sql_tot = "SELECT NULL FROM ".TABLE_PREFIX."_${var['table']} {$join} $where";
$qry_tot = $conn->query($sql_tot);
$total_itens = $qry_tot->num_rows;
$limit_end   = 30;
$n_paginas   = ceil($total_itens/$limit_end);
$pg_atual    = isset($_GET['pg']) && !empty($_GET['pg'])?$_GET['pg']:1;
$limit_start = ceil(($pg_atual-1)*$limit_end);

$qry_tot->close();


$orderby = !isset($_GET['orderby'])?$var['pre'].'_timestamp DESC':urldecode($_GET['orderby']);


$sql = "SELECT  ${var['pre']}_id,
		${var['pre']}_code,
		${var['pre']}_nome,
		${var['pre']}_email,
		${var['pre']}_cpf,
		${var['pre']}_login,
		${var['pre']}_telefone1,
		${var['pre']}_cep,
		${var['pre']}_pais,
		${var['pre']}_status,
		DATE_FORMAT(${var['pre']}_aniversario, '%d/%m/%y'),
		DATE_FORMAT(${var['pre']}_timestamp, '%d/%m/%y')
		FROM ".TABLE_PREFIX."_${var['table']}
		{$join}
		$where
    ORDER BY $orderby

    LIMIT $limit_start,$limit_end
    ";

 if (!$qry = $conn->prepare($sql)) {
  echo 'Houve algum erro durante a execução da consulta<p class="code">'.$sql.'</p><hr>';

  } else {

    $qry->execute();
    $qry->bind_result($id, $code, $nome, $email, $cpf, $login, $telefone1, $cep, $pais, $status, $nascimento, $dt_cadastro);


    if($total_itens==0) $total = 'Nenhum cadastro'.$countLetra;
    elseif ($total_itens==1) $total = "1 cadastro".$countLetra;
	else $total = $total_itens.' cadastros'.$countLetra;

  }
