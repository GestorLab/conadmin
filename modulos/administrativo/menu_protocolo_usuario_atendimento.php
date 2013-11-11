<?php 
	$localModulo		= 1;
	$localOperacao		= 197;
	$localSuboperacao	= "V";
	$localCadComum		= true;
	
	# array( Protocolo/Usuário Atendimento );
	$array_operacao = array("197");
	
	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include("../../rotinas/verifica_menu.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='js/protocolo_usuario_atendimento.js'></script>
	</head>
	<body onLoad="ativaNome('<?php echo dicionario(922); ?>')">
		<?php include('filtro_protocolo_usuario_atendimento.php'); ?>
	</body>
</html>