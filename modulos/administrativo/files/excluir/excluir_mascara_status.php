<?
	$localModulo		=	1;
	$localOperacao		=	68;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];	
	$local_IdServico	= $_GET['IdServico'];
	$local_IdStatus		= $_GET['IdStatus'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false) {
		echo $local_Erro = 2;
	} else {
		$sql = "START TRANSACTION;";
		@mysql_query($sql, $con);
		
		$tr_i = 0;
		$sql = "SELECT
					Obs
				FROM
					Servico
				WHERE
					Servico.IdLoja = '$local_IdLoja' AND
					Servico.IdServico = '$local_IdServico';";
		$res = @mysql_query($sql, $con);
		$lin = @mysql_fetch_array($res);
		
		if($lin[Obs] != '') {
			$lin[Obs] = "\n".$lin[Obs];
		}
		
		$local_Status = getParametroSistema(69, $local_IdStatus);
		
		$ObsServico = date("d/m/Y H:i:s") . " [" . $local_Login . "] - Mascar� Status " . $local_Status . " exclu�do." . str_replace("'", "\'", $lin[Obs]);
		$sql = "UPDATE Servico SET
					Obs				= '$ObsServico',
					LoginAlteracao	= '$local_Login',
					DataAlteracao	= concat(curdate(),' ',curtime())
				WHERE 
					IdLoja = '$local_IdLoja' AND
					IdServico = '$local_IdServico';";
		$local_transaction[$tr_i] = mysql_query($sql, $con);
		$tr_i++;
		
		$sql = "DELETE FROM ServicoMascaraStatus WHERE IdLoja = $local_IdLoja AND IdServico = $local_IdServico AND IdStatus = $local_IdStatus;";
		$local_transaction[$tr_i] = mysql_query($sql, $con);
		$tr_i++;
		
		for($i = 0; $i < $tr_i; $i++) {
			if(!$local_transaction[$i]) {
				$local_transaction = false;
				break;
			}
		}
		
		if($local_transaction) {
			$sql = "COMMIT;";
			echo $local_Erro = 7;
		} else { 
			$sql = "ROLLBACK;";
			echo $local_Erro = 33;
		}
		
		@mysql_query($sql, $con);
	}
?>