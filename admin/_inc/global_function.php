<?php
/*
 *gera codigo unico
 */
function saveCode($code)
{
	global $conn;

	if (empty($code))
		exit(__FUNCTION__.' Informe um código!');

	$sql = "INSERT INTO `".TP."_generated_codes` (`code`) VALUES (?)";
	if (!$res = $conn->prepare($sql))
		return $conn->error;

	else {
		$res->bind_param('s', $code);
		$res->execute();
		$res->close();

		return true;
	}

}

/*
 *gera codigo unico
 */
function newCode($var=null, $maxchar=6)
{
	global $conn;

	//gera o código e verifica se ele já existe antes de continuar
	do {

		$_code = generateHash($var);
		$_code = justAlphanumeric($_code);
		$code = substr($_code, 0, $maxchar);

		if (strlen($code)!=$maxchar)
			$num=1;
		else {

			$sql = "SELECT NULL FROM `".TP."_generated_codes` WHERE `code`=\"$code\"";
			$res = $conn->query($sql);
			$num = $res->num_rows;

		}

	} while ($num>0);


	return $code;
}

/*
 *retorna valor da coluna
 */
function getListUsers($args=null)
{
	global $conn;

	$whr = null;
	if (isset($args['where']))
		$whr = ' AND '.$args['where'];

	$orderby = null;
	if (isset($args['orderby']))
		$orderby = ' ORDER BY '.$args['orderby'];

	if (!isset($args['cols'])) {

		/*
		 *get all columns
		 */
		$sql_col = "SHOW fields FROM ".TABLE_PREFIX."_user";
		$qry_col = $conn->query($sql_col);

		$arr = array();
		while ($a = $qry_col->fetch_assoc())
			array_push($arr, $a['Field']);

		$qry_col->close();

		$field = array_values($arr);
		$cols = implode(',', $field);
		$vfield = implode(',$', $field);
		$vfield = '$'.$vfield;
		$vfield = explode(',', $vfield);

	} else 
		$cols = $args['cols'];

	/*
	 *query da disciplina
	 */
	$sql = "SELECT {$cols} FROM ".TABLE_PREFIX."_user WHERE 1 {$whr} {$orderby}";
	if(!$qry = $conn->query($sql))
		return false;

	else {

		$val = array();
		$i=0;

		while ($row = $qry->fetch_assoc()) {
			$val[$i] = $row;
			$i++;
		}
		$qry->close();

		return $val;
	}

}

/*
 *gera um hash unico, unique
 */
function generateHash($key, $crypt=false)
{

	$salt = pseudoRandomKey(256);
	$hash = null;
	for ($i=0; $i<100; $i++) {
		 $hash = hash('sha512', $hash.$salt.$key);
	}

	//return $hash;
	if ($crypt)
		return encrypt($hash, $key);
	else
		return $hash;
}

/*
 *RANDOM KEY
 */
function pseudoRandomKey($size, $strong=true)
{

	if (function_exists('openssl_random_pseudo_bytes')) {
		$random = openssl_random_pseudo_bytes($size, $strong);
		openssl_random_pseudo_bytes($size, $strong);
	}

	$sha='';
	$rnd='';

	for ($i=0;$i<$size;$i++) {
		$sha = hash('sha256', $random . mt_rand());
		$char= mt_rand(0, 62);
		$rnd.= chr(hexdec($sha[$char] . $sha[$char+1]));
	}

	return $rnd;

}

/*
 *apenas letras e numeros
 */
function justAlphanumeric($var)
{
   return preg_replace('/[^0-9A-Za-z]/', '', $var);
}

/*
 *retorna apenas os numeros
 */
function justNumbers($var)
{
   return preg_replace('/[^0-9]/', '', $var);
}

/*
 *retorna valor da coluna
 */
function getUrlNoticia($not_id)
{
	global $conn;
	/*
	 *query da disciplina
	 */
	$sql = "SELECT not_titulo ".TABLE_PREFIX."_noticia SET auto_views=auto_views+1 WHERE not_id=?";
	if(!$qry = $conn->prepare($sql))
		return false;

	else {

		$qry->bind_param('i', $not_id);
		$qry->bind_result($titulo);
		$qry->execute();
		$qry->fetch();
		$qry->close();

		return ABSPATH."noticias/{$id}/".linkfy($titulo);
	}

}


function encrypt($_input, $_key='your salt', $_type='mcrypt')
{


  /*
   *if exists mcrypt and $_type is mcrypt
   */
  if (function_exists('mcrypt') && $_type=='mcrypt') {

	  $td = mcrypt_module_open(MCRYPT_TWOFISH256, '', 'ofb', '');
	  $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_BLOWFISH);

	  mcrypt_generic_init($td, $_key, $iv);
	  $encryptedData = mcrypt_generic($td, $_input);
	  mcrypt_generic_deinit($td);
	  mcrypt_module_close($td);

  //else use md5
  } else {

	if(version_compare(PHP_VERSION, '5.0.0', '>='))
	  $bool = true;
	else $bool = false;

	  $encryptedKey  = md5($_key, $bool) . md5($_input, $bool);
	  $encryptedData = md5($encryptedKey, $bool);

  }

	// return generated password
	// enjoy
	return utf8_encode($encryptedData);

}

/*
 *retorna valor da coluna
 */
function getUrlAuto($auto_id)
{
	global $conn;
	/*
	 *query da disciplina
	 */
	$sql = "SELECT (SELECT cat_titulo FROM ".TABLE_PREFIX."_categoria WHERE cat_id=auto_marca AND cat_area='Marca'), auto_nome ".TABLE_PREFIX."_auto WHERE auto_id=?";
	if(!$qry = $conn->prepare($sql))
		return false;

	else {

		$qry->bind_param('i', $auto_id);
		$qry->bind_result($marca, $nome);
		$qry->execute();
		$qry->fetch();
		$qry->close();

		return ABSPATH."veiculo/{$id}/".linkfy($marca.' '.$nome);
	}

}

