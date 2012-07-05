<?php

   $_GET['p'] = 'auto';
   include_once '../../_inc/global.php';
   include_once '../../_inc/db.php';
   include_once '../../_inc/global_function.php';
   include_once '../mod.var.php';


  $and=null;
  if(isset($_POST['marca']))
	$and = " AND cat_idrel={$_POST['marca']}";

  $sql_modelo = "SELECT cat_titulo, cat_id FROM ".TABLE_PREFIX."_categoria WHERE cat_status=1 AND cat_area='Modelo'".$and;
  $qry_modelo = $conn->prepare($sql_modelo);
  $qry_modelo->bind_result($nome, $id);
  $qry_modelo->execute();

	  while ($qry_modelo->fetch()) {
?>
	<option value='<?=$id?>'<?php if (isset($_POST['modelo']) && $_POST['modelo']==$id) echo ' selected';?>> <?=$nome?></option>
<?php
	  }

	$qry_modelo->close();
