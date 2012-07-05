<?php
  
  foreach($_POST as $chave=>$valor) {
   $res[$chave] = $valor;
  }

# include de mensagens do arquivo atual
 include_once 'inc.exec.msg.php';
 include_once '_inc/class.password.php';


 $sql_valida = "SELECT ${var['pre']}_nome,${var['pre']}_email FROM ".TABLE_PREFIX."_${var['path']} WHERE ${var['pre']}_email=?";
 $qry_valida = $conn->prepare($sql_valida);
 $qry_valida->bind_param('s', $res['email']); 
 $qry_valida->execute();
 $qry_valida->store_result();

  if ($qry_valida->num_rows<>0 && $act=='insert') {
   echo $msgDuplicado;

 $qry_valida->free_result();
 $qry_valida->close();

  } else {


	$res['senha']=gera_senha(5);
	$password   = new Password;
	$res['senha_md5'] = $password->hash($res['senha'], 'mcrypt', SITE_NAME.'salt');
	$res['tipo'] = 'Administrador';


   if ($act=='insert') {

     $sql= "INSERT INTO ".TABLE_PREFIX."_${var['path']} 

  		  (${var['pre']}_email,
		   ${var['pre']}_nome,
		   ${var['pre']}_tipo,
		   ${var['pre']}_senha)
		  VALUES
		  (?, ?, ?, ?)";
     $qry=$conn->prepare($sql);
     $qry->bind_param('ssss', $res['email'], $res['nome'], $res['tipo'], $res['senha_md5']); 
     $qry->execute();
     $qry->close();


     $sql_id = "SELECT ${var['pre']}_id item FROM ".TABLE_PREFIX."_${var['path']} WHERE ${var['pre']}_email=\"".$res['email']."\"";
     $qry_id = $conn->query($sql_id);
     $result = $qry_id->fetch_array(); 
     $qry_id->close(); 

     $res['item'] = $result['item'];


    } else {

		 $sql= "UPDATE ".TABLE_PREFIX."_${var['path']} SET

			  ${var['pre']}_email=?,
			  ${var['pre']}_nome=?";
		 $sql.=" WHERE ${var['pre']}_id=?";
		 $qry=$conn->prepare($sql);
		 $qry->bind_param('ssi', $res['email'], $res['nome'],$res['item']); 
		 $qry->execute();
		 $qry->close();

     }



   #insere os módulos do adm
   include_once 'mod.exec.modulos.php';
   include_once 'inc.email.php';


   if ($qry==false) echo $msgExiste;
    else echo $msgSucesso;
   
  }


// mostra listagem
include_once 'list.php';
