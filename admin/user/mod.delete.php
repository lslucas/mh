<?php
  foreach($_GET as $chave=>$valor) {
   $res[$chave] = $valor;
  }

 $sql_guarda = "SELECT {$var['pre']}_nome, {$var['pre']}_id FROM ".TABLE_PREFIX."_${var['table']} WHERE ${var['pre']}_id=?";
 $qry_guarda = $conn->prepare($sql_guarda);
 $qry_guarda->bind_param('i', $res['item']); 
 $ok = $qry_guarda->execute()?true:false;
 $num = $qry_guarda->num_rows();
 $qry_guarda->bind_result($nome, $id); 
 $qry_guarda->fetch(); 
 $qry_guarda->close();



 if(isset($_GET['verifica']))
	echo $num;


  elseif ($ok) {

		  //log
		  $antes = getFieldAndValues(array('id'=>$res['item'], 'modulo'=>$var['path'], 'pre'=>$var['pre']));

	      $sql_rem = "DELETE FROM ".TABLE_PREFIX."_${var['table']} WHERE ${var['pre']}_id=?";
	      $qry_rem = $conn->prepare($sql_rem);
	      $qry_rem->bind_param('i', $res['item']); 

			if ($qry_rem->execute()) {
				echo "<b>${nome}</b> removido com êxito!";

				//log
				$acao = 'Removido';
				$depois = null;
				logextended($acao, $p, array('antes'=>$antes, 'depois'=>$depois, 'log_id'=>$log_id));

			}

	$qry_rem->close();

       

	# CASO EXISTA REMOVE AS IMAGENS E PDFS 
	if (file_exists($var['path'].'/mod.galeria.delete.php')) 
	 include_once $var['path'].'/mod.galeria.delete.php';
	 
 	if (file_exists($var['path'].'/mod.delete_cupons.php')) 
	 include_once $var['path'].'/mod.delete_cupons.php';
	      
 	if (file_exists($var['path'].'/mod.arquivo.delete.php')) 
	 include_once $var['path'].'/mod.arquivo.delete.php';

 	if (file_exists($var['path'].'/mod.administrador.delete.php')) 
	 include_once $var['path'].'/mod.administrador.delete.php';

 	if (file_exists($var['path'].'/mod.r_adm_mod.delete.php')) 
	 include_once $var['path'].'/mod.r_adm_mod.delete.php';

 } else
   echo "Não foi possível remover <b>${nome}</b>";
