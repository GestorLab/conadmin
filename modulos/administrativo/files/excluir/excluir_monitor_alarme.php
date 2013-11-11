<?
	$localModulo		= 1;
	$localOperacao		= 176;
	
	include('../../../../files/conecta.php');
	include('../../../../files/funcoes.php');
	include('../../../../rotinas/verifica.php');
	
	$local_IdLoja			= $_SESSION['IdLoja'];
	$local_IdMonitor		= $_GET['IdMonitor'];
	$local_IdTipoMensagem	= $_GET['IdTipoMensagem'];
	$local_IdStatus			= $_GET['IdStatus'];
	$local_Local			= $_GET['Local'];
	
	if(!permissaoSubOperacao($localModulo,$localOperacao,"D")){
		echo $local_Erro = 2;
	} else{
		if($local_Local == "MonitorAlarme"){
			$where ="";
		}else{
			$where ="and IdTipoMensagem = '$local_IdTipoMensagem'";
		}
		
		$sql = "delete from 
					MonitorPortaAlarme 
				where
					IdLoja = '$local_IdLoja' and 
					IdMonitor = '$local_IdMonitor' and 
					IdStatus = '$local_IdStatus'
					$where";
		
		if(@mysql_query($sql,$con)){
			echo $local_Erro = 7;
		} else{
			echo $local_Erro = 6;
		}
	}
?>