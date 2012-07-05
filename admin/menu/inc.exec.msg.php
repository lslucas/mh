<?php
/*
 *INICIO MSG ERROS
 */
$nomeAcao = $act=='insert'?'incluid'.$var['genero']:'alterad'.$var['genero'];

//duplicado
$msgDuplicado = <<<end
<div class='alert alert-error'>
	<a class="close" data-dismiss="alert">×</a>
	Já existe $var[um] com o e-mail <b>- $res[nome] -</b>
	<br>
	<p class='small'>
	<a href='javascript:history.back(-1);'>Volte e preencha novamente</a>
</div>
end;

# erro
$msgErro = <<<end
<div class='alert alert-error'>Houve um erro!
	<a class="close" data-dismiss="alert">×</a>
	<br>
	<pre>$conn->error()</pre>
</div>
end;

# sucesso
$msgSucesso = <<<end
<div class='alert alert-success'>
	<a class="close" data-dismiss="alert">×</a>
	Ítem $nomeAcao com êxito!
	<br><p class='small'>
		<a href='?p=$p&insert'>Incluir $var[novo]?</a>
	</a>
</div>
end;
