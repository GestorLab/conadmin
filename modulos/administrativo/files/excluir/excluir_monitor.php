<?
	$localModulo		= 1;
	$localOperacao		= 151;
	
	include('../../../../files/conecta.php');
	include('../../../../files/funcoes.php');
	include('../../../../rotinas/verifica.php');
	
	$local_IdLoja		= $_SESSION['IdLoja'];
	$local_IdMonitor	= $_GET['IdMonitor'];
	
	if(!permissaoSubOperacao($localModulo,$localOperacao,"D")){
		echo $local_Erro = 2;
	} else{
		$sql = "START TRANSACTION;";
		@mysql_query($sql,$con);
		$tr_i = 0;
		
		$sql = "delete from MonitorPortaLog where IdLoja = $local_IdLoja and IdMonitor = $local_IdMonitor;";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		$sql = "delete from MonitorPortaAlarme where IdLoja = $local_IdLoja and IdMonitor = $local_IdMonitor;";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		$sql = "delete from MonitorPorta where IdLoja = $local_IdLoja and IdMonitor = $local_IdMonitor;";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		for($i = 0; $i < $tr_i; $i++){
			if(!$local_transaction[$i]){
				$local_transaction = false;
				break;
			}
		}
		
		if($local_transaction){
			$sql = "commit;";
			echo $local_Erro = 7;
		} else{
			$sql = "rollback;";
			echo $local_Erro = 6;
		}
		
		@mysql_query($sql,$con);
	}
?>