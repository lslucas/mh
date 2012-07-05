<?php
# CSS INCLUIDO NO inc.header.php
$def_include_css = <<<end
<style type='text/css'>
	div.growlUI { 
		background: url(${rp}images/warning.png) no-repeat;
	} div.growlUI h1, div.growlUI h2 {
		color: white;
		padding: 5px 5px 5px 60px;
		text-align: left;
		font-family:'Tahoma';
	} td.showDragHandle {
		background-image: url(${rp}images/drag.gif);
		background-repeat: no-repeat;
		background-position: center center;
		cursor: move;

	}.tDnD_whileDrag {
		background-color: #eee;
	}
</style>

end;


# JS INCLUIDO NO inc.header.php, também pode conter codigo js <script>alert();</script>
$def_include_js = <<<end
end;
