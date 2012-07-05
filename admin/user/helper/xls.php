<?php

  include_once '../../_inc/global.php';
  include_once '../../_inc/db.php';
  include_once '../../_inc/global_function.php';




   /*
    *pega o nome da festa e verifica se ela existe
    */
	$row = array();
	$sql = "SELECT 
				usr_id,
				usr_code,
				usr_nome,
				usr_email,
				usr_login,
				usr_email_secundario,
				usr_cpf,
				usr_senha_temporaria,
				usr_bio,
				usr_telefone1,
				usr_telefone2,
				usr_cep,
				usr_pais,
				usr_nota,
				usr_status,
				DATE_FORMAT(usr_aniversario, '%d/%m/%Y') aniversario,
				DATE_FORMAT(usr_timestamp, '%d/%m/%Y %H:%i') dt_cadastro
               FROM ".TP."_user 
			   WHERE 1 ORDER BY usr_timestamp DESC";

	if (!$qry = $conn->prepare($sql))
		echo $conn->error;

	else {

		$qry->bind_result(
			$id,
			$code,
			$nome,
			$email,
			$login,
			$email_secundario,
			$cpf,
			$senha_temporaria,
			$bio,
			$telefone1,
			$telefone2,
			$cep,
			$pais,
			$nota,
			$status,
			$aniversario,
			$dt_cadastro
		);
		$qry->execute();

			while($qry->fetch()) {
				$row[$id]['code'] = $code;
				$row[$id]['nome'] = $nome;
				$row[$id]['email'] = $email;
				$row[$id]['login'] = $login;
				$row[$id]['email_secundario'] = $email_secundario;
				$row[$id]['cpf'] = $cpf;
				$row[$id]['senha_temporaria'] = $senha_temporaria;
				$row[$id]['bio'] = $bio;
				$row[$id]['telefone1'] = $telefone1;
				$row[$id]['telefone2'] = $telefone2;
				$row[$id]['cep'] = $cep;
				$row[$id]['pais'] = $pais;
				$row[$id]['nota'] = $nota;
				$row[$id]['status'] = $status;
				$row[$id]['aniversario'] = $aniversario;
				$row[$id]['dt_cadastro'] = $dt_cadastro;

			}

		$qry->close();

	}




   if(count($row)==0)
     die('Nenhum cadastro na lista!');

   else {


      function cleanData($str) {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        return html_entity_decode($str);
      }

      # file name for download
      $filename = "users_".date('d-m-Y').".xls";

      header("Content-Disposition: attachment; filename=\"$filename\"");
      header("Content-Type: application/vnd.ms-excel; charset=utf-8");


	  if(count($row)>0) {

			# display field/column names as first row
			echo "Lista de Cadastros\n";
			echo "Gerado em ".date('d/m/Y H:i')."\n\n";

			echo "Code\t";
			echo "Nome\t";
			echo "Email\t";
			echo "Login\t";
			echo "Email Secundário\t";
			echo "CPF\t";
			echo "Senha Temporaria\t";
			echo "BIO\t";
			echo "Telefone 1\t";
			echo "Telefone 2\t";
			echo "CEP\t";
			echo "País\t";
			echo "Nota\t";
			echo "Cod. Status\t";
			echo "Aniversário\t";
			echo "Data/Hora Cadastro\t";
			echo "\n";

			$i=$totalValor=0;
			foreach ($row as $id=>$arr) {

				echo cleanData($arr['code'])."\t";
				echo cleanData($arr['nome'])."\t";
				echo cleanData($arr['email'])."\t";
				echo cleanData($arr['login'])."\t";
				echo cleanData($arr['email_secundario'])."\t";
				echo cleanData($arr['cpf'])."\t";
				echo cleanData($arr['senha_temporaria'])."\t";
				echo cleanData($arr['bio'])."\t";
				echo cleanData($arr['telefone1'])."\t";
				echo cleanData($arr['telefone2'])."\t";
				echo cleanData($arr['cep'])."\t";
				echo cleanData($arr['pais'])."\t";
				echo cleanData($arr['nota'])."\t";
				echo cleanData($arr['status'])."\t";
				echo cleanData($arr['aniversario'])."\t";
				echo cleanData($arr['dt_cadastro'])."\t";
				echo "\n";
				$totalValor++;

			}

			echo "\n";
			echo "\n";
			echo cleanData("Total de Cadastros até agora: {$totalValor}")."\t";

	  }



   }
