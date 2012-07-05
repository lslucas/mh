<?php

  foreach($_GET as $chave=>$valor) {
   $res[$chave] = $valor;
  }


    $var['path_imagem']   = PATH_IMG.'/'.$var['path'];
    $var['path_original'] = PATH_IMG.'/'.$var['path'].'/original';
    $var['path_revista'] = PATH_IMG.'/'.$var['path'].'/revista';
    $var['path_bannerRevista'] = PATH_IMG.'/'.$var['path'].'/banner_revista';
    $var['path_thumb']    = PATH_IMG.'/'.$var['path'].'/thumb';

    $var['folder'] = $var['path_imagem'].','.$var['path_thumb'].','.$var['path_original'].','.$var['path_revista'].','.$var['path_bannerRevista'];
	$res['folder'] = $var['folder'];
    $res['ref'] = $res['pre'].'_id';



 $sql_field = $res['pre'].'_'.$res['col'];
 $sql_guarda = "SELECT ${res['pre']}_id id,$sql_field field FROM ".TABLE_PREFIX."_${res['prefix']} WHERE ${res['ref']}=?";

 $qry_guarda = $conn->prepare($sql_guarda);
 $qry_guarda->bind_param('i',$res['item']);
 $ok = $qry_guarda->execute()?true:false;
 $qry_guarda->bind_result($id,$field);
 $num = $qry_guarda->num_rows();

   $row_id=$row_field='';
   while($qry_guarda->fetch()) { 
    $row_id   .= $id.',';
    $row_field.= $field.',';
   }

  $row_id=substr($row_id,0,-1);
  $row['id'] = explode(',',$row_id);

  $row_field=substr($row_field,0,-1);
  $row['field'] = explode(',',$row_field);



 $qry_guarda->close();



 if(isset($_GET['verifica'])) {

/*
      if ($num==0)
       echo 'Arquivo já não existe!';

       else
	echo 'não removido';
*/
	echo $num;


  } elseif ($ok) {

     $sql_rem = "UPDATE ".TABLE_PREFIX."_${res['prefix']} SET ${res['pre']}_midia=NULL WHERE ${res['pre']}_id=?";
     $qry_rem = $conn->prepare($sql_rem);

	 #variaveis de contagem de arquivos apagados ou nao
	 $apagado = $nao_apagado = $erro_apagar = 0;

	 for($i=0;$i<count($row['id']);$i++) { 
	  if (!empty($row['id'][$i])) {


           $id  = $row['id'][$i];
           $arq = $row['field'][$i];


	     $qry_rem->bind_param('i',$id);
	     $qry_rem->execute();


		if ($qry_rem) {

		     $folder = explode(',',$res['folder']);
		     for($j=0;$j<count($folder);$j++) {
		      $arquivo = $folder[$j].'/'.$arq;

			if (!empty($folder[$j]) && is_file($arquivo)) {

			 unlink($arquivo);
			 $unlink_ok = 1;

			} else $unlink_no = 1;

		     }


		   if(isset($unlink_ok)) $apagado=$apagado+1;
		   if(isset($unlink_no)) $nao_apagado = $nao_apagado+1;


		} else
		  $erro_apagar = $erro_apagar+1;


	}
      } #fecha for 

	 /*
      if($apagado==1)
       echo "foto apagada!<br>";
       elseif($apagado>1) echo $apagado." fotos apagadas!<br>";


      if($nao_apagado==1)
       echo "foto <b>não</b> já não existe!<br>";
       elseif($nao_apagado>1) echo $nao_apagado." foto já não existem!<br>";


      if($erro_apagar==1)
       echo "Erro ao tentar apagar!<br>";
       elseif($erro_apagar>1) echo $erro_apagar." erros ao tentar apagar!<br>";
	  */


     $qry_rem->close();



 } else
	echo "Não foi possível remover o arquivo!<br>";