/*
 *retorna valor da coluna
 */
function plusViews($auto_id)
{
	global $conn;

	$ip = $_SERVER['REMOTE_ADDR'];
	if (!isset($_SESSION[TP]['views'][$ip][$auto_id]) || $_SESSION[TP]['views'][$ip][$auto_id]!=date('Y-m-d')) {

		$sql = "UPDATE ".TABLE_PREFIX."_auto SET auto_views=auto_views+1 WHERE auto_id=?";
		if(!$qry = $conn->prepare($sql))
			return false;

		else {

			$qry->bind_param('i', $auto_id);
			$qry->execute();
			$qry->close();

			$_SESSION[TP]['views'][$ip][$auto_id] = date('Y-m-d');
			return true;
		}
	}

}

/*
 *retorna valor da coluna
 */
function plusTelefone($auto_id)
{
	global $conn;

	$ip = $_SERVER['REMOTE_ADDR'];
	if (!isset($_SESSION[TP]['telefone'][$ip][$auto_id]) || $_SESSION[TP]['telefone'][$ip][$auto_id]!=date('Y-m-d')) {

		/*
		 *query
		 */
		$sql = "UPDATE ".TABLE_PREFIX."_auto SET auto_telefone=auto_telefone+1 WHERE auto_id=?";
		if(!$qry = $conn->prepare($sql))
			return false;

		else {

			$qry->bind_param('i', $auto_id);
			$qry->execute();
			$qry->close();

			$_SESSION[TP]['telefone'][$ip][$auto_id] = date('Y-m-d');
			return true;
		}

	}

}

/*
 *retorna valor da coluna
 */
function plusContato($auto_id)
{
	global $conn;

	$ip = $_SERVER['REMOTE_ADDR'];
	if (!isset($_SESSION[TP]['contato'][$ip][$auto_id]) || $_SESSION[TP]['contato'][$ip][$auto_id]!=date('Y-m-d')) {

		/*
		 *query
		 */
		$sql = "UPDATE ".TABLE_PREFIX."_auto SET auto_contato=auto_contato+1 WHERE auto_id=?";
		if(!$qry = $conn->prepare($sql))
			return false;

		else {

			$qry->bind_param('i', $auto_id);
			$qry->execute();
			$qry->close();

			$_SESSION[TP]['contato'][$ip][$auto_id] = date('Y-m-d');
			return true;
		}

	}

}

/*
 *retorna valor da coluna
 */
function plusBannerViews($ban_id)
{
	global $conn;

	$ip = $_SERVER['REMOTE_ADDR'];
	if (!isset($_SESSION[TP]['banner_views'][$ip][$ban_id]) || $_SESSION[TP]['banner_views'][$ip][$ban_id]!=date('Y-m-d')) {

		$sql = "UPDATE ".TABLE_PREFIX."_banner SET ban_views=ban_views+1 WHERE ban_id=?";
		if(!$qry = $conn->prepare($sql))
			return false;

		else {

			$qry->bind_param('i', $ban_id);
			$qry->execute();
			$qry->close();

			$_SESSION[TP]['banner_views'][$ip][$ban_id] = date('Y-m-d');
			return true;
		}
	}

}

/*
 *retorna valor da coluna
 */
function plusBannerClicks($ban_id)
{
	global $conn;

	$ip = $_SERVER['REMOTE_ADDR'];
	if (!isset($_SESSION[TP]['banner_clicks'][$ip][$ban_id]) || $_SESSION[TP]['banner_clicks'][$ip][$ban_id]!=date('Y-m-d')) {

		$sql = "UPDATE ".TABLE_PREFIX."_banner SET ban_clicks=ban_clicks+1 WHERE ban_id=?";
		if(!$qry = $conn->prepare($sql))
			return false;

		else {

			$qry->bind_param('i', $ban_id);
			$qry->execute();
			$qry->close();

			$_SESSION[TP]['banner_clicks'][$ip][$ban_id] = date('Y-m-d');
			return true;
		}
	}

}

/*
 *retorna valor da coluna
 */
function getAgenciaNomeEmail($adm_id)
{
	global $conn;

	/*
	 *query 
	 */
	$sql = "SELECT adm_nome, adm_email, age_email_contato
				FROM ".TABLE_PREFIX."_administrador
				INNER JOIN ".TABLE_PREFIX."_agencia
					ON age_adm_id=adm_id
			WHERE adm_id=?";
	if(!$qry = $conn->prepare($sql))
		echo divAlert($conn->error, 'error');

	else {

		$qry->bind_param('i', $adm_id);
		$qry->execute();
		$qry->bind_result($nome, $email, $email_contato);
		$qry->fetch();
		$qry->close();

		return array('nome'=>$nome, 'email'=>$email, 'email_contato'=>$email_contato);
	}

}

/*
 *retorna valor da coluna
 */
function getAgenciaId($id)
{
	global $conn;

	/*
	 *query 
	 */
	$sql = "SELECT age_id FROM ".TABLE_PREFIX."_agencia WHERE age_adm_id=?";
	if(!$qry = $conn->prepare($sql))
		echo divAlert($conn->error, 'error');

	else {

		$qry->bind_param('i', $id);
		$qry->execute();
		$qry->bind_result($age_id);
		$qry->fetch();
		$qry->close();

		return $age_id;
	}


}



/*
 *retorna valor da coluna
 */
function getAgenciaLogo($age_id)
{
	global $conn, $rp;
	/*
	 *query da disciplina
	 */
	$sql = "SELECT rag_imagem FROM ".TABLE_PREFIX."_r_age_galeria WHERE rag_age_id=?";
	if(!$qry = $conn->prepare($sql))
		echo divAlert($conn->error, 'error');

	else {

		$qry->bind_param('i', $age_id);
		$qry->execute();
		$qry->bind_result($logo);
		$qry->fetch();
		$qry->close();

		$logo = !empty($logo) && file_exists($rp.'../image/agencia/'.$logo) ? $rp.'../image/agencia/'.$logo : null;
		return $logo;
	}

}

