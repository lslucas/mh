<?php

function insCat($titulo, $area, $rel=null)
{
	global $conn, $var;

	$idrel=null;
	if (!empty($rel)) {

		$sql = "SELECT {$var['pre']}_id idrel FROM ".TABLE_PREFIX."_{$var['path']} WHERE {$var['pre']}_titulo='{$rel}'";
		$qry = $conn->query($sql);
		$rs = $qry->fetch_array();
		$qry->close();
		$idrel = $rs['idrel'];


	}


	if (!empty($rel) && empty($idrel))
		echo 'O modelo '.$titulo.' <b>NÃO</b> foi inserido porque não foi encontrada referencia com <b>'.$rel.'</b>!<br/>';

	else {


	 ## verifica se existe um titulo/nome/email com o mesmo nome do que esta sendo inserido
	 $sql_valida = "SELECT ${var['pre']}_titulo retorno FROM ".TABLE_PREFIX."_${var['path']} WHERE ${var['pre']}_area=? AND ${var['pre']}_titulo=?";
	 $qry_valida = $conn->prepare($sql_valida);
	 $qry_valida->bind_param('ss', $area, $titulo); 
	 $qry_valida->execute();
	 $qry_valida->store_result();
	 $num = $qry_valida->num_rows;
	 $qry_valida->close();


	  if ($num==0 && !empty($titulo) && $titulo!='-') {

		 $sql= "INSERT INTO ".TABLE_PREFIX."_${var['path']}
			 (
			  ${var['pre']}_titulo,
			  ${var['pre']}_idrel,
			  ${var['pre']}_area
			) VALUES (?, ?, ?)
		";
		 if (!$qry=$conn->prepare($sql))
			 echo $conn->error;

		 else {

			 $qry->bind_param('sis',
				 $titulo,
				 $idrel,
				 $area); 
			 $qry->execute();

		 }


	   if ($qry==false) echo $conn->error;
		else
			echo $area.' - <b>'.$titulo.'</b> cadastrado com êxito<br/>';

	 }

	}

}

function insChassi($titulo)
{
	global $conn, $var;

	$idrel=null;
	$area = "Carroceria";

	 ## verifica se existe um titulo/nome/email com o mesmo nome do que esta sendo inserido
	 $sql_valida = "SELECT ${var['pre']}_titulo retorno FROM ".TABLE_PREFIX."_${var['path']} WHERE ${var['pre']}_area=? AND ${var['pre']}_titulo=?";
	 $qry_valida = $conn->prepare($sql_valida);
	 $qry_valida->bind_param('ss', $area, $titulo); 
	 $qry_valida->execute();
	 $qry_valida->store_result();
	 $num = $qry_valida->num_rows;
	 $qry_valida->close();


	  if ($num==0 && !empty($titulo) && $titulo<>'-') {

		 $sql= "INSERT INTO ".TABLE_PREFIX."_${var['path']}
			 (
			  ${var['pre']}_titulo,
			  ${var['pre']}_area
			) VALUES (?, ?)
		";
		 if (!$qry=$conn->prepare($sql))
			 echo $conn->error;

		 else {

			 $qry->bind_param('ss',
				 $titulo,
				 $area); 
			 $qry->execute();

		 }


	   if ($qry==false) echo $conn->error;
		else
			echo $area.' - <b>'.$titulo.'</b> cadastrado com êxito<br/>';

	 }

}
