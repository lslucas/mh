<?php
  
  foreach($_POST as $chave=>$valor) {
   $res[$chave] = $valor;
  }

# include de mensagens do arquivo atual
 include_once 'inc.exec.msg.php';


 $sql_valida = "SELECT ${var['pre']}_nome FROM ".TABLE_PREFIX."_${var['path']} WHERE ${var['pre']}_nome=?";
 $qry_valida = $conn->prepare($sql_valida);
 $qry_valida->bind_param('s', $res['nome']); 
 $qry_valida->execute();
 $qry_valida->store_result();

  if ($qry_valida->num_rows<>0 && $act=='insert') {
   echo $msgDuplicado;

 $qry_valida->close();


  } else {

     #autoinsert
     include_once $rp.'inc.autoinsert.php';


     $sql= "UPDATE ".TABLE_PREFIX."_${var['path']} SET

  		  ${var['pre']}_nome=?,
		  ${var['pre']}_nome_singular=?,
		  ${var['pre']}_nome_plural=?,
		  ${var['pre']}_genero=?,
		  ${var['pre']}_path=?,
		  ${var['pre']}_pre=?,
		  ${var['pre']}_icone=?,
		  ${var['pre']}_display=?
		  ";
     $sql.=" WHERE ${var['pre']}_id=?";
     $qry=$conn->prepare($sql);
     $qry->bind_param('sssssssii', $res['nome'], $res['nome_singular'], $res['nome_plural'], $res['genero'], $res['path'], $res['pre'], $res['icone'], $res['display'],$res['item']); 
     $qry->execute();
     $qry->close();



   if ($qry==false) echo $msgExiste;
    else echo $msgSucesso;
   

  }

// mostra listagem
include_once 'list.php';
