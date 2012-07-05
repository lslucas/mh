<?php

  include_once '../../_inc/global.php';
  include_once '../../_inc/db.php';
  include_once '../../_inc/global_function.php';




   /*
    *pega o nome da festa e verifica se ela existe
    */
	$row = array();
	$sql = "SELECT 
				biz_id,
				biz_code,
				(SELECT usr_nome FROM ".TP."_user WHERE usr_code=biz_usr_code) usr_nome,
				(SELECT usr_email FROM ".TP."_user WHERE usr_code=biz_usr_code) usr_email,
				biz_nome_fantasia,
				biz_razao_social,
				biz_cnpj,
				biz_endereco,
				biz_estado,
				biz_cidade,
				biz_telefone1,
				biz_telefone2,
				biz_site,
				biz_nota,
				biz_status,
				DATE_FORMAT(biz_timestamp, '%d/%m/%Y %H:%i') dt_cadastro
               FROM ".TP."_biz 
			   WHERE biz_nome_fantasia IS NOT NULL ORDER BY biz_timestamp DESC";

	if (!$qry = $conn->prepare($sql))
		echo $conn->error;

	else {

		$qry->bind_result(
			$id,
			$code,
			$usr_nome,
			$usr_email,
			$nome_fantasia,
			$razao_social,
			$cnpj,
			$endereco,
			$estado,
			$cidade,
			$telefone1,
			$telefone2,
			$site,
			$nota,
			$status,
			$dt_cadastro
		);
		$qry->execute();

			while($qry->fetch()) {
				$row[$id]['code'] = $code;
				$row[$id]['responsavel'] = $usr_nome;
				$row[$id]['email_responsavel'] = $usr_email;
				$row[$id]['nome_fantasia'] = $nome_fantasia;
				$row[$id]['razao_social'] = $razao_social;
				$row[$id]['cnpj'] = $cnpj;
				$row[$id]['endereco'] = $endereco;
				$row[$id]['estado'] = $estado;
				$row[$id]['cidade'] = $cidade;
				$row[$id]['telefone1'] = $telefone1;
				$row[$id]['telefone2'] = $telefone2;
				$row[$id]['site'] = $site;
				$row[$id]['nota'] = $nota;
				$row[$id]['status'] = $status;
				$row[$id]['dt_cadastro'] = $dt_cadastro;

			}

		$qry->close();

	}




   if(count($row)==0)
     die('Nenhuma empresa na lista!');

   else {


      function cleanData($str) {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        return html_entity_decode($str);
      }

      # file name for download
      $filename = "biz_".date('d-m-Y').".xls";

      header("Content-Disposition: attachment; filename=\"$filename\"");
      header("Content-Type: application/vnd.ms-excel; charset=utf-8");


	  if(count($row)>0) {

			# display field/column names as first row
			echo "Lista de Empresas\n";
			echo "Gerado em ".date('d/m/Y H:i')."\n\n";

			echo "Code\t";
			echo "Responsável\t";
			echo "Email do Responsável\t";
			echo "Nome Fantasia\t";
			echo "Razão Social\t";
			echo "CNPJ\t";
			echo "Endereço\t";
			echo "Cidade/UF\t";
			echo "Telefone 1\t";
			echo "Telefone 2\t";
			echo "Site\t";
			echo "Notas\t";
			echo "Cod. Status\t";
			echo "Data/Hora Cadastro\t";
			echo "\n";

			$i=$totalValor=0;
			foreach ($row as $id=>$arr) {

				echo cleanData($arr['code'])."\t";
				echo cleanData($arr['responsavel'])."\t";
				echo cleanData($arr['email_responsavel'])."\t";
				echo cleanData($arr['nome_fantasia'])."\t";
				echo cleanData($arr['razao_social'])."\t";
				echo cleanData($arr['cnpj'])."\t";
				echo cleanData($arr['endereco'])."\t";
				echo cleanData($arr['cidade'].'/'.$arr['estado'])."\t";
				echo cleanData($arr['telefone1'])."\t";
				echo cleanData($arr['telefone2'])."\t";
				echo cleanData($arr['site'])."\t";
				echo cleanData($arr['nota'])."\t";
				echo cleanData($arr['status'])."\t";
				echo cleanData($arr['dt_cadastro'])."\t";
				echo "\n";
				$totalValor++;

			}

			echo "\n";
			echo "\n";
			echo cleanData("Total de Empresas até agora: {$totalValor}")."\t";

	  }



   }
