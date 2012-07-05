<?php

$noGlobal = isset($_GET['noGlobal'])?1:0;
$noDb     = isset($_GET['noDb'])?1:0;
$noGlobalFunction = isset($_GET['noGlobalFunction'])?1:0;
$noHeader = isset($_GET['noHeader'])?1:0;
$noVar = isset($_GET['noVar'])?1:0;
$noVisual = isset($_GET['noVisual'])?1:0;
#verifica se foi setado variavel noGlobal, caso sim nao inclue variaveis e constantes 
 if ($noGlobal==0)
   include_once '_inc/global.php';
#verifica se foi setado variavel noDB, caso sim nao faz conexao com banco
 if ($noDb==0)
   include_once '_inc/db.php';
#verifica se foi setado variavel noFunctions, caso sim nao inclue funcoes 
 if ($noGlobalFunction==0)
   include_once '_inc/global_function.php';
#funcao autentica
  include_once 'inc.auth.php';


  # caso seja efetuado o logout
  if(isset($_GET['logout']))
    header('location: inc.logout.php');


# VARIAVEIS DO MÓDULO PADRAO DE JS E CSS
########################################
# CHAMA PADROES DO CONTEUDO CABEÇALHOS
 $def_inc_var = $rp.'mod.var.php';
 if (is_file($def_inc_var))
   include $def_inc_var;

# CABECALHOS DO HEADER, SAO ARQUIVOS JS, CSS
 $def_inc_header = $rp.'mod.header.php';
 if (is_file($def_inc_header))
   include $def_inc_header;


#VARIAVEIS DO MODULO ATUAL
##########################
## on modulo: se está em em algum módulo
if (isset($ap)) {

 $inc_var = $ap.'mod.var.php';
 if ($noVar==0 && is_file($inc_var))
   include $inc_var;

# CABECALHOS DO HEADER, SAO ARQUIVOS JS, CSS
 $inc_header = $ap.'mod.header.php';
 if ($noHeader==0 && is_file($inc_header))
   include $inc_header;

} #// on modulo



# CHAMA HEADER
if ($noVisual==0)
 include_once 'inc.header.php';

# CHAMA CONTEUDO
 switch(true) {
  case isset($_SESSION['user']) && empty($p):
    include_once 'default.php';
 break;

  case (!isset($_SESSION['user']) || $_SESSION['user']['id']==null) && $p=='esqueci-senha':
    include_once 'esqueci-senha.php';
 break;

  case !empty($ap):
    $inc_default = $ap.'default.php';
    if (is_file($inc_default))
      include_once $inc_default;
 break;

  default:
   include_once 'login.php';
   $noFooter=1;
 break;
 }

# CHAMA FOOTER
if ($noVisual==0 && !isset($noFooter))
 include_once 'inc.footer.php';
