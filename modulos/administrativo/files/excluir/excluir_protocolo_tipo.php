<?
	$localModulo		= 1;
	$localOperacao		= 169;
	
	include('../../../../files/conecta.php');
	include('../../../../files/funcoes.php');
	include('../../../../rotinas/verifica.php');
	
	$local_IdLoja		= $_SESSION['IdLoja'];
	$local_IdProtocoloTipo	= $_GET['IdProtocoloTipo'];
	
	if(!permissaoSubOperacao($localModulo,$localOperacao,"D")){
		echo $local_Erro = 2;
	} else{
		$sql = "delete from ProtocoloTipo where IdLoja = $local_IdLoja and IdProtocoloTipo = $local_IdProtocoloTipo;";
		
		if(mysql_query($sql,$con)){
			echo $local_Erro = 7;
		} else{
			echo $local_Erro = 33;
		}
	}
?>