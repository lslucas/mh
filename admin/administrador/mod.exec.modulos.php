<?php
 if(isset($res['item'])) { 

   $sql_dmod = "DELETE FROM ".TABLE_PREFIX."_r_adm_mod WHERE ram_adm_id=?";
   $qry_dmod = $conn->prepare($sql_dmod);
   $qry_dmod->bind_param('i', $res['item']); 
   $qry_dmod->execute();
   $qry_dmod->close();

 }



   $sql_imod = "INSERT INTO ".TABLE_PREFIX."_r_adm_mod 

		(ram_adm_id,
		 ram_mod_id)
		VALUES
		(?,
		 ?)";
   $qry_imod = $conn->prepare($sql_imod);


   for ($i=0;$i<count($_POST['mod_id']);$i++) {

      $qry_imod->bind_param("ii",$res['item'], $_POST['mod_id'][$i]);
      $qry_imod->execute();

   }


   $qry_imod->close();
?>
