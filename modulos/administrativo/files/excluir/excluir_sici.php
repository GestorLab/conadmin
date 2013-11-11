<?
	$localModulo	= 1;
	$localOperacao	= 159;
	
	include("../../../../files/conecta.php");
	include("../../../../files/funcoes.php");
	include("../../../../rotinas/verifica.php");
	
	$local_PeriodoApuracao	= $_GET['PeriodoApuracao'];
	
	if(!permissaoSubOperacao($localModulo, $localOperacao, "D")){
		echo $local_Erro = 2;
	} else{	
		$sql = "START TRANSACTION;";
		@mysql_query($sql,$con);
		$tr_i = 0;
		
		if(@ereg("([0-9])/([0-9])", $local_PeriodoApuracao)){
			$local_PeriodoApuracao = dataConv($local_PeriodoApuracao, "m/Y", "Y-m");
		}
		
		$sql = "DELETE FROM SICICidadeTecnologiaVelocidade WHERE PeriodoApuracao = '$local_PeriodoApuracao';";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		$sql = "DELETE FROM SICICidadeTecnologia WHERE PeriodoApuracao = '$local_PeriodoApuracao';";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		$sql = "DELETE FROM SICICidade WHERE PeriodoApuracao = '$local_PeriodoApuracao';";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		$sql = "DELETE FROM SICIEstadoVelocidade WHERE PeriodoApuracao = '$local_PeriodoApuracao';";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		$sql = "DELETE FROM SICIEstado WHERE PeriodoApuracao = '$local_PeriodoApuracao';";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		$sql = "DELETE FROM SICILancamento WHERE PeriodoApuracao = '$local_PeriodoApuracao';";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		$sql = "DELETE FROM SICI WHERE PeriodoApuracao = '$local_PeriodoApuracao';";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		if(!@in_array(false, $local_transaction)){
			$sql = "COMMIT;";
			echo $local_Erro = 7;
		} else{
			$sql = "ROLLBACK;";
			echo $local_Erro = 6;
		}
		
		@mysql_query($sql,$con);
	}
?>