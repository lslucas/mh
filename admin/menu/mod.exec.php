<?php
#define as variaveis para caso elas nao tenham sido
#setadas no formulario
$res['pai']=null;
$res['nivel']=null;
$res['modulo_id']=null;
  
  foreach($_POST as $chave=>$valor) {
   $res[$chave] = $valor;
  }


 if(!is_null($res['modulo_id'])) {

  $sql_mod = "SELECT mod_nome FROM ".TABLE_PREFIX."_modulo WHERE mod_id=".$res['modulo_id'];
  $qry_mod = $conn->query($sql_mod);
  $m=$qry_mod->fetch_array();
  $qry_mod->close();
  $res['nome']=$m['mod_nome'];

 }

# include de mensagens do arquivo atual
 include_once 'inc.exec.msg.php';


 $sql_valida = "SELECT ${var['pre']}_nome FROM ".TABLE_PREFIX."_${var['path']} WHERE ${var['pre']}_nome=? AND ${var['pre']}_pai=?";
 $qry_valida = $conn->prepare($sql_valida);
 $qry_valida->bind_param('si', $res['nome'], $res['pai']); 
 $qry_valida->execute();
 $qry_valida->store_result();

  if ($qry_valida->num_rows<>0 && $act=='insert') {
   echo $msgDuplicado;

 $qry_valida->close();

  } else {


   if ($act=='insert') {
     $sql= "INSERT INTO ".TABLE_PREFIX."_${var['path']} 

  		  (${var['pre']}_nome,
		   ${var['pre']}_pai,
		   ${var['pre']}_modulo_id,
		   ${var['pre']}_link,
		   ${var['pre']}_nivel
		  )
		  VALUES
		  (?,
		   ?,
		   ?,
		   ?,
		   ?
		   )";
     $qry=$conn->prepare($sql);
     $qry->bind_param('siisi', $res['nome'], $res['pai'], $res['modulo_id'], $res['link'],$res['nivel']); 


    } else {
     $sql= "UPDATE ".TABLE_PREFIX."_${var['path']} SET

  		  ${var['pre']}_nome=?,
		  ${var['pre']}_pai=?,
		  ${var['pre']}_modulo_id=?,
		  ${var['pre']}_link=?,
		  ${var['pre']}_nivel=?
	   ";
     $sql.= " WHERE ${var['pre']}_id=?";
     $qry=$conn->prepare($sql);
     $qry->bind_param('siisii', $res['nome'], $res['pai'], $res['modulo_id'], $res['link'],$res['nivel'],$res['item']); 

     }


   $qry->execute();
   $qry->close();


   if ($qry==false) echo $msgExiste;
    else echo $msgSucesso;
   

  }

// mostra listagem
include_once 'list.php';
