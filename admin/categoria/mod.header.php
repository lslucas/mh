<?php
## NOTA: CASO EM NENHUM OUTRO MODULO SEJA DEFINIDO O ARQUIVO HEADER, ESSE SERA O ARQUIVO PADRAO


# CSS INCLUIDO NO inc.header.php
//<link href="css/reset.css" rel="stylesheet" />
$include_css = <<<end
end;


# JS INCLUIDO NO inc.header.php, também pode conter codigo js <script>alert();</script>
/*
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.tipTip.js"></script>
*/
$include_js = <<<end
    <script type="text/javascript" src="${rp}js/jquery.blockUI.js"></script>
    <script type="text/javascript" src="${rp}js/jquery.tablednd.js"></script>
    <script type="text/javascript" src="${rp}js/jquery.maskedinput-1.2.2.min.js"></script>
    <script type="text/javascript" src="${rp}js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
    <script type="text/javascript" src="${rp}js/jquery.validate.min.js"></script>
    
<script>
  $(function(){
      // validação do formulario, todos os campos com a classe
      // class="required" serao validados
	var container = $('div.container-error');
	// validate the form when it is submitted
	var validator = $(".form").validate({
		errorContainer: container,
		errorClass: 'error-validate',
		errorLabelContainer: $("ol", container),
		wrapper: 'li',
		meta: "validate"
	});


	$('.divPlano').slideUp();
	$('.divMarca').hide();
	$('.divPlano').find('input, select, textarea').attr('disabled', true);
	$('.divMarca').find('input, select, textarea').attr('disabled', true);

	$('#area').change(function() {

		var val = $('#area option:selected').val();
		var retrn = 0;

		if(val=='Marca') {
			$('.divMarca').hide();
			$('.divMarca').find('input, select, textarea').attr('disabled', true);
			retrn = 1;
		}

		if(val=='Modelo') {
			$('.divMarca').show();
			$('.divMarca').find('input, select, textarea').attr('disabled', false);
			retrn = 1;
		}

		if(val=='Plano') {
			$('.divPlano').slideDown();
			$('.divPlano').find('input, select, textarea').attr('disabled', false);
			retrn = 1;
		} else {
			$('.divPlano').slideUp();
			$('.divPlano').find('input, select, textarea').attr('disabled', true);
		}


		if(retrn==0) {
			$('div.divMarca').hide();
		}

	}).change();




  });
</script>
end;
