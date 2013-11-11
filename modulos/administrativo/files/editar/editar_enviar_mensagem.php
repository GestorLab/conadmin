<?	
	if($_SESSION[IdLoja] == ""){
		$_SESSION[IdLoja] = $_GET[IdLoja];
	}
	if($con == ""){
		include ('../../../../files/conecta.php');
	}
	$obs = "";
	$sqlObs = "SELECT
				Obs
			FROM 
				HistoricoMensagem
			WHERE 
			IdLoja = $local_IdLoja
			AND IdHistoricoMensagem = $local_IdHistoricoMensagem";
	$resObs	= mysql_query($sqlObs,$con);
	$linObs = mysql_fetch_array($resObs);		
		
	if($linObs[Obs] != ""){			
		$obs = date("d/m/Y H:i:s")." [".$local_Login."] - Cancelou o envio.";
	}else{
		$obs = date("d/m/Y H:i:s")." [".$local_Login."] - Cancelou o envio.";		
	}
		
	$obs .= "\n".$linObs[Obs];
	
	$sql = "UPDATE 
				HistoricoMensagem
			SET 
				IdStatus = 6,
				Obs 	 = '$obs'
			WHERE 
				IdLoja = $_SESSION[IdLoja]
				AND IdHistoricoMensagem = $_GET[IdHistoricoMensagem]";
	if(mysql_query($sql,$con) == true){
		
		$local_Erro = 4;
	}else{
		$local_Erro = 5;
	}		
?>