<?
	$localModulo		=	1;
	$localOperacao		=	10001;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	//array( Radius )
	//$array_operacao 	= array( "10001" );
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
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
		<script type='text/javascript' src='js/radius_conexao_ativa.js'></script>
	</head>
	<body onLoad="ativaNome('Radius/Conexão Ativa')">
		<? 
			include('filtro_radius_conexao_ativa.php'); 
		?>
	</body>
</html>