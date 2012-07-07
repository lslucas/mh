<?php

	/**
	 *GET ALL COLS
	*/
	$sql_col = "SELECT * FROM ".TP."_user LIMIT 1";
	$qry_col = $conn->query($sql_col);
	$cols	 = $qry_col->fetch_assoc();
	$qry_col->close();

	$field	= array_keys($cols);
	$lfield = implode(',', $field);
	$vfield = implode(',$', $field);
	$vfield = '$'.$vfield;
	$vfield = explode(',', $vfield);

	/*
	 *SET VAL EMPTY
	 */
	for($i=0; $i<count($field); $i++) {
		$sufix_field		= str_replace('usr_', '', $field[$i]);
		$val[$sufix_field]	= isset($row[$field[$i]]) ? $row[$field[$i]] : '';
	}