/*
 *retorna valor da coluna
 */
function getCategoriaCol($col, $ref, $rel)
{
	global $conn;
	/*
	 *query da disciplina
	 */
	$sql = "SELECT cat_{$col} FROM ".TABLE_PREFIX."_categoria WHERE cat_{$ref}=?";
	if(!$qry = $conn->prepare($sql))
		echo divAlert($conn->error, 'error');

	else {

		$qry->bind_param('s', $rel);
		$qry->execute();
		$qry->bind_result($$col);
		$qry->fetch();
		$qry->close();

		return $$col;
	}

}

/*
 *retorna valor da coluna
 */
function getAgenciaCol($col, $age_id)
{
	global $conn;
	/*
	 *query da disciplina
	 */
	$sql = "SELECT age_{$col} FROM ".TABLE_PREFIX."_agencia WHERE age_id=?";
	if(!$qry = $conn->prepare($sql))
		echo divAlert($conn->error, 'error');

	else {

		$qry->bind_param('i', $age_id);
		$qry->execute();
		$qry->bind_result($$col);
		$qry->fetch();
		$qry->close();

		return $$col;
	}

}


/*
 *retorna mes por extenso
 */
function mesExtenso($mes, $type='min')
{

	if (!empty($mes)) {

		switch ($mes) {
			case 1:
			case 01:
				$mesMin = 'Jan';
				$mesFull = 'Janeiro';
		break;
			case 2:
			case 02:
				$mesMin = 'Fev';
				$mesFull = 'Fevereiro';
		break;
			case 3:
			case 03:
				$mesMin = 'Mar';
				$mesFull = 'Março';
		break;
			case 4:
			case 04:
				$mesMin = 'Abr';
				$mesFull = 'Abril';
		break;
			case 5:
			case 05:
				$mesMin = 'Mai';
				$mesFull = 'Maio';
		break;
			case 6:
			case 06:
				$mesMin = 'Jun';
				$mesFull = 'Junho';
		break;
			case 7:
			case 07:
				$mesMin = 'Jul';
				$mesFull = 'Julho';
		break;
			case 8:
			case 08:
				$mesMin = 'Ago';
				$mesFull = 'Agosto';
		break;
			case 9:
			case 09:
				$mesMin = 'Set';
				$mesFull = 'Setembro';
		break;
			case 10:
				$mesMin = 'Out';
				$mesFull = 'Outubro';
		break;
			case 11:
				$mesMin = 'Nov';
				$mesFull = 'Novembro';
		break;
			case 12:
				$mesMin = 'Dez';
				$mesFull = 'Dezembro';
		break;
		}

	if ($type=='min')
		return $mesMin;
	else
		return $mesFull;

	}

}

/*
 *retorna array com todas as disciplinas
 */
function getListAgencias($age=false)
{
	global $conn;

	/*
	 *query da disciplina
	 */
	$sqla = "SELECT 
				adm_id,
				adm_nome,
				adm_status,
				(SELECT age_id FROM ".TP."_agencia WHERE age_adm_id=adm_id) age_id,
				adm_email

			FROM ".TABLE_PREFIX."_administrador
			WHERE adm_tipo='Agência'
			ORDER BY adm_nome";

	$agencia = $agenciaage = array();
	if(!$qrya = $conn->prepare($sqla))
		return false;

	else {

		$qrya->execute();
		$qrya->bind_result($id, $nome, $status, $age_id, $email);

		while ($qrya->fetch()) {
			$agencia[$id] = array('id'=>$id, 'age_id'=>$age_id, 'nome'=>$nome, 'email'=>$email, 'status'=>$status);
			$agenciaage[$age_id] = array('id'=>$id, 'age_id'=>$age_id, 'nome'=>$nome, 'email'=>$email, 'status'=>$status);
		}

		if (!$age)
			return $agencia;
		else
			return $agenciaage;

		$qrya->close();


	}

}

/*
 *retorna array com todas as disciplinas
 */
function getListMarca()
{
	global $conn;
	/*
	 *query da disciplina
	 */
	$sqld = "SELECT 
				cat_id,
				cat_titulo

			FROM ".TABLE_PREFIX."_categoria
			WHERE cat_status=1 AND cat_area='Marca'
			ORDER BY cat_titulo";

	$disciplina = array();
	if(!$qryd = $conn->prepare($sqld))
		echo divAlert($conn->error, 'error');

	else {

		$qryd->execute();
		$qryd->bind_result($id, $titulo);

		while ($qryd->fetch())
			$disciplina[$id] = $titulo;

		$qryd->close();


	}

	return $disciplina;
}
/*
 *retorna array com todas as disciplinas
 */
function getListModelo()
{
	global $conn;
	/*
	 *query da disciplina
	 */
	$sqld = "SELECT 
				cat_id,
				cat_titulo

			FROM ".TABLE_PREFIX."_categoria
			WHERE cat_status=1 AND cat_area='Modelo'
			ORDER BY cat_titulo";

	$disciplina = array();
	if(!$qryd = $conn->prepare($sqld))
		echo divAlert($conn->error, 'error');

	else {

		$qryd->execute();
		$qryd->bind_result($id, $titulo);

		while ($qryd->fetch())
			$disciplina[$id] = $titulo;

		$qryd->close();


	}

	return $disciplina;
}
/*
 *retorna array com todas as disciplinas
 */
function getListOpcional()
{
	global $conn;
	/*
	 *query da disciplina
	 */
	$sqld = "SELECT 
				cat_id,
				cat_titulo

			FROM ".TABLE_PREFIX."_categoria
			WHERE cat_status=1 AND cat_area='Disciplinas'
			ORDER BY cat_titulo";

	$opc = array();
	if(!$qryd = $conn->prepare($sqld))
		echo divAlert($conn->error, 'error');

	else {

		$qryd->execute();
		$qryd->bind_result($id, $titulo);

		while ($qryd->fetch())
			$opc[$id] = $titulo;

		$qryd->close();


	}

	return $opc;
}

