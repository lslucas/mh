<?php

$conn = @new mysqli(DB_SERVER,DB_USER,DB_PASS,DB_DATABASE);

  if ($conn->connect_errno) 
    die('Não foi possível conectar-se ao banco de dados: '.$conn->connect_error);
  
	/* change character set to utf8 */
	if (!$conn->set_charset("utf8")) {
		printf("Error loading character set utf8: %s\n", $conn->error);
	} 
	/* activate reporting */
	mysqli_report(MYSQLI_REPORT_ERROR);
