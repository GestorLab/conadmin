<?
	$localModulo		= 1;
	$localOperacao		= 166;
	
	include('../../../../files/conecta.php');
	include('../../../../files/funcoes.php');
	include('../../../../rotinas/verifica.php');
	
	$local_IdLoja		= $_SESSION['IdLoja'];
	$local_IdTemplate	= $_GET['IdTemplate'];
	
	if(!permissaoSubOperacao($localModulo,$localOperacao,"D")){
		echo $local_Erro = 2;
	} else{	
		$sql = "delete from TemplateMensagem where IdLoja = $local_IdLoja and IdTemplate = $local_IdTemplate;";
		
		if(@mysql_query($sql,$con)){
			echo $local_Erro = 7;
		} else{
			echo $local_Erro = 33;
		}
	}
?>