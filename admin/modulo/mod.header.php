<?php
## NOTA: CASO EM NENHUM OUTRO MODULO SEJA DEFINIDO O ARQUIVO HEADER, ESSE SERA O ARQUIVO PADRAO


# CSS INCLUIDO NO inc.header.php
//<link href="css/reset.css" rel="stylesheet" />
$include_css = <<<end
<!-- <link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.1.css" media="screen" />-->
end;


# JS INCLUIDO NO inc.header.php, também pode conter codigo js <script>alert();</script>
/*
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.tipTip.js"></script>
*/
$include_js = <<<end
<!--<script type="text/javascript" src="${rp}js/fancybox/jquery.mousewheel-3.0.2.pack.js"></script>
    <script type="text/javascript" src="${rp}js/fancybox/jquery.fancybox-1.3.1.js"></script>-->
    <script type="text/javascript" src="${rp}js/jquery.blockUI.js"></script>
    <script type="text/javascript" src="${rp}js/jquery.validate.min.js"></script>

<script>
  $(function(){

      // validação do formulario, todos os campos com a classe
      // class="required" serao validados
	var container = $('.completeform-error');
	// validate the form when it is submitted
	var validator = $(".form-horizontal").validate({
		errorContainer: container,
		errorClass: 'error-validate',
		errorLabelContainer: $("ol", container),
		wrapper: 'li',
		meta: "validate"
	});
 

	/* APAGA 
	************************************/
	$(".trash").click(function(event){
	 event.preventDefault();
  	 var id_trash = $(this).attr('id');
  	 var href_trash = $(this).attr('href');
  	 var nome_trash = $(this).attr('name');

	  $.blockUI({
	   message: "<p>Tem certeza que deseja remover <b>"+nome_trash+"</b>?</p><br><input type='submit' value='sim' id='trash-sim'> <input type='button' value='não' id='trash-nao'>"
	  });

	// ACAO AO CLICAR EM NaO
	     $("#trash-nao").click(function(){
	      $.unblockUI();
	      return false;
	     });


	// ACAO AO CLICAR EM SIM
	     $("#trash-sim").click(function(){

		// BOX DE CARREGAMENTO
		$.blockUI({
		 message: "<img src='images/loading.gif'>",
		 css: { 
                   top:  ($(window).height()-24)/2+'px', 
                   left: ($(window).width()-24)/2+'px', 
		   width: '24px' 
            	 } 
		});

		$.ajax({
			type: "POST",
			url: href_trash,
			success: function(data){
			 $.unblockUI();
			 $.growlUI('Remoção',data);  
			 $('#tr'+id_trash).hide();
			}
		});

	     });



	});
	/* FIM: APAGA*/


	/* STATUS 
	************************************/
	$(".status").click(function(event){
	 event.preventDefault();
  	 var id_status = $(this).attr('id');
  	 var texto_status = $(this).text();
  	 var href_status  = $(this).attr('href');
  	 var nome_status  = $(this).attr('name');

		// BOX DE CARREGAMENTO
		$.blockUI({
		 message: "<img src='images/loading.gif'>",
		 css: { 
                   top:  ($(window).height()-24)/2+'px', 
                   left: ($(window).width()-24)/2+'px', 
		   width: '24px' 
            	 } 
		});

		$.ajax({
			type: "POST",
			url: href_status,
			success: function(data){
			 $.unblockUI();
			 $.growlUI('Status',data);  

			 if(texto_status=='Ativo')
			   $('.status'+id_status).html('<font color="#999999">Pendente</font>');

			   else
			    $('.status'+id_status).html('<font color="#000000">Ativo</font>');
			}
		});


	});
	/* FIM: STATUS*/

  });
</script>
end;
