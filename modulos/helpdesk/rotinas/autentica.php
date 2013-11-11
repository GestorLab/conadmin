<?php
	if($_SESSION["Login"] == ''){
		include ("../../../files/conecta.php");
		include ("../../../files/funcoes.php");
		
		$formDados[IdLoja]		= $_POST['IdLoja'];
		$formDados[Login]		= $_POST['Login'];
		$formDados[Senha]		= $_POST['Senha'];
	}
	
	$local_urlRetornoErro	=	"../../../helpdesk.php";
	
	if((validaAutenticacaoLogin($formDados[Login], $formDados[Senha]) && in_array($formDados[IdLoja], explode(",",lojas_permissao_login($formDados[Login])))) || $_SESSION["Login"] != '') {
		if($_SESSION["Login"] != '') {
			$formDados[IdLoja] = $_SESSION['IdLoja'];
			$formDados[Login] = $_SESSION["Login"];	
		}
		
		session_cache_expire (720);
		session_start("ConAdmin_session_HD");

		$_SESSION["LoginHD"]					=	$formDados[Login];
		$_SESSION["IdLojaHD"]					=	$formDados[IdLoja];
		$_SESSION["IdLoja"]						=	$formDados[IdLoja];
		$_SESSION["IdLogAcessoHelpDesk"]		=	LogAcessoHelpDesk();
		$_SESSION["filtro_help_desk_concluido"]	=	getCodigoInterno(3,143);
		// Carrega as variáveis do config
		$Vars = Vars();
		$VarsKeys = array_keys($Vars);
		
		for($i = 0; $i < count($VarsKeys); $i++) {
			$_SESSION[$VarsKeys[$i]] = $Vars[$VarsKeys[$i]];
		}
		// Fim - Carrega as variáveis do config
		header("Location: ../index.php$local_Url");
	} else {
		header("Location: $local_urlRetornoErro");
	}
?>