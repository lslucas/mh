<?php

  foreach($_POST as $chave=>$valor) {
   $res[$chave] = $valor;
  }


# include de mensagens do arquivo atual
 include_once 'inc.exec.msg.php';

 ## verifica se existe um titulo/nome/email com o mesmo nome do que esta sendo inserido

 $sql_valida = "SELECT ${var['pre']}_titulo FROM ".TABLE_PREFIX."_${var['path']} WHERE ${var['pre']}_titulo=?";
 $qry_valida = $conn->prepare($sql_valida);
 $qry_valida->bind_param('s', $res['titulo']); 
 $qry_valida->execute();
 $qry_valida->store_result();

  #se existe um titulo/nome/email assim nao passa
  if ($qry_valida->num_rows<>0 && $act=='insert') {
   echo $msgDuplicado;
   $qry_valida->close();


  #se nao existe faz a inserção
  } else {

     #autoinsert
     include_once $rp.'inc.autoinsert.php';


	 if($res['area']<>'Modelo')
		 $res['marca'] = $res['sobre_modelo'] = null;


    $sql= "UPDATE ".TABLE_PREFIX."_${var['path']} SET

            ${var['pre']}_titulo=?,
            ${var['pre']}_idrel=?,
            ${var['pre']}_estoque=?,
            ${var['pre']}_qtd_fotos=?,
            ${var['pre']}_premium=?,
            ${var['pre']}_sobre_modelo=?,
            ${var['pre']}_area=?
          	";
     $sql.=" WHERE ${var['pre']}_id=?";
     $qry=$conn->prepare($sql);
     $qry->bind_param('siiiissi', $res['titulo'], $res['idrel'], $res['estoque'], $res['qtd_fotos'], $res['premium'], $res['sobre_modelo'], $res['area'], $res['item']); 
     $qry->execute();


   if ($qry==false) echo $msgExiste;
    else {
     
     $qry->close();
     #insere as fotos/galeria do artigo
	 #se a area for apenas igual a modelo cadastra imagem
	 if($res['area']=='Marca') {
		include_once 'mod.exec.galeria.php';
	 }
    
     echo $msgSucesso;

    }

 }

// mostra listagem
include_once 'list.php';
