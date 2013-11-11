<?
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	$_SESSION["Login"] = $_GET['NovoLogin']; 
	$_SESSION["IdPessoa"] = $_GET['NovoIdPessoa']; 
	
	include("../../../files/rotina_log_acesso.php");
	
	header("Location: ../conteudo.php");
?>
