<?
	include("../files/conecta.php");
	include("../files/funcoes.php");
	include("../modulos/administrativo/local_cobranca/21/funcoes.php");

	processa_retorno_f2b($_GET['num_billing']);

	header("Location: ../modulos/cda/menu.php?ctt=listar_conta_receber.php&IdParametroSistema=1");
?>