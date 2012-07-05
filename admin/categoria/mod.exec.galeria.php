<?php
 if (isset($_FILES)) {

  include_once "_inc/class.upload.php";
   $sqlImagem = '';
   $w=$pos=0;


   $sql_smod = "SELECT rcg_pos FROM ".TABLE_PREFIX."_r_${var['pre']}_galeria WHERE rcg_${var['pre']}_id=? ORDER BY rcg_pos DESC LIMIT 1";
   $qry_smod = $conn->prepare($sql_smod);
   $qry_smod->bind_param('i',$res['item']);
   $qry_smod->execute();
   $qry_smod->bind_result($pos);
   $qry_smod->fetch();
   $qry_smod->close();
   $pos = ($pos!==0)?$pos=$pos+1:$pos;



       $sql= "INSERT INTO ".TABLE_PREFIX."_r_${var['pre']}_galeria 

		    (rcg_${var['pre']}_id,
		     rcg_imagem,
		     rcg_pos
		     )
		    VALUES
		    (?,
		     ?,
		     ?)";
       $qry=$conn->prepare($sql);
       $qry->store_result();



   for ($i=0;$i<=count($_FILES);$i++) {


       if (isset($_FILES['galeria'.$i]['name']) && is_file($_FILES['galeria'.$i]['tmp_name']) ) {


	 $filename = $res['item'].'_'.rand();
	 $handle = new Upload($_FILES['galeria'.$i]);

	 // then we check if the file has been uploaded properly
	 // in its *temporary* location in the server (often, it is /tmp)
	 if ($handle->uploaded) {
	   $handle->file_new_name_body  = $filename;
	   $handle->Process($var['path_original']);
	   #$handle->jpeg_quality        = 90;
	   if (!$handle->processed) echo 'error : ' . $handle->error;

	   $handle->file_new_name_body  = $filename;
	   $handle->image_resize        = true;
	   $handle->image_ratio_x       = true;
	   //$handle->image_ratio_crop    = true;
	   $handle->image_x             = $var['imagemWidth'];
	   $handle->image_y             = $var['imagemHeight'];
	   $handle->jpeg_quality        = 100;
	   $handle->process($var['path_imagem']);
	   if (!$handle->processed) echo 'error : ' . $handle->error;

	   $handle->file_new_name_body  = $filename;
	   $handle->image_resize        = true;
	   $handle->image_ratio_x       = true;
	   //$handle->image_ratio_crop    = true;
	   //$handle->image_x             = $var['thumbWidth'];
	   $handle->image_y             = $var['thumbHeight'];
	   //$handle->image_max_height    = $var['thumbHeight'];
	   $handle->jpeg_quality        = 100;
	   $handle->process($var['path_thumb']);
	   if (!$handle->processed) echo 'error : ' . $handle->error;


	     $imagem = $handle->file_dst_name;


	 $qry->bind_param('isi', $res['item'],$imagem,$pos); 
	 $qry->execute();
         }
      }

    $pos++;
   }



   $qry->close();


 }
