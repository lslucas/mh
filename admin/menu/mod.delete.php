<?php
  foreach($_GET as $chave=>$valor) {
   $res[$chave] = $valor;
  }

 $sql_guarda = "SELECT ${var['pre']}_nome FROM ".TABLE_PREFIX."_${var['path']} WHERE ${var['pre']}_id=?";
 $qry_guarda = $conn->prepare($sql_guarda);
 $qry_guarda->bind_param('i', $res['item']); 
 $ok = $qry_guarda->execute()==true?true:false;
 $num = $qry_guarda->num_rows();
 $qry_guarda->bind_result($nome); 
 $qry_guarda->fetch(); 
 $qry_guarda->close();



 if(isset($_GET['verifica'])) {

/*
      if ($num==0)
       echo 'removido';

       else
	echo 'não removido';
*/
	echo $num;


  } elseif ($ok) {

	      $sql_rem = "DELETE FROM ".TABLE_PREFIX."_${var['path']} WHERE ${var['pre']}_id=?";
	      $qry_rem = $conn->prepare($sql_rem);
	      $qry_rem->bind_param('i', $res['item']); 

		if ($qry_rem->execute()) {
		  echo "<b>${nome}</b> removido com êxito!";
		  #echo "<p><input type='button' value='fechar' onClick='parent.$.fancybox.close();'></p>";
		}

	$qry_rem->close();

       

 } else {
   echo "Não foi possível remover <b>${nome}</b>";
   #echo "<p><input type='button' value='fechar' onClick='parent.$.fancybox.close();'></p>";
 }

?>