/*
 *mostra mensagens de erro com css
 */
function divAlert($msg, $type='error')
{

	$alert = "<div class='alert alert-{$type}'>";
	$alert.= "<a class='close' data-dismiss='alert'>×</a>";
	$alert.= $msg;
	$alert.= "</div>";

	return $alert;
}


//-----------------------------------------------------
//Funcao: validaCNPJ($cnpj)
//Sinopse: Verifica se o valor passado é um CNPJ válido
// Retorno: Booleano
// Autor: Gabriel Fróes - www.codigofonte.com.br
//-----------------------------------------------------
function validaCNPJ($cnpj)
{ 
    if (strlen($cnpj) <> 18) return 0; 
    $soma1 = ($cnpj[0] * 5) + 

    ($cnpj[1] * 4) + 
    ($cnpj[3] * 3) + 
    ($cnpj[4] * 2) + 
    ($cnpj[5] * 9) + 
    ($cnpj[7] * 8) + 
    ($cnpj[8] * 7) + 
    ($cnpj[9] * 6) + 
    ($cnpj[11] * 5) + 
    ($cnpj[12] * 4) + 
    ($cnpj[13] * 3) + 
    ($cnpj[14] * 2); 
    $resto = $soma1 % 11; 
    $digito1 = $resto < 2 ? 0 : 11 - $resto; 
    $soma2 = ($cnpj[0] * 6) + 

    ($cnpj[1] * 5) + 
    ($cnpj[3] * 4) + 
    ($cnpj[4] * 3) + 
    ($cnpj[5] * 2) + 
    ($cnpj[7] * 9) + 
    ($cnpj[8] * 8) + 
    ($cnpj[9] * 7) + 
    ($cnpj[11] * 6) + 
    ($cnpj[12] * 5) + 
    ($cnpj[13] * 4) + 
    ($cnpj[14] * 3) + 
    ($cnpj[16] * 2); 
    $resto = $soma2 % 11; 
    $digito2 = $resto < 2 ? 0 : 11 - $resto; 
    return (($cnpj[16] == $digito1) && ($cnpj[17] == $digito2)); 
} 


/*
 *valida CPF
 */
function validaCPF($cpf)
{	// Verifiva se o número digitado contém todos os digitos
    $cpf = str_pad(preg_replace('/[^0-9]/', '', $cpf), 11, '0', STR_PAD_LEFT);
	
	// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
    if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999')
	{
	return false;
    }
	else
	{   // Calcula os números para verificar se o CPF é verdadeiro
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf{$c} != $d) {
                return false;
            }
        }

        return true;
    }
}


/*
 *valida data
 */
function validaData ($ano, $mes, $dia)
{
	return var_dump(checkdate($mes, $dia, $ano));
}
/*
 *valida data nascimento
 */
function validaNascimento($ano, $mes, $dia)
{

	$dataCheck = $ano.'-'.$mes.'-'.$dia;
	if (checkdate($mes, $dia, $ano) && $dataCheck<=date('Y-m-d'))
		return true;
	else return false;
}

/*
 *show javascript modal
 */
function showModal($args)
{
	global $res;

	$modalHeader = $closeButton = null;
	if (!is_array($args))
		exit('Parametro inválido');

	if (is_array($args) && count($args)==1 && !isset($args['content']))
		$args['content'] = $args[0];

	if (!isset($args['button']['param']))
		$args['button']['param'] = null;
	if (!isset($args['button']['link']))
		$args['button']['link'] = null;
	if (!isset($args['button']['value']))
		$args['button']['value'] = null;
	if (!isset($args['button']['class']))
		$args['button']['class'] = null;

	$closeButton = 'Fechar';
	//$closeButton = !empty($args['button']['value']) ? 'Cancelar' : 'Fechar';


	if (isset($args['title']))
		$modalHeader = "<div class='modal-header'> <a class='close' data-dismiss='modal'>×</a> <h3>{$args['title']}</h3> </div>";
	else
		$modalHeader = "<div class='modal-header'> <a class='close' data-dismiss='modal'>×</a> </div>";


	$js = null;
	$js .= "\n\t\tvar template = \"<div class='modal fade hide' id='msg-modal'>\";
		template += \"{$modalHeader}\";
		template += \"<div class='modal-body'>\";
		template += \"<p>{$args['content']}</p>\";
		template += \"</div>\";
		template += \"<div class='modal-footer'>\";";

	if (!isset($args['button']['close']) || $args['button']['close']==true)
		$js .= "\n\t\ttemplate += \"	<a href='javascript:void(0);' class='btn' data-dismiss='modal'>{$closeButton}</a>\";";

	if (!empty($args['button']['value']))
		$js .= "\n\t\ttemplate += \"<a href='{$args['button']['link']}' id='{$args['button']['param']}' class='btn-rm btn {$args['button']['class']} btn-primary'>{$args['button']['value']}</a>\";";

	$js .= "\n\n\t\ttemplate += \"</div></div>\";";
	$js .= "\n\t\tif ($('#html-msg'))";
	$js .= "\n\t\t\t$('#html-msg').html(template);";
	$js .= "\n\t\telse";
	$js .= "\n\t\t\t$(template).appendTo('body');";
	$js .= "\n\t\tif ($('#lightbox')) $('#lightbox').hide();";
	$js .= "\n\t\tif ($('.hide')) $('.hide').hide();";
	$js .= "\n\t\t$('#msg-modal').modal('show');\n\n";
	return $js;

}

/*
 *o mesmo que linffy só que converte toda / na string em -
 */
function linkfySmart($var, $spacer='-') {
	$url = preg_replace('|[/]|', $spacer, $var);
	return linkfy($url);

}


/*
 *encurtador de url
 */
