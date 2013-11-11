<?
	if(substr($_GET['nome'],0,7) == 'filtro_'){
		include ('../../../files/conecta.php');
		include ('../../../files/conecta_cntsistemas.php');
		include ('../../../files/funcoes.php');
		include ('verifica.php');
		
		$_SESSION[$_GET['nome']]	=	$_GET['valor'];
	}
?>