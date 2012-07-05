<?php
#ESSE ARQUIVO SERVE PARA VERIFICAR SE É UMA INSERÇÃO OU UPDATE
#se for inserção na tabela ele auto insere um novo ítem e já pega o id do mesmo
#para que no arquivo de inserção de dados em sí possa somenta realizar um update

  if (!isset($_POST['item']) || isset($_POST['item']) && !is_numeric($_POST['item'])) {

       #verifica se nao foi setado $n que é um numero randomico
	if(!isset($n) || isset($n) && empty($n)) {

		if (isset($_POST['n']) && !empty($_POST['n']))     $n = $_POST['n'];
		  elseif (isset($_GET['n']) && !empty($_GET['n'])) $n = $_GET['n'];
			elseif (isset($res['n']) && !empty($res['n'])) $n = $res['n'];

		#se realmente nao existe $n cria um novo super aleatorio usando 
		#a funcao de gerar senha + o a date e hora atuais em ingles
		if (!isset($n))
		 $n = gera_senha(75).date('Ymdhi');

    }


     #insere um novo campo na tabela atual apenas com o valor de $n
     $sql_ins= "INSERT INTO ".TABLE_PREFIX."_${var['path']} (${var['pre']}_n) VALUES (?)";
     if($qry_ins=$conn->prepare($sql_ins)) {
		 $qry_ins->bind_param('s',$n);
		 $qry_ins->execute();
		 $qry_ins->close();

     #pega o valor de n como referencia para buscar o id da linha criada nesse momento 
     $sql_id = "SELECT ${var['pre']}_id item FROM ".TABLE_PREFIX."_${var['path']} WHERE ${var['pre']}_n='${n}'";
     $qry_id = $conn->query($sql_id);
     $res_id = $qry_id->fetch_array();
     $res['item'] = $res_id['item'];
     $qry_id->close();

	 } else echo $qry_ins->error;


 #se for update já existe id entao...
 } else 
    $res['item'] = $_POST['item'];