function shortUrl($url, $service='google', $action='short') {

	if($action=='short') {

		if($service=='google') {

			$urlapi = "https://www.googleapis.com/urlshortener/v1/url";
			$postData = array('longUrl'=>$url, 'key'=>'AIzaSyAcJa1PtXCCRXVUEYiv4iu4MnT4vBM2r-o');

		} else {

			$postData = array('login'=>'lslucas', 'longUrl'=>$url, 'apiKey'=>'R_9413f87bc6b34d74c50254d31a8a55c8', 'format'=>'json');
			$querystring = http_build_query($postData);
			$postData = null;

			$urlapi = "http://api.bitly.com/v3/shorten?".$querystring;

		}




		$post = !is_null($postData) ? json_encode($postData) : null;
		$json = curl_post($urlapi, $post, array('Content-Type: application/json'));

		if($service=='google') return $json->id;
		else {
			if($json->status_code!=500) return $json->data->url;
		}


	} 

}
/*
 *CURL POST
 */
function curl_post($url, $post, $header) {
	$curlObj = curl_init();
	 
	curl_setopt($curlObj, CURLOPT_URL, $url);
	curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);

	// se é um post
	if(!empty($post)) {

		curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curlObj, CURLOPT_HEADER, 0);
		if(is_array($header)) curl_setopt($curlObj, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curlObj, CURLOPT_POST, 1);
		curl_setopt($curlObj, CURLOPT_POSTFIELDS, $post);

	}
	 
	$response = curl_exec($curlObj);
	curl_close($curlObj);
	 
	//change the response json string to object
	$json = json_decode($response);
	return $json; 

}

/*
 *Converte decimal em moeda
 */
function Moeda($val) {
	//setlocale(LC_MONETARY, 'pt_BR', 'ptb');
	//return money_format('%4n', $val);
	return number_format($val, 2,',','.');
}

/*
 *Converte de Float para moeda
 */
function Currency2Decimal($number, $reverse=0) {


  if($reverse===1) {
   $number = preg_replace('/[^0-9,]/', '', $number);
   $number = preg_replace('/[, ]/', '.', $number);
   $number = number_format($number, 2, '.', '');
   return $number;

  } else return number_format($number, 2, ',', '.');


}



/*
 *substring melhorado
 */
function super_substr($texto, $limit) {


	$acentosUpper = "ĄĆĘŁŃÓŚŹŻABCDEFGHIJKLMNOPRSTUWYZQXVЁЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮÂÀÁÄÃÊÈÉËÎÍÌÏÔÕÒÓÖÛÙÚÜÇ";
	$acentosLower = "ąćęłńóśźżabcdefghijklmnoprstuwyzqxvёйцукенгшщзхъфывапролджэячсмитьбюâàáäãêèéëîíìïôõòóöûùúüç";

	if (strlen($texto)>$limit) {
		$texto = strip_tags($texto);
		$_t = substr($texto, 0, $limit);
		$_p = strrpos($_t, ' ');
		$_t = substr($_t, 0, $_p);
		$_final = preg_replace("/[^A-Za-z{$acentosUpper}{$acentosLower}]/", '', substr($_t, -1,1));

		$res = substr($_t, 0, -1).$_final;

	} else
		$res = $texto;

	return $res;
}


/*
 *remove acentos
 */
function file_extension($filename) {

 return end(explode(".", $filename));

}


/**
 * Converts all accent characters to ASCII characters.
 *
 * If there are no accent characters, then the string given is just returned.
 *
 * @param string $string Text that might have accent characters
 * @return string Filtered string with replaced "nice" characters.
 */

function remove_accents($string) {
 if (!preg_match('/[\x80-\xff]/', $string))
  return $string;
 if (seems_utf8($string)) {
  $chars = array(
  // Decompositions for Latin-1 Supplement
  chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
  chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
  chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
  chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
  chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
  chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
  chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
  chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
  chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
  chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
  chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
  chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
  chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
  chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
  chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
  chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
  chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
  chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
  chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
  chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
  chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
  chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
  chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
  chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
  chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
  chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
  chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
  chr(195).chr(191) => 'y',
  // Decompositions for Latin Extended-A
  chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
  chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
  chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
  chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
  chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
  chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
  chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
  chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
  chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
  chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
  chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
  chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
  chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
  chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
  chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
  chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
  chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
  chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
  chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
  chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
  chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
  chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
  chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
  chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
  chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
  chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
  chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
  chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
  chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
  chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
  chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
  chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
  chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
  chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
  chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
  chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
  chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
  chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
  chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
  chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
  chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
  chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
  chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
  chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
  chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
  chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
  chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
  chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
  chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
  chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
  chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
  chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
  chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
  chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
  chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
  chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
  chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
  chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
  chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
  chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
  chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
  chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
  chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
  chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
  // Euro Sign
  chr(226).chr(130).chr(172) => 'E',
  // GBP (Pound) Sign
  chr(194).chr(163) => '');
  $string = strtr($string, $chars);
 } else {
  // Assume ISO-8859-1 if not UTF-8
  $chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
   .chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
   .chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
   .chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
   .chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
   .chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
   .chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
   .chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
   .chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
   .chr(252).chr(253).chr(255);
  $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";
  $string = strtr($string, $chars['in'], $chars['out']);
  $double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
  $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
  $string = str_replace($double_chars['in'], $double_chars['out'], $string);
 }
 return $string;
}

/**
 * Checks to see if a string is utf8 encoded.
 *
 * @author bmorel at ssi dot fr
 *
 * @param string $Str The string to be checked
 * @return bool True if $Str fits a UTF-8 model, false otherwise.
 */
