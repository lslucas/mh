    $(function(){

	/* MENU
	******************** */
	// mostra todas as setas down
	$('.down').show();

	// ao clicar em um menu que possua submenu mostra/esconde o submenu
	  $('.has-submenu > span').toggle(
	   function(){
   	    var pai = $(this).parent();
	    $(pai).children('.submenu').slideDown('fast');
	    $(pai).children('.arrow-menu').hide();
	    $(pai).children('.up').show();
	    },function(){
   	    var pai = $(this).parent();
	    $(pai).children('.submenu').slideUp('fast');
	    $(pai).children('.arrow-menu').hide();
	    $(pai).children('.down').show();
	    }
	   
	  );
/*
	$('.arrow-menu').click(
 	 function(){

   	  var pai = $(this).parent();

	  if ($(this).hasClass('down')==true){
	  $(pai).children('.submenu').slideDown();
	  $(pai).children('.arrow-menu').hide();
	  $(pai).children('.up').show();
	  } else {
	  $(pai).children('.submenu').slideUp();
	  $(pai).children('.arrow-menu').hide();
	  $(pai).children('.down').show();
 	  }
	 }
	);
*/
	/* //MENU
	******************** */
    });
