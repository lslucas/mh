 $(function(){

	/* LAYOUT
	******************** */

  function layout(docWidth) {

     // largura atual do documento
     if(docWidth==undefined)
      var docWidth = $(document).width();

       // largura do menu lateral
       var navWidth = $('nav').width();
       // correcao da largura, para margens
       var margemCorrecao = 65;

       //nova largura de container
       var novaLargura = docWidth-(navWidth+margemCorrecao);

       // define a largura de container
       $('.container').width(novaLargura);

  }


	 // largura em tempo real do documento
	 $(window).resize(function(){
 	  docWidth = $(document).width();
    layout(docWidth);
   });


    layout();


	/* //LAYOUT
	******************** */
 });
