<?
	$localModulo		=	1;
	$localOperacao		=	26;
	
	$local_IdLoja 			= 	$_SESSION["IdLoja"];
	$local_IdOrdemServico	=	$_GET['IdOrdemServico'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;	
	
		$sql = "DELETE FROM OrdemServicoParametro WHERE IdLoja = '$local_IdLoja' and IdOrdemServico = '$local_IdOrdemServico';";
		$local_transaction[$tr_i]	= mysql_query($sql,$con);
		$tr_i++;
	
		$sql = "DELETE FROM OrdemServicoParcela WHERE IdLoja = '$local_IdLoja' and IdOrdemServico = '$local_IdOrdemServico';";
		$local_transaction[$tr_i]	= mysql_query($sql,$con);
		$tr_i++;
		
		$sql = "
			SELECT
				IdAnexo,
				NomeOriginal
			FROM
				OrdemServicoAnexo
			WHERE
				IdLoja = '$local_IdLoja' AND
				IdOrdemServico = '$local_IdOrdemServico';";
		$res = @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			$ext = end(explode(".", $lin[NomeOriginal]));
			$url = "../../anexos/ordem_servico/".$local_IdOrdemServico."/".$lin[IdAnexo].".".$ext;
		
			$sql0 = "DELETE FROM OrdemServicoAnexo WHERE IdLoja = '$local_IdLoja' AND IdOrdemServico = '$local_IdOrdemServico' AND IdAnexo = '$lin[IdAnexo]';";
			$local_transaction[$tr_i]	= mysql_query($sql0, $con);
			
			if($local_transaction[$tr_i] == true){
				@unlink($url);
			}
			
			$tr_i++;
		}
	
		$sql = "DELETE FROM OrdemServico WHERE IdLoja = '$local_IdLoja' and IdOrdemServico = '$local_IdOrdemServico';";
		$local_transaction[$tr_i]	= mysql_query($sql,$con);
		$tr_i++;
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			echo $local_Erro = 7;
			@rmdir("../../anexos/ordem_servico/".$local_IdOrdemServico);
			$sql = "COMMIT;";
		}else{
			echo $local_Erro = 33;
			$sql = "ROLLBACK;";
		}
		
		mysql_query($sql,$con);
	}
?>