function seems_utf8($Str) { # by bmorel at ssi dot fr
 $length = strlen($Str);
 for ($i = 0; $i < $length; $i++) {
  if (ord($Str[$i]) < 0x80) continue; # 0bbbbbbb
  elseif ((ord($Str[$i]) & 0xE0) == 0xC0) $n = 1; # 110bbbbb
  elseif ((ord($Str[$i]) & 0xF0) == 0xE0) $n = 2; # 1110bbbb
  elseif ((ord($Str[$i]) & 0xF8) == 0xF0) $n = 3; # 11110bbb
  elseif ((ord($Str[$i]) & 0xFC) == 0xF8) $n = 4; # 111110bb
  elseif ((ord($Str[$i]) & 0xFE) == 0xFC) $n = 5; # 1111110b
  else return false; # Does not match any model
  for ($j = 0; $j < $n; $j++) { # n bytes matching 10bbbbbb follow ?
   if ((++$i == $length) || ((ord($Str[$i]) & 0xC0) != 0x80))
   return false;
  }
 }
 return true;
}

function utf8_uri_encode($utf8_string, $length = 0) {
 $unicode = '';
 $values = array();
 $num_octets = 1;
 $unicode_length = 0;
 $string_length = strlen($utf8_string);
 for ($i = 0; $i < $string_length; $i++) {
  $value = ord($utf8_string[$i]);
  if ($value < 128) {
   if ($length && ($unicode_length >= $length))
    break;
   $unicode .= chr($value);
   $unicode_length++;
  } else {
   if (count($values) == 0) $num_octets = ($value < 224) ? 2 : 3;
   $values[] = $value;
   if ($length && ($unicode_length + ($num_octets * 3)) > $length)
    break;
   if (count( $values ) == $num_octets) {
    if ($num_octets == 3) {
     $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
     $unicode_length += 9;
    } else {
     $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
     $unicode_length += 6;
    }
    $values = array();
    $num_octets = 1;
   }
  }
 }
 return $unicode;
}

/**
 * Sanitizes title, replacing whitespace with dashes.
 *
 * Limits the output to alphanumeric characters, underscore (_) and dash (-).
 * Whitespace becomes a dash.
 *
 * @param string $title The title to be sanitized.
 * @return string The sanitized title.
 */
function slugify($title) {
 $title = strip_tags($title);
 // Preserve escaped octets.
 $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
 // Remove percent signs that are not part of an octet.
 $title = str_replace('%', '', $title);
 // Restore octets.
 $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);
 $title = remove_accents($title);
 if (seems_utf8($title)) {
  if (function_exists('mb_strtolower')) {
   $title = mb_strtolower($title, 'UTF-8');
  }
  $title = utf8_uri_encode($title, 200);
 }
 $title = strtolower($title);
 $title = preg_replace('/&.+?;/', '', $title); // kill entities
 $title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
 $title = preg_replace('/\s+/', '-', $title);
 $title = preg_replace('|-+|', '-', $title);
 $title = trim($title, '-');
 return $title;
}


/**
 * Sanitizes title, replacing whitespace with dashes.
 *
 * Limits the output to alphanumeric characters, underscore (_) and dash (-).
 * Whitespace becomes a dash.
 *
 * @param string $title The title to be sanitized.
 * @return string The sanitized title.
 */
function linkfy($title) {
 $title = strip_tags($title);
 // Preserve escaped octets.
 $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
 // Remove percent signs that are not part of an octet.
 $title = str_replace('%', '', $title);
 // Restore octets.
 $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);
 $title = remove_accents($title);
 if (seems_utf8($title)) {
  $title = utf8_uri_encode($title, 200);
 }

 $title = preg_replace('/&.+?;/', '', $title); // kill entities*/
 $title = preg_replace('/\s+/', '-', $title);
 $title = preg_replace('|-+|', '-', $title);
 $title = trim($title, '-');
 return $title;
}

/*
 *tiny mce
 */
function parse_mytag($content) {

        // Find the tags
        preg_match_all('/\<span style="font-weight: bold;"([^>]*)\>(.*?)\<\/span\>/is', $content, $matches);

        // Loop through each tag
        for ($i=0; $i < count($matches['0']); $i++) {
                $tag = $matches['0'][$i];
                $text = $matches['2'][$i];

                $new = '<b>';
                $new .= $text;
                $new .= '</b>';

                // Replace with actual HTML
                $content = str_replace($tag, $new, $content);
        }

        return $content;
}



/*
 *valida email 
 */
function validaEmail($email) {

 if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
    list($username , $domain) = explode('@',$email);
    if(!checkdnsrr($domain, 'MX'))
      return false;

    return true;

 } else 
	 return false;
}


 /*
  *REMOVE ACENTOS
  */
/***
 * Função para remover acentos de uma string
 *
 * @autor Thiago Belem <contato@thiagobelem.net>
 */
function removeAcentos($string, $slug = false) {
	$string = strtolower($string);

	// Código ASCII das vogais
	$ascii['a'] = range(224, 230);
	$ascii['e'] = range(232, 235);
	$ascii['i'] = range(236, 239);
	$ascii['o'] = array_merge(range(242, 246), array(240, 248));
	$ascii['u'] = range(249, 252);

	// Código ASCII dos outros caracteres
	$ascii['b'] = array(223);
	$ascii['c'] = array(231);
	$ascii['d'] = array(208);
	$ascii['n'] = array(241);
	$ascii['y'] = array(253, 255);

	foreach ($ascii as $key=>$item) {
		$acentos = '';
		foreach ($item AS $codigo) $acentos .= chr($codigo);
		$troca[$key] = '/['.$acentos.']/i';
	}

	$string = preg_replace(array_values($troca), array_keys($troca), $string);

	// Slug?
	if ($slug) {
		// Troca tudo que não for letra ou número por um caractere ($slug)
		$string = preg_replace('/[^a-z0-9]/i', $slug, $string);
		// Tira os caracteres ($slug) repetidos
		$string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
		$string = trim($string, $slug);
	}

	return $string;
}


