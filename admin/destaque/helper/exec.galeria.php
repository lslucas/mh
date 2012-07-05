<?php
 if (isset($_FILES) && isset($_FILES['midia']['name']) && is_file($_FILES['midia']['tmp_name'])) {

  include_once "_inc/class.upload.php";
   $sqlImagem = '';
   $w=$pos=0;


	/*
	 *Verifica se já existe alguma mídia
	 */
   /*
	$sql_s = "SELECT ${var['pre']}_midia FROM ".TABLE_PREFIX."_${var['table']} WHERE ${var['pre']}_id=? LIMIT 1";
	$qry_s = $conn->prepare($sql_s);
	$qry_s->bind_param('i', $res['item']);
	$qry_s->execute();
	$qry_s->bind_result($midia);
	$qry_s->fetch();
	$qry_s->close();
	*/

	//remove imagem se existir
	$res['pre'] = $var['pre'];
	$res['col'] = 'id';
	$res['prefix'] = $var['table'];
	include_once 'del.galeria.php';

	$sql= "
			UPDATE ".TABLE_PREFIX."_${var['table']}
				SET {$var['pre']}_midia=?
			WHERE {$var['pre']}_id=?";

	if (!$qry=$conn->prepare($sql))
		echo $conn->error;

	else {


		if (isset($_FILES['midia']['name']) && is_file($_FILES['midia']['tmp_name']) ) {

			$filename = $res['item'].'_'.rand();
			$tipo = 'Imagem';
			$handle = new Upload($_FILES['midia']);

			if ($handle->uploaded) {
				$handle->file_new_name_body  = $filename;
				$handle->Process($var['path_original']);
				#$handle->jpeg_quality        = 90;
				if (!$handle->processed) echo 'error : ' . $handle->error;

				$handle->file_new_name_body  = $filename;
				$handle->image_resize        = true;
				#$handle->image_ratio_x        = true;
				$handle->image_ratio_crop    = true;
				$handle->image_x             = $var['imagemWidth'];
				$handle->image_y             = $var['imagemHeight'];
				$handle->process($var['path_imagem']);
				if (!$handle->processed) echo 'error : ' . $handle->error;

				$handle->file_new_name_body  = $filename;
				$handle->image_resize        = true;
				$handle->image_ratio_crop    = true;
				$handle->image_x             = $var['thumbWidth'];
				$handle->image_y             = $var['thumbHeight'];
				$handle->process($var['path_thumb']);
				if (!$handle->processed) echo 'error : ' . $handle->error;

				$handle->file_new_name_body  = $filename;
				$handle->image_resize        = true;
				$handle->image_ratio_crop    = true;
				$handle->image_x             = $var['revistaWidth'];
				$handle->image_y             = $var['revistaHeight'];
				$handle->process($var['path_revista']);
				if (!$handle->processed) echo 'error : ' . $handle->error;

				$handle->file_new_name_body  = $filename;
				$handle->image_resize        = true;
				$handle->image_ratio_crop    = true;
				$handle->image_x             = $var['bannerRevistaWidth'];
				$handle->image_y             = $var['bannerRevistaHeight'];
				$handle->process($var['path_bannerRevista']);
				if (!$handle->processed) echo 'error : ' . $handle->error;

				$imagem = $handle->file_dst_name;


				$qry->bind_param('si', $imagem, $res['item']);
				$qry->execute();
				$qry->close();

			}

		}

	}

 }
