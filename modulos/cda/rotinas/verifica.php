<?
	session_start("ConAdmin_session_cda");

	$localRetornoPrincipal	=	"index.php";
	$localRetornoIndex		=	"rotinas/sair.php";
	$bloqueio 				=	true;
	
	$local_login 	= $_SESSION["LoginCDA"];
	$local_IdLoja	= $_SESSION["IdLojaCDA"];
	$IdLoja			= $_SESSION["IdLojaCDA"];
	$local_IdPessoa	= $_SESSION["IdPessoaCDA"];
	$local_CPF		= $_SESSION["CPF_CNPJCDA"];
		
	if(!isset($_SESSION["LoginCDA"]) || !isset($_SESSION["IdLojaCDA"]) || !isset($_SESSION["IdPessoaCDA"])){
		session_destroy();
		header("Location: $localRetornoIndex");
	}
?>