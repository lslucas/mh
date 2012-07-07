<?php

	$toScript = null;
	$act = 'insert';
	if ($basename=='meu-cadastro') {
		$act = 'update';
		$item = $_GET['item'] = $usr['id'];
	}

	if (!isset($res['nascimento']))
		$res['nascimento'] = null;

$incjQuery .= <<<end

	//tabs
	$('#prev').hide();
	$('input[type="submit"]').attr('disabled', true);
	$('input[type="submit"]').hide();
	$('#perfil').on('shown', function (e) {
		$('#avancar').addClass('btn-primary');
		$('#next').show();
		$('#prev').hide();
		$('input[type="submit"]').hide();
		$('input[type="submit"]').attr('disabled', true);
	});

	$('#dados-pessoais').on('shown', function (e) {
		$('#next').show();
		$('#prev').show();
		$('input[type="submit"]').hide();
		$('input[type="submit"]').attr('disabled', true);
	});

	$('#senha').on('shown', function (e) {
		$('#next').hide();
		$('#prev').show();
		$('input[type="submit"]').show();
		$('input[type="submit"]').attr('disabled', false);
	});

	$('#next').click(function() {
		var e = $('.subscribetabs li.active').next().find('a[data-toggle="tab"]');
		if (e.length > 0)
			e.click();
	});

	$('#prev').click(function() {
		var e = $('.subscribetabs li.active').prev().find('a[data-toggle="tab"]');
		if (e.length > 0)
			e.click();
	});

end;
?>
  <form method='post' action='<?=$_SERVER['QUERY_STRING']?>' id='form_<?=$p?>' class='form-horizontal cmxform' enctype="multipart/form-data">
 <input type='hidden' name='act' value='<?=$act?>'>
<?php
  if ($act=='update') {
    echo "<input type='hidden' name='item' value='${_GET['item']}'>";
  }
?>
<h2 class='head rosa'>
<?php 
  if ($act=='update') echo 'Atualizar Dados';
   else echo 'Novo Cadastro';
?>
</h2>
<p class='header'>
<?php
	if ($basename<>'meu-cadastro')
		echo "Consultório Sentimental é um espaço para você perguntar, pedir conselhos ou simplesmente conversar e desabafar sobre relacionamento amoroso com a escritora e Consultora de Relacionamento Rennata Alarcon.<br/><br/>";
?>
	Preencha os campos abaixo. Os campos marcados com asterisco (<b>*</b>) são de preenchimento obrigatório!
</p>


 <div class='alert alert-error completeform-error hide'>
	<a class="close" data-dismiss="alert">×</a>
	Antes de prosseguir preencha corretamente o formulário e revise os campos abaixo:
	<br/><br/>
	<ol> 
		<li><label for="nome" class="error-validate">Digite o nome</label></li> 
		<li><label for="email" class="error-validate">Entre com um <b>email</b> válido</label></li> 
		<li><label for="email_secundario" class="error-validate">Entre com um <b>email secundário</b> válido</label></li> 
		<li><label for="sexo" class="error-validate">Informe seu <b>sexo</b></label></li> 
		<li><label for="nascimento" class="error-validate">Informe a data do seu <b>nascimento</b></label></li> 
		<li><label for="telefone1" class="error-validate">Informe um <b>telefone</b></label></li> 
		<li><label for="telefone2" class="error-validate">Informe um <b>celular</b></label></li> 
		<li><label for="cpf" class="error-validate">Informe seu <b>CPF</b></label></li> 
		<li><label for="rg" class="error-validate">Informe seu <b>RG</b></label></li> 
		<li><label for="msn" class="error-validate">Entre com um email válido para o <b>MSN</b></label></li> 
		<li><label for="gtalk" class="error-validate">Entre com um email válido para o <b>GTALK</b></label></li> 
		<li><label for="facebook" class="error-validate">Entre com uma url válida para o <b>Facebook</b>. Lembre-se de colocar http:// no inicio da url</label></li> 
		<li><label for="twitter" class="error-validate">Entre com uma url válida para o <b>Twitter</b>. Lembre-se de colocar http:// no inicio da url</label></li> 
		<li><label for="senha" class="error-validate">Informe a sua <b>Senha</b></label></li> 
		<li><label for="senha_confirma" class="error-validate">Você deve preencher a <b>Confirmação de Senha</b></label></li> 
		<li><label for="senha_atual" class="error-validate">Você deve preencher informar sua <b>Senha Atual</b></label></li> 
	</ol> 
</div>

