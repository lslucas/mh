<?php
	session_start();

	header('Content-type: text/javascript');
	include_once "../_inc/global.php";
	include_once "../_inc/global_function.php";
?>
/*
 *Modal Global de mensagens e alertas
 */
function showModal(args)
{
		if (!args) {
			if(typeof console.log == 'function') console.log('showModal: Argumento inválido!');
			else alert('showModal: Argumento inválido!');

			return false;
		}

		args = JSON.parse(args);


		//titulo do modal
		var title = null;
		if (args.title)
			title = " <h3>"+args.title+"</h3>";

		// botão fechar/cancelar
		if (args.buttonClose)
			var closeButton = args.buttonClose;
		else
			closeButton = 'Fechar';


		// botão de ação 1
		var actionButton1 =  '';
		if (args.button) {

			var btn_color = 'btn-primary';
			if (args.button.color!=undefined)
				btn_color = args.button.color;

			var btn_class = '';
			if (args.button.class)
				btn_class = args.button.class;

			var btn_id = '';
			if (args.button.id)
				btn_id = args.button.id;

			var btn_name = '';
			if (args.button.name)
				btn_name = args.button.name;

			var btn_href = 'javascript:void(0);';
			if (args.button.href)
				btn_href = args.button.href;

			var btn_target = ' ';
			if (args.button.target)
				btn_target = " target='"+args.button.target+"' ";

			if (args.button)
				actionButton1 = "<a href='"+btn_href+"'"+btn_target+"id='"+btn_id+"' name='"+btn_name+"' class='btn-rm btn "+btn_class+" "+btn_color+"'>"+args.button.value+"</a>";

		}



		// botão de ação 2
		var actionButton2 = '';
		if (args.button2) {

			var btn_color = 'btn-primary';
			if (args.button2.color!=undefined)
				btn_color = args.button2.color;

			var btn_class = '';
			if (args.button2.class)
				btn_class = args.button2.class;

			var btn_id = '';
			if (args.button2.id)
				btn_id = args.button2.id;

			var btn_name = '';
			if (args.button2.name)
				btn_name = args.button2.name;

			var btn_href = 'javascript:void(0);';
			if (args.button2.href)
				btn_href = args.button2.href;

			var btn_target = ' ';
			if (args.button2.target)
				btn_target = " target='"+args.button2.target+"' ";

			if (args.button2)
				actionButton2 = "<a href='"+btn_href+"'"+btn_target+"id='"+btn_id+"' name='"+btn_name+"' class='btn-rm btn "+btn_class+" "+btn_color+"'>"+args.button2.value+"</a>";

		}




		// cria template do modal
		var template = "<div class='modal fade hide' id='msg-modal'>";
		template += "<div class='modal-header'> <a class='close' data-dismiss='modal'>×</a>"+title+"</div>";
		template += "<div class='modal-body'>";
		template += "<p>"+args.content+"</p>";
		template += "</div>";
		template += "<div class='modal-footer'>";

		template += actionButton1;
		template += actionButton2;
		template += "	<a href='javascript:void(0);' class='btn' data-dismiss='modal'>"+closeButton+"</a>";
		template += "</div></div>";


		if ($('#html-msg'))
			$('#html-msg').html(template);
		else
			$(template).appendTo('body');

		$('#msg-modal').modal('show');

}