/*
 *CALCULA IDADE
 */
 function diferencaAnos($var,$ref) {

  $var = explode('-',$var);
  $ref = explode('-',$ref);

  list($ano,$mes,$dia)=$var;
  list($ano_atual,$mes_atual,$dia_atual)=$ref;

 if (!checkdate($mes, $dia, $ano) || !checkdate($mes_atual, $dia_atual, $ano_atual)) {
  return '[data inválida]';
 #  echo "A data que você informou está errada <b>[ ${var[0]}/${var[1]}/${var[2]} ou ${ref[0]}/${ref[1]}/${ref[2]}]</b>";

  } else { 

   $dif = $ano_atual-$ano;

   if ($mes_atual<$mes) {
    $dif=$dif-1;

   } elseif ($mes==$mes_atual && $dia_atual<$dia) {
    $dif=$dif-1;
   } 

  return $dif;
  }

}

/*
 *REMOVE TUDO QUE NAO É NÚMERO
 */
function apenasNumeros($var)
{
	return preg_replace('/[^0-9]/','',$var);
}

/*
 *RETORNA TIMESTAMP DA DATA EM INGLES
 */
 function en2timestamp($date,$sep='-') {


   $date = explode($sep,$date);
   $unix = mktime(0,0,0,$date[1],$date[2],$date[0]);


   return $unix;

 }



/*
 *RETORNA O DIA DA SEMANA
 */
 function date_diasemana($date,$type='') {

  if (!empty($date)) {

   #pega informações da data
   $date = en2timestamp($date);
   $wday = getdate($date);
   $wday = $wday['wday']; #usa apenas o dia da semana em números de 0 a 6

     switch($wday) {
	 case 0: $s_min = 'dom'; $s_nor = 'domingo';
       break;
	 case 1: $s_min = 'seg'; $s_nor = 'segunda';
       break;
	 case 2: $s_min = 'ter'; $s_nor = 'terça';
       break;
	 case 3: $s_min = 'qua'; $s_nor = 'quarta';
       break;
	 case 4: $s_min = 'qui'; $s_nor = 'quinta';
       break;
	 case 5: $s_min = 'sex'; $s_nor = 'sexta';
       break;
	 case 6: $s_min = 'sab'; $s_nor = 'sábado';
       break;
     }

     $return = empty($type)?$s_nor:$s_min;

   return $return;

  }

 }


 /*
  *converte newline para br
  */
 function newline2br($txt){

   $texto0= ereg_replace("(\r)",'<br/>',$txt);
   $texto = ereg_replace("(\n)",'',$texto0);
   $txt   = ereg_replace("/(<br\s*\/?>\s*)+/",'<br/>',$texto);

 return $txt;

 }


/*
 *CASO O TEXTO SEJA DE BBCODE ELE CONVERTE
 */
function txt_bbcode($var) {

 $txt = utf8_encode(html_entity_decode($var));

 return $txt;
}


/*
 *converte br to nl
 */
function br2nl( $input ) {
 return preg_replace('/<br(\s+)?\/?>/i', "\n", $input);
}


# GERA PASSWORD
###############
function gera_senha($numL) {
    $chars = "?abcdefghijkmnopqrstuvwxyz023456789#";
    srand((double)microtime()*1000000);
    $i = 0;
    $pass = '' ;

     while ($i <= $numL) {
        $num = rand() % 36;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
      }

    return $pass;

}
# //GERA PASSWORD
###



# CONVERTE A DATA DO PORTUGUES PARA INGLES
##########################################
function datept2en($sep,$date,$nsep='-') {

 if (!empty($date)) {

   $date = explode($sep,$date);
   return $date[2].$nsep.$date[1].$nsep.$date[0];

 }

}

#// CONVERTE A DATA DO PORTUGUES PARA INGLES
###

# CONVERTE A DATA DO INGLES PARA PORTUGUES
##########################################
function dateen2pt($sep,$date,$nsep='-') {

 if (!empty($date)) {

   $date = explode($sep,$date);
   return $date[2].$nsep.$date[1].$nsep.$date[0];

 }

}

#// CONVERTE A DATA DO PORTUGUES PARA INGLES
###



## debug do session
function debug($var) {
	echo '<pre>'. print_r($var, 1) .'</pre>';
}

/*
 *user agent
 */
function getUserAgentName($agent)
{
	$browserArray = array(
		'Windows Mobile' => 'IEMobile',
		'Android Mobile' => 'Android',
		'iPhone Mobile' => 'iPhone',
		'Firefox' => 'Firefox',
		'Google Chrome' => 'Chrome',
		'Internet Explorer' => 'MSIE',
		'Opera' => 'Opera',
		'Safari' => 'Safari'
	); 

	foreach ($browserArray as $k => $v) {

		if (preg_match("/$v/", $agent))
			break;
		else
			$k = "Browser Unknown";

	} 

	$browser = $k;


	$osArray = array(
		'Windows 98' => '(Win98)|(Windows 98)',
		'Windows 2000' => '(Windows 2000)|(Windows NT 5.0)',
		'Windows ME' => 'Windows ME',
		'Windows XP' => '(Windows XP)|(Windows NT 5.1)',
		'Windows Vista' => 'Windows NT 6.0',
		'Windows 7' => '(Windows NT 6.1)|(Windows NT 7.0)',
		'Windows NT 4.0' => '(WinNT)|(Windows NT 4.0)|(WinNT4.0)|(Windows NT)',
		'Linux' => '(X11)|(Linux)',
		'Mac OS' => '(Mac_PowerPC)|(Macintosh)|(Mac OS)'
	); 

	foreach ($osArray as $k => $v) {

		if (preg_match("/$v/", $agent))
			break;
		else
			$k = "Unknown OS";
	} 

	$os = $k;

	return $browser.' - '.$os;

}

function logextended($acao, $modulo, $params) {
	global $conn;

	if (!is_array($params))
		exit('Parâmetro inválido!');

	if (empty($acao) || empty($params['log_id']))
		exit('Dados inválidos');


	$sql_loge = "INSERT INTO ".TABLE_PREFIX."_log_extended
		(
		 lex_log_id,
		 lex_acao,
		 lex_modulo,
		 lex_antes,
		 lex_depois
		) VALUES (?, ?, ?, ?, ?)
	  ";
	if(!$qr_loge = $conn->prepare($sql_loge))
		echo $conn->error();

	else {
		$qr_loge->bind_param('issss', $params['log_id'], $acao, $modulo, $params['antes'], $params['depois']);
		$qr_loge->execute();
		$qr_loge->close();
	}

}