<div class="tabbable">
  <ul class="subscribetabs nav nav-tabs">
    <li class="active"><a href="#perfil" id='tabPerfil' data-toggle="tab" class='bold'>Perfil</a></li>
    <li><a href="#dados-pessoais" id='tabDadosPessoais' data-toggle="tab" class='bold'>Dados Pessoais</a></li>
    <li><a href="#social" data-toggle="tab">Social</a></li>
    <li><a href="#senha" id='tabSenha' data-toggle="tab" class='bold'>Senha</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="perfil">

		<fieldset>

			<div class="control-group">
			  <label class="control-label" for="nome">* Nome</label>
			  <div class="controls">
				<input type="text" class="input-xlarge required" placeholder='Nome' name='nome' id='nome' value='<?=$val['nome']?>'>
				<p class="help-block">Informe seu nome</p>
			  </div>
			</div>

			<div class="control-group">
			  <label class="control-label" for="email">* Email</label>
			  <div class="controls">
				<input type="text" class="input-xlarge email required" placeholder='email@provedor.com.br' name='email' id='email' value='<?=$val['email']?>'>
				<p class="help-block">Informe um email válido</p>
			  </div>
			</div>

<?php /*
			<div class="control-group">
			  <label class="control-label" for="email_secundario">Email Secundário</label>
			  <div class="controls">
			  <input type="text" class="input-xlarge email" placeholder='Email secundário' name='email_secundario' id='email_secundario' value='<?=$val['email_secundario']?>'>
				<p class="help-block">Informe um email válido. Não é utilizado para login, apenas para recuperar o login</p>
			  </div>
			</div>

			<div class="control-group">
			  <label class="control-label" for="foto">Foto</label>
			  <div class="controls">
				  <?php
					  
					$num=0;
					if ($act=='update') {
							  
						$sql_gal = "SELECT rag_id, rag_imagem, rag_legenda, rag_pos FROM ".TABLE_PREFIX."_r_${var['pre']}_galeria WHERE rag_adm_id=? AND rag_imagem IS NOT NULL ORDER BY rag_pos ASC;"; 
						$qr_gal = $conn->prepare($sql_gal);
						$qr_gal->bind_param('s',$_GET['item']);
						$qr_gal->execute();
						$qr_gal->store_result();
						$num = $qr_gal->num_rows;
						$qr_gal->bind_result($g_id, $g_imagem, $g_legenda, $g_pos);
						$i=0;
						
					}
						
						if ($num>0) {
			
						  echo '<table id="posGaleria" cellspacing="0" cellpadding="2">';
						  while ($qr_gal->fetch()) {

							  $arquivo = $var['path_original']."/".$g_imagem;
				  ?>
					<tr id="<?=$g_id?>">
					  <td width='20px' title='Clique e arraste para mudar a posição da foto' class='tip'></td>
			
					  <td class='small'>
						[<a href='?p=<?=$p?>&delete_galeria&item=<?=$g_id?>&prefix=r_<?=$var['pre']?>_galeria&pre=rag&col=imagem&folder=<?=$var['imagem_folderlist']?>&noVisual' title="Clique para remover o ítem selecionado" class='tip trash-galeria' style="cursor:pointer;" id="<?=$g_id?>">remover</a>]
					  </td>
					  <td>
						<a href='<?=$arquivo?>' id='imag<?=$i?>' class='' target='_blank'>
						<?="<img src='".substr($var['path_thumb'],0)."/".$g_imagem."' class='thumbnail'>"?>
						 <div id='imagThumb<?=$i?>' style='float:left;display:none;'>
						 <?php 
						 
							if (file_exists(substr($var['path_thumb'],0)."/".$g_imagem))
							 echo "<img src='".substr($var['path_thumb'],0)."/".$g_imagem."'>";
			
							   else echo "<center>imagem não existe.</center>";
						  ?>
						 </div>
					  </td>
					</tr>
				  <?php
						  $i++;	
			
						}
					   echo '</table><br>';
			
					   } else {
				   ?>
					 <div class='divImagem'>
					   <input class="galeria" type='file' name='galeria0' id='galeria' alt='0' style="height:18px;font-size:7pt;margin-bottom:8px; width:500px;">
					   <br><small>- JPEG, PNG ou GIF; Máximo 1MB</small>
					 </div>
					 </p>
					<?php
					
					   }
					?>
				<p class="help-block">Foto para o perfil</p>
			  </div>
			</div>
		</fieldset>
 */ ?>

    </div>
    <div class="tab-pane" id="dados-pessoais">

		<fieldset>

			<div class="control-group">
			  <label class="control-label" for="sexo">* Sexo</label>
			  <div class="controls">
				<label>
				<input type="radio" class='required' name='sexo' value='Masculino'<?=$val['sexo']=='Masculino' ? ' checked':null?>> Masculino
				</label>
				<label>
					<input type="radio" class='required' name='sexo' value='Feminino'<?=$val['sexo']=='Feminino' ? ' checked':null?>> Feminino
				</label>

			  </div>
			</div>

			<div class="control-group">
			  <label class="control-label" for="nascimento">* Nascimento</label>
			  <div class="controls">
				<input type="text" class="input-xlarge required data" placeholder='dd/mm/YYYY' name='nascimento' id='nascimento' value='<?=!isset($res['nascimento']) && !empty($val['nascimento']) ? dateen2pt('-',$val['nascimento'],'/') : $res['nascimento']?>'>
				<p class="help-block">Entre com a data de nascimento</p>
			  </div>
			</div>

			<div class="control-group">
			  <label class="control-label" for="cpf">CPF</label>
			  <div class="controls">
				<input type="text" class="input-xlarge cpf" placeholder='CPF' name='cpf' id='cpf' value='<?=$val['cpf']?>'>
			  </div>
			</div>

			<div class="control-group">
			  <label class="control-label" for="rg">RG</label>
			  <div class="controls">
				<input type="text" class="input-xlarge" placeholder='RG' name='rg' id='rg' value='<?=$val['rg']?>'>
			  </div>
			</div>

			<div class="control-group">
			  <label class="control-label" for="telefone">Telefone</label>
			  <div class="controls">
			  <input type="text" class="input-xlarge phone" placeholder='Telefone' name='telefone1' id='telefone1' value='<?=$val['telefone1']?>'>
				<p class="help-block">Telefone principal ou telefone fixo</p>
			  </div>
			</div>

			<div class="control-group">
			  <label class="control-label" for="telefone2">Telefone 2</label>
			  <div class="controls">
				<input type="text" class="input-xlarge" placeholder='Celular' name='telefone2' id='telefone2' value='<?=$val['telefone2']?>'>
				<p class="help-block">Número de celular pessoal ou para trabalho</p>
			  </div>
			</div>

			<div class="control-group">
			  <label class="control-label" for="cidade">* Cidade</label>
			  <div class="controls">
				<input type="text" class="input-xlarge required" placeholder='Cidade' name='cidade' id='cidade' value='<?=$val['cidade']?>'>
				<p class="help-block">Cidade em que reside</p>
			  </div>
			</div>

			<div class="control-group">
			  <label class="control-label" for="estado">* UF</label>
			  <div class="controls">
                <select name="estado" class='required'>
                    <option value="">Selecione</option>
                    <option value="AC"<?=$val['estado']=='AC' ? ' selected' : null?>>AC</option>
                    <option value="AL"<?=$val['estado']=='AL' ? ' selected' : null?>>AL</option>
                    <option value="AM"<?=$val['estado']=='AM' ? ' selected' : null?>>AM</option>
                    <option value="AP"<?=$val['estado']=='AP' ? ' selected' : null?>>AP</option>
                    <option value="BA"<?=$val['estado']=='BA' ? ' selected' : null?>>BA</option>
                    <option value="CE"<?=$val['estado']=='CE' ? ' selected' : null?>>CE</option>
                    <option value="DF"<?=$val['estado']=='DF' ? ' selected' : null?>>DF</option>
                    <option value="ES"<?=$val['estado']=='ES' ? ' selected' : null?>>ES</option>
                    <option value="GO"<?=$val['estado']=='GO' ? ' selected' : null?>>GO</option>
                    <option value="MA"<?=$val['estado']=='MA' ? ' selected' : null?>>MA</option>
                    <option value="MG"<?=$val['estado']=='MG' ? ' selected' : null?>>MG</option>
                    <option value="MS"<?=$val['estado']=='MS' ? ' selected' : null?>>MS</option>
                    <option value="MT"<?=$val['estado']=='MT' ? ' selected' : null?>>MT</option>
                    <option value="PA"<?=$val['estado']=='PA' ? ' selected' : null?>>PA</option>
                    <option value="PB"<?=$val['estado']=='PB' ? ' selected' : null?>>PB</option>
                    <option value="PE"<?=$val['estado']=='PE' ? ' selected' : null?>>PE</option>
                    <option value="PI"<?=$val['estado']=='PI' ? ' selected' : null?>>PI</option>
                    <option value="PR"<?=$val['estado']=='PR' ? ' selected' : null?>>PR</option>
                    <option value="RJ"<?=$val['estado']=='RJ' ? ' selected' : null?>>RJ</option>
                    <option value="RN"<?=$val['estado']=='RN' ? ' selected' : null?>>RN</option>
                    <option value="RO"<?=$val['estado']=='RO' ? ' selected' : null?>>RO</option>
                    <option value="RR"<?=$val['estado']=='RR' ? ' selected' : null?>>RR</option>
                    <option value="RS"<?=$val['estado']=='RS' ? ' selected' : null?>>RS</option>
                    <option value="SC"<?=$val['estado']=='SC' ? ' selected' : null?>>SC</option>
                    <option value="SE"<?=$val['estado']=='SE' ? ' selected' : null?>>SP</option>
                    <option value="TO"<?=$val['estado']=='TO' ? ' selected' : null?>>TO</option>
                </select> 
				<p class="help-block">Estado em que reside</p>
			  </div>
			</div>


	  </fieldset>

    </div>
    <div class="tab-pane" id="social">

		<fieldset>

			<div class="control-group">
			  <label class="control-label" for="msn">MSN</label>
			  <div class="controls">
				<input type="text" class="input-xlarge email" placeholder='msn messenger' name='msn' id='msn' value='<?=$val['msn']?>'>
				<p class="help-block">Email do Mensageiro Instantaneo</p>
			  </div>
			</div>

			<div class="control-group">
			  <label class="control-label" for="gtalk">gTalk</label>
			  <div class="controls">
				<input type="text" class="input-xlarge email" placeholder='gtalk' name='gtalk' id='gtalk' value='<?=$val['gtalk']?>'>
				<p class="help-block">Email do Google Talk</p>
			  </div>
			</div>

			<div class="control-group">
			  <label class="control-label" for="skype">Skype</label>
			  <div class="controls">
				<input type="text" class="input-xlarge" placeholder='Skype' name='skype' id='skype' value='<?=$val['skype']?>'>
				<p class="help-block">Usuário do Skype</p>
			  </div>
			</div>

			<div class="control-group">
			  <label class="control-label" for="facebook">Facebook</label>
			  <div class="controls">
				<input type="text" class="input-xlarge url" placeholder='URL do perfil Facebook' name='facebook' id='facebook' value='<?=$val['facebook']?>'>
				<p class="help-block">URL do seu perfil do facebook</p>
			  </div>
			</div>

			<div class="control-group">
			  <label class="control-label" for="twitter">Twitter</label>
			  <div class="controls">
				<input type="text" class="input-xlarge url" placeholder='URL do perfil do Twitter' name='twitter' id='twitter' value='<?=$val['twitter']?>'>
				<p class="help-block">URL do seu perfil do Twitter</p>
			  </div>
			</div>

	  </fieldset>

    </div>
    <div class="tab-pane" id="senha">

		<fieldset>


			<?php if ($act=='update') { ?>

				<div class="control-group">
					<label class="control-label" for="senha_atual">* Senha Atual</label>
					<div class="controls">
						<input type="password" class="input-xlarge" placeholder='Por segurança, informe sua senha atual' name='senha_atual' id='senha_atual' value='<?=isset($res['senha_atual']) ? $res['senha_atual'] : null?>'>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="senha">* Nova Senha</label>
					<div class="controls">
						<input type="password" class="input-xlarge" placeholder='Sua nova senha' name='senha' id='senha' value='<?=isset($res['senha']) ? $res['senha'] : null?>'>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="senha_confirma">* Confirmar Senha</label>
					<div class="controls">
						<input type="password" class="input-xlarge" placeholder='Digite novamente sua nova senha, para confirmação' name='senha_confirma' id='senha_confirma' value='<?=isset($res['senha_confirma']) ? $res['senha_confirma'] : null?>'>
					</div>
				</div>

			<?php } else { ?>

				<div class="control-group">
					<label class="control-label" for="senha">* Senha</label>
					<div class="controls">
					<input type="password" class="input-xlarge required" placeholder='Senha para login' name='senha' id='senha' value='<?=isset($res['senha']) ? $res['senha'] : null?>'>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="senha_confirma">* Confirmar Senha</label>
					<div class="controls">
						<input type="password" class="input-xlarge" placeholder='Digite novamente sua senha, para confirmação' name='senha_confirma' id='senha_confirma' value='<?=isset($res['senha_confirma']) ? $res['senha_confirma'] : null?>'>
					</div>
				</div>

			<?php } ?>

		</fieldset>

    </div>
  </div>
</div>

    <div class='form-actions'>
		<?php
			if ($basename=='meu-cadastro') {
		?>
		<a href='<?=ABSPATH?>consultorio-sentimental' class='btn btn-info'>Voltar ao Consultório Sentimental</a>
		<?php
			}
		?>
		<input type='submit' value='ok' data-complete-text="concluido!" data-loading-text="aguarde..." class='btn btn-primary hide'>
		<input type='button' value='voltar' id='prev' class='btn hide'>
		<input type='button' value='avançar' id='next' class='btn'>
	</div>

</form>
