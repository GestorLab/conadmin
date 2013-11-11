<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_aberta_quadro_help_desk(){
		global $_GET;
		global $_SESSION;
		
		$Alterar = $_GET['Alterar'];
		
		if($Alterar == 1) {
			if($_SESSION["aberta_quadro_help_desk"] == 1){
				$_SESSION["aberta_quadro_help_desk"] = 0;
			} else{
				$_SESSION["aberta_quadro_help_desk"] = 1;
			}
		}
		
		return $_SESSION["aberta_quadro_help_desk"];
	}
	
	echo get_aberta_quadro_help_desk();
?>