## LOG
#COMPUTA TUDO NA TABELA DE LOG
###############################
function logquery() {
 global $conn, $log_id;

 if (!isset($_SESSION['user'])) {
     $userdata = array(
      'id' => '',
      'nome' => '',
      'email' => '',
      'senha' => '',
      'ip' => $_SERVER['REMOTE_ADDR'],
      'host' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
      'useragent' => $_SERVER['HTTP_USER_AGENT']
     );
     
     foreach($userdata as $k=>$v) {
      $log[$k]=$v;
     }


 } else {

     foreach($_SESSION['user'] as $k=>$v) {
      $log[$k]=$v;
     }

 }


  #computa variaveis para o log
     $server = array(
      'php_self' => $_SERVER['PHP_SELF'],
      'query_string' => $_SERVER['QUERY_STRING'],
      'request_uri' => $_SERVER['REQUEST_URI'],
      'request_time' => $_SERVER['REQUEST_TIME'],
      'http_referer' => isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:''
     );

     foreach($server as $k=>$v) {
      $slog[$k]=$v;
     }



  $sql_log = "INSERT INTO ".TABLE_PREFIX."_log 
  		(
  		 log_adm_id,
		 log_nome,
		 log_email,
		 log_senha,
		 log_php_self,
		 log_query_string,
		 log_request_uri,
		 log_request_time,
		 log_http_referer,
		 log_ip,
		 log_host,
		 log_useragent
		) VALUES (
		 ?,?,?,?,?,?,?,?,?,?,?,?
		)
	  ";
  if(($qr_log = $conn->prepare($sql_log))==false) {
   echo $conn->error();
   $qr_log->close();
  }

   else {
    $qr_log->bind_param('isssssssssss', $log['id'], $log['nome'], $log['email'], $log['senha'], $slog['php_self'], $slog['query_string'], $slog['request_uri'], $slog['request_time'], $slog['http_referer'], $log['ip'], $log['host'],$log['useragent']);
    $qr_log->execute();
	return $log_id = $conn->insert_id;
    $qr_log->close();
  }

}
## //LOG
#####

## Retorna lista de campos e valores
###############################
function getFieldAndValues($params) {
	global $conn;

	if (!is_array($params))
		exit('Parâmetro inválido!');

	$id = $params['id'];
	$mod = $params['modulo'];
	$pre = $params['pre'];
	$ref = isset($params['ref']) ? $params['ref'] : 'id';

	if (empty($id) || empty($mod) || empty($pre))
		exit('Dados inválidos');


	/*
	 *pega lista de colunas
	 */
	$sql= "SELECT * FROM ".TABLE_PREFIX."_{$mod} WHERE {$pre}_{$ref}=\"{$id}\"";
	$fields = array();
	if(!$qry = $conn->query($sql))
		echo $conn->error();

	else {

		while($fld = $qry->fetch_field()) 
			array_push($fields, str_replace($pre.'_', null, $fld->name));

		$qry->close();
	}

	/*
	 *pega valores dessas colunas
	 */
	$sqlv= "SELECT * FROM ".TABLE_PREFIX."_{$mod} WHERE {$pre}_{$ref}=\"{$id}\"";
	if(!$qryv = $conn->query($sqlv))
		echo $conn->error();

	else {
		$valores = $qryv->fetch_array(MYSQLI_ASSOC);
		$qryv->close();
	}

	$res = null;
	foreach ($fields as $i=>$col)
		$res .= "{$col} = ".$valores[$pre.'_'.$col].";\n";


	return $res."\n";
}

## DEBUG
# grava todo tipo de erro numa tabela e pode enviar para o administrador
########
 function debuglog($numero,$texto,$errfile, $errline){
  global $conn;


  if(DEBUG==1) {

    ## VARIAVEIS DE CONFIG
     if (!isset($_SESSION['user'])) {
	 $userdata = array(
	  'id' => '',
	  'nome' => '',
	  'ip' => $_SERVER['REMOTE_ADDR'],
	  'useragent' => $_SERVER['HTTP_USER_AGENT']
	 );
	 
	 foreach($userdata as $k=>$v) {
	  $log[$k]=$v;
	 }

     } else {

	 foreach($_SESSION['user'] as $k=>$v) {
	  $log[$k]=$v;
	 }

     }




      # se DEBUG_LOG nao for vazio vai gravar no arquivo de log
      if (DEBUG_LOG<>'') {

	/*
	if(!file_exists(DEBUG_LOG)) {
	 mkdir(DEBUG_LOG,0777,true);
	}
	*/

	$ddf = fopen(DEBUG_LOG,'a');
	fwrite($ddf,"".date("r").": [$numero] $texto $errfile $errline \r\n [$log[id]]$log[nome] - $log[ip], $log[useragent] \r\n\r\n");
	fclose($ddf);

      }


      $sql_dlog = "INSERT INTO ".TABLE_PREFIX."_debuglog 
		    (
		     del_adm_id,
		     del_nome,
		     del_useragent,
		     del_ip,
		     del_err_number,
		     del_err,
		     del_err_file,
		     del_err_line
		    ) VALUES (
		     ?,?,?,?,?,?,?,?
		    )
	      ";

      if(($qr_dlog = $conn->prepare($sql_dlog))==false) {
   	    echo 'erro '.$conn->error;

      } else { 
	$qr_dlog->bind_param('isssissi',$log['id'],$log['nome'],$log['ip'],$log['useragent'],$numero,$texto,$errfile, $errline);
	$qr_dlog->execute();
	$qr_dlog->close();
  unset($qr_dlog);
      }
 }

}
 if (DEBUG==1) 
  set_error_handler('debuglog'); 
## //DEBUG
#####
