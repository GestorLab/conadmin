<?
	session_start("ConAdmin_session_HD");

	$localRetornoPrincipal	=	"index.php";
	$localRetornoIndex		=	"rotinas/sair.php";
	
	$local_login 	= $_SESSION["LoginHD"];
		
	if(!isset($_SESSION["LoginHD"])){
		session_destroy();
		header("Location: $localRetornoIndex");
	}
	
	if(($localOperacao != '' || $localOperacao == 0) && $localSuboperacao !=''){
		if(permissaoSubOperacao($localModulo, $localOperacao, $localSuboperacao) == false){
			header("Location: sem_permissao.php");
		}
	}
?>
