 <?php

if (isset($_SESSION['user'])) { 


	$sql_niveis = "SELECT men_nivel 
			FROM ".TABLE_PREFIX."_menu 

			 WHERE men_status=1 
				AND men_nivel IS NOT NULL 
				AND EXISTS(SELECT null FROM ".TABLE_PREFIX."_r_adm_mod WHERE ram_adm_id=\"".$_SESSION['user']['id']."\" AND ram_mod_id=men_modulo_id)
				OR men_status=1
				AND men_nivel IS NOT NULL
				AND ".$_SESSION['user']['id']."=1

			  GROUP BY men_nivel 
			   ORDER BY men_nivel";
	$qry_niveis = $conn->query($sql_niveis);
	$i=0;

	while($nivel = $qry_niveis->fetch_assoc()) {


	  $sql_menu_pai = "SELECT men_id,
				  men_nome,
				  men_link,
				  (SELECT mod_nome FROM ".TABLE_PREFIX."_modulo WHERE mod_id=men_modulo_id) modulo_nome,
				  (SELECT mod_path FROM ".TABLE_PREFIX."_modulo WHERE mod_id=men_modulo_id) path 

				FROM ".TABLE_PREFIX."_menu 
				WHERE men_nivel=".$nivel['men_nivel']." 
				AND men_status=1 
				AND men_nome IS NOT NULL 
				AND EXISTS(SELECT null FROM ".TABLE_PREFIX."_r_adm_mod WHERE ram_adm_id=\"".$_SESSION['user']['id']."\" AND ram_mod_id=men_modulo_id)
				OR men_nivel=".$nivel['men_nivel']." 
				AND men_status=1 
				AND men_nome IS NOT NULL 
				AND ".$_SESSION['user']['id']."=1
				ORDER BY men_nome";
	  $qry_menu_pai = $conn->query($sql_menu_pai);
	  $j=0;



		while ($row=$qry_menu_pai->fetch_assoc()){


		  $sql_menu_filho = "SELECT men_modulo_id, men_nome, men_link, men_nivel, men_pai FROM ".TABLE_PREFIX."_menu WHERE men_pai=".$row['men_id'].' ORDER BY men_nome';
		  $qry_menu_filho = $conn->query($sql_menu_filho);
		  $class_menu=$has_submenu='';



		if ($qry_menu_filho->num_rows>0) { 

		  $class_menu =' has-submenu';
		  $has_submenu='<img src="images/arrow-up.png" border="0" class="arrow-menu up"><img src="images/arrow-down.png" border="0" class="arrow-menu down">';
		  $menu_pai = (!empty($row['men_nome'])) ? $row['men_nome'] : $row['modulo_nome'];
		  $menu_pai = '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$menu_pai.' <b class="caret"></b></a>';

		} elseif (!empty($row['men_link'])) 

			  $menu_pai = (!empty($row['men_nome'])) ? '<a href="'.$row['men_link'].'">'.$row['men_nome'].'</a>' : '<a href="'.$row['men_link'].'">'.$row['modulo_nome'].'</a>';



		if ($j==0)
		  $class_menu.=' menu-top-first';


		/*
		 *html
		 */
		if ($qry_menu_filho->num_rows==0) {
			$active = isset($p) && $p==$row['path'] ? ' class="active"' : null;
			echo "\n\t\t<li{$active}>{$menu_pai}</li>";

		} else {

			$active = isset($p) && $p==$row['path'] ? ' active' : null;
			echo "\n\t\t<li class='dropdown{$active}'>{$menu_pai}";
			echo "\n\t\t<ul class='dropdown-menu'>";

			$_tmpf = null;
			while ($row_filho = $qry_menu_filho->fetch_assoc()){
				//<li class="divider"></li>
				//<li class="nav-header">Nav header</li>
				$pos = $row_filho['men_nivel'];
				$mid = $row_filho['men_modulo_id'];
				$mpai = $row_filho['men_pai'];
				//if ($row_filho['men_nome']=='Lançamento de Notas') {
					//$_tmpf.= "<li class='divider'></li>";
					//$_tmpf.= "<li class='nav-header'>Notas</li>";
					//$_tmpf.= "\n\t\t\t<li><a href='{$row_filho['men_link']}'>{$row_filho['men_nome']}</a></li>";

				//} else
				if ($_SESSION['user']['tipo']=='Agência' && $mpai==46 && $row_filho['men_nome']=='Buscar')
					$_tmpf.= "\n\t\t\t<li><a href='{$row_filho['men_link']}'>{$mid} {$row_filho['men_nome']}</a></li>";
				elseif ($_SESSION['user']['tipo']<>'Agência' || $_SESSION['user']['tipo']=='Agência' && $mpai!=46)
					$_tmpf.= "\n\t\t\t<li><a href='{$row_filho['men_link']}'>{$row_filho['men_nome']}</a></li>";

			}

			echo $_tmpf;;
			echo "\n\t\t</ul>";

		}


	 } 

	}
	$qry_niveis->close();

}