$(function(){

	$('.tip').tooltip({animation: true, placement: 'bottom', delay: { show: 500, hide: 100 }});
	$(".alert").alert();
	//$('.tabs').button();
	//$(".collapse").collapse();
	//$('.typeahead').typeahead();

	// Acao ao clicar no botao voltar
	$('#form-back').click(function(){
		window.location='<?=$_SESSION['rp']?>?p=<?=$_SESSION['p']?>';
		return true;
	});

	// Acao ao clicar no botao voltar
	$('#form-back-home').click(function(){
		window.location='index.php';
		return true;
	});

	<?php /*
	// MOSTRA AS ACOES AO PASSAR O MOUSE SOBRE A TR DO ÍTEM DA TABELA
	if ($('.table')) {
		$('.table').find('.row-actions').hide();
		$('.table tr').bind('mouseenter',function(){
			$(this).find('.row-actions').show();
		}).bind('mouseleave',function(){
			$(this).find('.row-actions').hide();
		});
	}
	*/
	?>
	// validação do formulario, todos os campos com a classe
	// class="required" serao validados
	if ($('.completeform-error')) {
		var container = $('.completeform-error');
		// validate the form when it is submitted
		var validator = $(".cmxform").validate({
			errorContainer: container,
			errorClass: 'error-validate',
			errorLabelContainer: $("ol", container),
			wrapper: 'li',
			meta: "validate"
		});
	}






	/*
     *ao arrastar alguma linha altera a posição dos elementos
     *e altera na banco
	 */
	if ($('#posGaleria') && typeof $().tableDnD == 'function') {

		$('#posGaleria').tableDnD({
			onDrop: function(table, row) {

			  $.ajax({
				 type: "POST",
				 url: "<?=$_SESSION['p']?>/helper/ajax.galeria_pos.php",
				 data: $.tableDnD.serialize()
			  });

			}
		});

		// ao passar o mouse sobre a linha add a classe para mostrar a imagem de +
		$("#posGaleria tr").hover(function() {
		   $(this.cells[0]).addClass('showDragHandle');
		}, function() {
			$(this.cells[0]).removeClass('showDragHandle');
		});

	}






	/*
	 *adiciona mais um campo file a cada vez que é clicado no elemento
	 *com a classe class="addImagem"
	 */
	if ($('.addImagem')) {
		$('.addImagem').click(function(){
			var i = parseInt($('.galeria:last').attr('alt'));

			$('.divImagem:first').clone().insertAfter('.divImagem:last').slideDown();
			$('.divImagem:last > .galeria').attr('name','galeria'+(i+1)).attr('alt',(i+1)).val('');
		});
	}





	/* APAGA IMAGEM/ARQUIVO
	************************************/
	$(".trash-galeria").click(function(event){

		event.preventDefault();
		var id_trash = $(this).attr('id');
		var href_trash = $(this).attr('href');
		var args = '';


		if (!$(this).attr('alt'))
			args = '{"title": "Apagar Imagem", "content": "Tem certeza que deseja remover?", "buttonClose": "Cancelar", "button": {"id": "trash-galeria-sim", "value": "Apagar", "color": "btn-danger"}}';
		else
			args = $(this).attr('alt');

		showModal(args);


		// ACAO AO CLICAR EM SIM
		$("#trash-galeria-sim").click(function(){

			<?=$LOADING?>
			$.ajax({
				type: "POST",
				url: href_trash,
				success: function(data){

					$.unblockUI();
					$.growlUI(data);  
					$('#'+id_trash).hide();
					$('#msg-modal').modal('hide');

					if ($(this).attr('data-reload'))
						setTimeout(window.location.reload(), 3000);
				}
			});
		});

	});
	/* FIM: APAGA GALERIA */



	/* ALTERA STATUS LISTAGEM
	************************************/
	$(".status").click(function(event) {
	 event.preventDefault();
  	 var id_status = $(this).attr('id');
  	 var texto_status = $(this).text();
  	 var href_status  = $(this).attr('href');
  	 var nome_status  = $(this).attr('name');
  	 var json  = JSON.parse($(this).attr('alt'));

		<?=$LOADING?>
		$.ajax({
			type: "POST",
			url: href_status,
			success: function(data){
			 $.unblockUI();
			 $.growlUI(data);  

			 if(texto_status=="Ativo" || texto_status==" Ativo")
			   $('.status'+id_status).html(json.inativo);

			   else
			    $('.status'+id_status).html(json.ativo);
			}
		});


	});
	/* FIM: ALTERA STATUS LISTAGEM */


	/* APAGA 
	************************************/
	$(".btn-rm").click(function(event){
		event.preventDefault();
		var id_rm = $(this).attr('id');
		var href_rm = $(this).attr('href');

		$('.modal').modal('hide');

		<?=$LOADING?>
		$.ajax({
			type: "POST",
			url: href_rm,
			success: function(data){
			 $.unblockUI();
			 $.growlUI(data);  
			 $('#tr'+id_rm).hide();
			}
		});

	});
	/* FIM: APAGA*/



	/*
	 *mascaras e etc
	 * tinymce, add bbcode no textarea texto
	 */
	if ($('textarea.tinymce') && typeof $().tinymce == 'function')
		$('textarea.tinymce').tinymce({ <?=$TinyMCE?> });

	//mascaras 
	if(typeof $().mask == 'function') {
		$('.phone').mask('(99) 9999-9999');
		$('.data, .date').mask('99/99/9999');
		$('.year').mask('9999');
		$('.cpf, #cpf').mask('999.999.999-99');
		$('.cep').mask('99.999-999');
		$('.uf').mask('aa');
		$('.cnpj').mask('99.999.999/9999-99');
		$('.doubleint').mask('99');
		$('.year').mask('9999');
		$('.hour').mask('99:99');
	}





});
