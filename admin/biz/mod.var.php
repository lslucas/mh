<?php
	## RESGATA VARIAVEIS
	$sql_var = "SELECT mod_nome, mod_nome_singular, mod_nome_plural, mod_genero, mod_path, mod_pre FROM ".TABLE_PREFIX."_modulo WHERE mod_path=?";
	$qry_var = $conn->prepare($sql_var);
	$qry_var->bind_param('s',$p);
	$qry_var->execute();
	$qry_var->bind_result($vr_nome,$vr_singular,$vr_plural,$vr_genero,$vr_path,$vr_pre);
	$qry_var->fetch();
	$qry_var->close();

    $var['pre'] = $vr_pre;
    $var['path'] = $vr_path;
    $var['table'] = $vr_path;
    $var['title'] = $vr_nome;
    $var['mono_singular'] = $vr_singular;
    $var['mono_plural'] = $vr_plural;
    $var['genero'] = $vr_genero;
    $var['novo'] = 'nov'.$vr_genero.' '.$vr_singular;
    $var['um'] = ($vr_genero=='a')?'uma '.$vr_singular:'um '.$vr_singular;
    $var['insert'] = 'Cadastro';
    $var['update'] = 'Alterar '.$vr_singular;


	/*
	 *get all columns
	 */
	$sql_col = "SHOW fields FROM ".TABLE_PREFIX."_{$var['table']}";
	$qry_col = $conn->query($sql_col);

	$arr = array();
	while ($a = $qry_col->fetch_assoc())
		array_push($arr, $a['Field']);

	$qry_col->close();

	$field = array_values($arr);
	$lfield = implode(',',$field);
	$vfield = implode(',$',$field);
	$vfield = '$'.$vfield;
	$vfield = explode(',',$vfield);



	if ($act=='update' || isset($_GET['item'])) {
		$sql_form = "SELECT $lfield FROM ".TABLE_PREFIX."_${var['table']} WHERE ${var['pre']}_id=".intval($_GET['item']);
		$qry_form = $conn->query($sql_form);
		$row = $qry_form->fetch_array();

		$qry_form->close();
	}


	# DEFINE OS VALORES DE CADA CAMPO
	for($i=0;$i<count($field);$i++) {
		$sufix_field = str_replace($var['pre'].'_','',$field[$i]);
		$val[$sufix_field] = isset($row[$field[$i]])?$row[$field[$i]]:'';
	}
