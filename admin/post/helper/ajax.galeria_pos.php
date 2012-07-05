<?php

   $_GET['p'] = 'auto';
   include_once '../../_inc/global.php';
   include_once '../../_inc/db.php';
   include_once '../../_inc/global_function.php';
   include_once '../mod.var.php';

  for($i=0;$i<count($_POST['posGaleria']);$i++) {
  
  if (!empty($_POST['posGaleria'][$i])) {
   $id_imagem = $_POST['posGaleria'][$i];
   
    $sql_up = "UPDATE ".TABLE_PREFIX."_r_{$var['pre']}_galeria SET rag_pos=? WHERE rag_id=?";
    $sql_up = $conn->prepare($sql_up);
    $sql_up->bind_param('ii',$i,$id_imagem);
    $sql_up->execute();

  }

 }


