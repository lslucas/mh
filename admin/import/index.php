<?php

	$rp = '../';
	$_GET['p'] = 'categoria';
	$act=$res['act']='insert';
	include_once $rp.'_inc/global.php';
	include_once $rp.'_inc/db.php';
	include_once $rp.'_inc/global_function.php';
	include_once $rp.'categoria/mod.var.php';
    include_once '../_inc/Excel/reader.php';
	include_once 'functions.php';

    $excel = new Spreadsheet_Excel_Reader();
    $excel->read('marcasmodeloschassi.xls');    


    $x=1;
	$sheet=$chassi=array();
    while($x<=$excel->sheets[0]['numRows']) {
		if (isset($excel->sheets[0]['cells'][$x][1]) && !empty($excel->sheets[0]['cells'][$x][1])) {

			$marca = isset($excel->sheets[0]['cells'][$x][1]) ? trim($excel->sheets[0]['cells'][$x][1]) : null;
			$marca = strip_tags(utf8_encode($marca));

			$modelo = isset($excel->sheets[0]['cells'][$x][2]) ? trim($excel->sheets[0]['cells'][$x][2]) : null;
			$modelo = strip_tags(utf8_encode($modelo));

			$carroceria = isset($excel->sheets[0]['cells'][$x][3]) ? trim($excel->sheets[0]['cells'][$x][3]) : null;
			$carroceria = strip_tags(utf8_encode($carroceria));

			$sheet[$x]['marca'] = $marca;
			$sheet[$x]['modelo'] = $modelo;
			$chassi[$x] = $carroceria;

		}
		/*
		  while($y<=$excel->sheets[0]['numCols']) {
			$cell = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';
			$y++;
		  }
		 */
      $x++;
	}
	$chassi = array_unique($chassi);
	//var_dump($chassi);
	//var_dump($sheet);
	//grava na base
	foreach ($sheet as $int=>$res)
		insCat($res['marca'], 'Marca');

	foreach ($sheet as $int=>$res)
		insCat($res['modelo'], 'Modelo', $res['marca']);

	foreach ($chassi as $int=>$cha)
		insChassi($cha);
