      <hr>
      <footer>
		  <p>&copy; Marcando Hora <?=date('Y')?></p>
      </footer>

    </div> <!-- /container -->
	<script type='text/javascript'>
		var basename='<?=$basename?>';
		var ABSPATH = '<?=ABSPATH?>';
	</script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="<?=JS?>jquery.maskedinput-1.3.min.js"></script>
	<script src="<?=$rph?>public/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?=JS?>application.js.php"></script>
	<script type='text/javascript'>
		<?=isset($incJS) ? $incJS : null?>

		$(function() {
			<?=isset($incjQuery) ? $incjQuery : null?>
		});
	</script>
	</body>
</html>
