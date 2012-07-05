<?php
 $noFooter=1;
 #mostra msg caso haja
?>
<style type='text/css'>
	body { background-color:#d9d9d9; }
	.column-container { width:530px; }
</style>
<center>
	<form action="index.php" method="post" class='cmxform form-inline login' style='width:530px; text-align: left; '>
	<div class='well'>
			<?php

				if (isset($msgNotice) && !empty($msgNotice)) {
				 echo "
					 <div class='alert alert-error'>
						<a class='close' data-dismiss='alert'>×</a>
						$msgNotice
					</div>
					 ";
				}

			?>
			<?php if (file_exists(PATH_ADMLOGO)) { ?>
			<img src='<?=PATH_ADMLOGO?>' border=0 style='float: left; margin-right:10px; margin-top: 0px;'/>
			<?php }  else { ?>
			<img src="http://placehold.it/160x180" alt="Logo" style='float: left; margin-right:20px;'>
			<?php } ?>
		  <fieldset>
			<!--<legend>Legend text</legend>-->
			<div class="control-group">
			  <label class="control-label" for="username">Email</label>
			  <div class="controls">
				<div class="input-prepend">
	                <span class="add-on"><i class='icon-user'></i></span><input type="text" class="input-xlarge email required" placeholder='email@provedor.com.br' name='username' id='username'>
				</div>
			  </div>
			</div>

			<div class="control-group">
			  <label class="control-label" for="password">Senha</label>
			  <div class="controls">
				
				<div class="input-prepend">
	                <span class="add-on"><i class='icon-lock'></i></span><input type="password" class="input-xlarge required" placeholder='senha' name='password' id='password'>
				</div>

			  </div>
			</div>


			<div class='form-actions'>
				<input type='submit' value='Login' class='btn btn-primary'>
				<a href='esqueci-senha.php' value='Esqueci minha senha' class='btn'>Esqueci a senha</a>
			</div>

			</fieldset>
	</div>
	</form>
</center>
