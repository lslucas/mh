<?php

  include_once '../../_inc/global.php';
  include_once '../../_inc/db.php';
  include_once '../../_inc/global_function.php';




   /*
    *pega o nome da festa e verifica se ela existe
    */
	$row = array();
	$sql_cad = "SELECT 
				id,
				nome,
				email,
				DATE_FORMAT(timestamp, '%d/%m/%Y %H:%i') cadastro
               FROM ".TABLE_PREFIX."_newsletter 
			   WHERE nome IS NOT NULL ORDER BY nome ASC";

	if ($qry_cad = $conn->prepare($sql_cad)){

		$qry_cad->execute();
		$qry_cad->store_result();
		$qry_cad->bind_result($id, $nome, $email, $cadastro);
		$num = $qry_cad->num_rows;

			while($qry_cad->fetch()) {
				$row[$id]['id'] = $id;
				$row[$id]['nome'] = $nome;
				$row[$id]['email'] = $email;
				$row[$id]['cadastro'] = $cadastro;

			}

		$qry_cad->close();

	} else echo $qry_cad->error;




   if($num==0)
     die('Nenhum email na lista!');

   else {


      function cleanData($str) {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        return html_entity_decode($str);
      }

      # file name for download
      $filename = "listadeemails_".date('d-m-Y').".xls";

      header("Content-Disposition: attachment; filename=\"$filename\"");
      header("Content-Type: application/vnd.ms-excel; charset=utf-8");


	  if(count($row)>0) {

			# display field/column names as first row
			echo "Lista de Emails\n";
			echo "Gerado em ".date('d/m/Y H:i')."\n\n";

			echo "Nome\t";
			echo "Email\t";
			echo "Data/Hora Cadastro\t";
			echo "\n";

			$i=$totalValor=0;
			foreach ($row as $id=>$arr) {

				echo cleanData($arr['nome'])."\t";
				echo cleanData($arr['email'])."\t";
				echo cleanData($arr['cadastro'])."\t";
				echo "\n";
				$totalValor++;

			}

			echo "\n";
			echo "\n";
			echo cleanData("Total de Emails até agora: {$totalValor}")."\t";

	  }



   }
