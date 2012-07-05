<?php
	foreach($_GET as $chave=>$valor)
		$res[$chave] = $valor;


	$sql_guarda = "SELECT {$var['pre']}_titulo, {$var['pre']}_midia FROM ".TABLE_PREFIX."_${var['path']} WHERE ${var['pre']}_id=?";
	if (!$qry_guarda = $conn->prepare($sql_guarda))
		echo $conn->error;

	else {

		$qry_guarda->bind_param('i', $res['item']); 
		$qry_guarda->execute();
		$num = $qry_guarda->num_rows();
		$qry_guarda->bind_result($nome, $midia); 
		$qry_guarda->fetch(); 
		$qry_guarda->close();



		if(isset($_GET['verifica']))
			echo $num;


		else {


			$sql_rem = "DELETE FROM ".TABLE_PREFIX."_${var['path']} WHERE ${var['pre']}_id=?";
			$qry_rem = $conn->prepare($sql_rem);
			$qry_rem->bind_param('i', $res['item']); 

			if ($qry_rem->execute()) {
				echo "<b>${nome}</b> removido com êxito!";

				$var['path_original'] = PATH_IMG.'/'.$var['path'].'/original';
				$var['path_thumb']    = PATH_IMG.'/'.$var['path'].'/thumb';

				$var['folder'] = $var['path_imagem'].','.$var['path_original'].','.$var['path_thumb'];
				$res['folder'] = $var['folder'];

				$folder = explode(',',$res['folder']);
				for($j=0;$j<count($folder);$j++) {
					$arquivo = $folder[$j].'/'.$midia;
					if (!empty($folder[$j]) && is_file($arquivo))
						unlink($arquivo);

				}
			}

			$qry_rem->close();


			# CASO EXISTA REMOVE AS IMAGENS E PDFS 
			if (file_exists($var['path'].'/helper/del.galeria.php')) 
			include_once $var['path'].'/helper/del.galeria.php';

			if (file_exists($var['path'].'/helper/del.opcionais.php')) 
			include_once $var['path'].'/helper/del.opcionais.php';

		}

	}
