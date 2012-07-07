<?php
	header('Content-type: text/javascript');

	/*
	*cabeçalho para funcoes,variaveis e conexao com a base
	*/
	$rp = '../../admin/';
	include_once '../../load.php';
?>
	$(function() {

		/*
		 *aplica mascara
		 */
		if ($('input[name="cpf"], .cpf'))
			$('input[name="cpf"], .cpf').mask('999.999.999-99');

		if ($('input[name="cnpj"], .cnpj'))
			$('input[name="cnpj"], .cnpj').mask('99.999.999/9999-99');

		if ($('input[name="cep"], .cep'))
			$('input[name="cep"], .cep').mask('99.999-999');

		if ($('.phone'))
			$('.phone').mask('(99) 9999-9999');

		if ($('.date'))
			$('.date').mask('99/99/9999');


		$('input, textarea').keydown(function(){
			if ($(this).attr('maxlength') && $(this).attr('maxlength')>0) {

				var maxlen = $(this).attr('maxlength');
				var actuallen = ($(this).val().length+1);
				var resul = maxlen-actuallen;
				var name = $(this).attr('name');
				var txt = '';
				
				if (resul>1)
					txt = ' Faltam '+resul+' caracteres!';
				else if (resul==0 || resul==-1)
					txt = ' Você chegou ao limite de '+maxlen+' caracteres!';
				else
					txt = ' Falta 1 caractere!';

				if ($('span#'+name).length==0)
					$('<br/><span class="small" id="'+name+'">'+txt+'</span>').insertAfter(this);
				else
					$('span#'+name).text(txt);

			}
		});

	});
