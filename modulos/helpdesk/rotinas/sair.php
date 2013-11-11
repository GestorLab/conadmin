<?
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	
	session_start("ConAdmin_session_cda");
	session_destroy();
	
	$url_sair	=	getParametroSistema(95,7);
	
	if($url_sair == ""){
		$url_sair	=	"../index.php";
	}
	
	switch ($_GET['motivo']){
		case "manutencao":
			$urlExit = "../manutencao.php";
			break;
		default:
			$urlExit = "$url_sair";
			break;
	}
	header("Location: redireciona.php?urlExit=$urlExit");
?>
