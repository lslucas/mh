    $(function(){

	/* PANEL
	******************** */
	// mostra todas as setas up-box
	$('.up-box').show();

	// ao clicar no titulo do box mostra/esconde o conteudo
	$('.box-title').toggle(
 	 function(){
   	  var pai = $(this).parent();
	  $(pai).children('.box-content').slideUp();
	  $(this).children('.arrow-box').hide();
	  $(this).children('.down-box').show();
	  },function(){
   	  var pai = $(this).parent();
	  $(pai).children('.box-content').slideDown();
	  $(this).children('.arrow-box').hide();
	  $(this).children('.up-box').show();
 	  }
	 
	);

	/* //PANEL
	******************** */


    });
