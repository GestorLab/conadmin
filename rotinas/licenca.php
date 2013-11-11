<?
	include("../files/conecta.php");
	include("../files/funcoes.php");

	$Vars		= Vars();
	$KeyCode	= KeyCode($Vars[IdLicenca],1);

	$File		= file("http://intranet.cntsistemas.com.br/licenca/licenca.php?KeyCode=$KeyCode");
	$KeyLicenca = end($File);
	KeyProcess($KeyCode, $KeyLicenca);

	header("Location: ../index.php");
?>