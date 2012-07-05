<?php
## NOTA: CASO EM NENHUM OUTRO MODULO SEJA DEFINIDO O ARQUIVO HEADER, ESSE SERA O ARQUIVO PADRAO
# CSS INCLUIDO NO inc.header.php
$include_css = <<<end
     <link rel="stylesheet" href="${rp}js/bettertip/jquery.bettertip.css" type="text/css" />

end;


/*
 *Trecho js especial 
 */
$scriptInc = null;
//$scriptInc = isset($_GET['desempenho']) ? '' : null;
$extraJS = $eDataModelo = null;
if($act=='update') {
$extraJS = <<<end
end;
}
/*
 * // Trecho js especial 
 */


# JS INCLUIDO NO inc.header.php, também pode conter codigo js <script>alert();</script>
$pag = isset($_GET['pg'])?'&pg='.$_GET['pg']:'';
$letter = isset($_GET['letra'])?'&letra='.$_GET['letra']:'';
$include_js = <<<end
    <script type="text/javascript" src="${rp}js/bettertip/jquery.bettertip.js"></script>
    <script type="text/javascript" src="${rp}js/jquery.blockUI.js"></script>
    <script type="text/javascript" src="${rp}js/jquery.tablednd.js"></script>
    <script type="text/javascript" src="${rp}js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="${rp}js/jquery.mask.js"></script>
    <!--<script type="text/javascript" src="${rp}js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
    <script type="text/javascript" src="${rp}js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>-->

	{$scriptInc}
    

<script>
  {$extraJS}

	function changeArea(val)
	{

		if (val=='Principal') {

			$('.principal').slideDown();
			$('.principal').find('input').removeAttr('disabled');
			

		} else {

			$('.principal').slideUp();
			$('.principal').find('input').attr('disabled', 'disabled');
		}

	}


	$(function(){

		// mostra/oculta campos do formulário
		changeArea($('#area').val());
		$('#area').change(function() {
			changeArea($(this).val());
		});

	});
</script>
end;
