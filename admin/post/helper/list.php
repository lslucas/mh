<?php

	$where = null;

	/*
	 *TOTALITENS + Paginação busca total de itens e faz variaveis de paginação
	 */
	$sql_tot = "SELECT NULL FROM ".TABLE_PREFIX."_${var['path']} $where";
	$qry_tot = $conn->query($sql_tot);
	$total_itens = $qry_tot->num_rows;
	$qry_tot->close();

	//variaveis auxiliares para paginação
	$limit_end   = 50;
	$n_paginas   = ceil($total_itens/$limit_end);
	$pg_atual    = isset($_GET['pg']) && !empty($_GET['pg'])?$_GET['pg']:1;
	$limit_start = ceil(($pg_atual-1)*$limit_end);



	/*
	 *ORDER BY da query principal
	 */
	$orderby = !isset($_GET['orderby'])?$var['pre'].'_data DESC':urldecode($_GET['orderby']);


	/*
	 *QUERY PRINCIPAL, LISTAGEM DE VEICULOS
	 */
	$sql = "SELECT  ${var['pre']}_id,
			(SELECT cat_titulo FROM ".TP."_categoria WHERE cat_id=${var['pre']}_cat_id) categoria,
			${var['pre']}_titulo,
			${var['pre']}_resumo,
			${var['pre']}_status,
			${var['pre']}_data data_en,
			DATE_FORMAT(${var['pre']}_data,'%d/%m/%y') data,
			(SELECT rpg_imagem FROM ".TABLE_PREFIX."_r_${var['pre']}_galeria WHERE rpg_${var['pre']}_id=${var['pre']}_id ORDER BY rpg_pos DESC LIMIT 1) imagem 
			
			FROM ".TABLE_PREFIX."_${var['path']} 
			$where
			ORDER BY ".$orderby." LIMIT {$limit_start}, {$limit_end}";


	if (!$qry = $conn->prepare($sql)) {
	echo 'Houve algum erro durante a execução da consulta<p class="code">'.$sql.'</p><hr>';

	} else {

		$qry->execute();
		$qry->bind_result($id, $categoria, $titulo, $resumo, $status, $data_en, $data, $imagem);
		$qry->store_result();
		$num = $qry->num_rows;


		switch($total_itens) {
			case $total_itens==0: $total = 'Nenhum post';
		break;
			case $total_itens==1: $total = "1 post";
		break;
			default: $total = $total_itens.' posts';
		break;
		}

	}
