<?
	$localModulo			= 1;
	$localOperacao			= 136;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja 			= $_SESSION["IdLoja"];
	$local_IdServico		= $_GET['IdServico'];
	$local_IdTipoServico	= $_GET['IdTipoServico'];
	$local_QtdMes			= $_GET['QtdMes'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	} else{
		$sql = "
			DELETE FROM 
				ServicoAgendamento 
			WHERE 
				IdLoja = $local_IdLoja and 
				IdServico = $local_IdServico and
				QtdMes = $local_QtdMes;";
		if(@mysql_query($sql,$con) == true){
			echo $local_Erro = 7;
		} else{
			echo $local_Erro = 6;
		}
	}
?>
