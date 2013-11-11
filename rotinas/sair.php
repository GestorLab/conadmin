<?
	session_start("ConAdmin_session");
	session_destroy();
	switch ($_GET['motivo']){
		case "manutencao":
			$urlExit = "../manutencao.php";
			break;
		default:
			$urlExit = "../index.php";
			break;
	}
	header("Location: ../rotinas/redireciona.php?urlExit=$urlExit");
?>
