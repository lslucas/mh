<?php
  
  foreach($_GET as $chave=>$valor) {
   $res[$chave] = $valor;
  }

 $col = 'status';
 //$col = isset($_GET['status']) ? 'status' : (isset($_GET['fraude']) ? 'fraude' :  (isset($_GET['contactado']) ? 'contactado' :  null));
 $sql_guarda = "SELECT ${var['pre']}_nome_fantasia, ${var['pre']}_{$col} FROM ".TABLE_PREFIX."_${var['path']}";
 $sql_guarda.= " WHERE ${var['pre']}_id=?";
 if (!$qry_guarda = $conn->prepare($sql_guarda))
	 echo $conn->error;

 else {

	 $qry_guarda->bind_param('i', $res['item']); 
	 $ok = $qry_guarda->execute()==true?true:false;
	 $num = $qry_guarda->num_rows();
	 $qry_guarda->bind_result($nome,$status); 
	 $qry_guarda->fetch(); 
	 $qry_guarda->close();


	 if ($ok) {

		 $novoStatus  = $status==1?0:1;

		 if (isset($_GET['status']))
			 $novoStatusT = $status==1?'Bloqueado':'Ativo';
		 elseif (isset($_GET['fraude']))
			 $novoStatusT = $status==1?'Marcado como não fraude':'Marcado como fraude';
		 elseif (isset($_GET['contactado']))
			 $novoStatusT = $status==1?'NÃO Contactado':'Contactado';

		 $sql_status  = "UPDATE ".TABLE_PREFIX."_${var['path']} SET ${var['pre']}_{$col}=${novoStatus}";
		 $sql_status .= " WHERE ${var['pre']}_id=?";
		 $qry_status  = $conn->prepare($sql_status);
		 $qry_status->bind_param('s', $res['item']); 

			 if ($qry_status->execute()) {

				/*
				 *log
				 */
				 if (isset($_GET['status'])) 
					 $acao = 'Status: '.$novoStatusT;
				 elseif (isset($_GET['fraude'])) 
					 $acao = 'Fraude: '.$novoStatusT;
				 elseif (isset($_GET['contactado'])) 
					 $acao = 'Contactado: '.$novoStatusT;
				 $antes = $col." = {$status}";
				 $depois = $col." = {$novoStatus}";
				 logextended($acao, $p, array('antes'=>$antes, 'depois'=>$depois, 'log_id'=>$log_id));


				echo "<b>${nome}</b> agora está <b>${novoStatusT}</b>";
		 }

	   $qry_status->close();

	 }


 }
