<?
	$localModulo		= 1;
	$localOperacao		= 155;
	
	include('../../../../files/conecta.php');
	include('../../../../files/funcoes.php');
	include('../../../../rotinas/verifica.php');
	
	$local_IdLoja		= $_SESSION['IdLoja'];
	$local_IdMalaDireta	= $_GET['IdMalaDireta'];
	
	if(!permissaoSubOperacao($localModulo,$localOperacao,"D")){
		echo $local_Erro = 2;
	} else{	
		$sql = "select 
					IdTipoMensagem,
					ExtModelo 
				from 
					MalaDireta 
				where 
					MalaDireta.IdLoja = '$local_IdLoja' and 
					IdMalaDireta = '$local_IdMalaDireta';";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		$sql = "START TRANSACTION;";
		@mysql_query($sql,$con);
		$tr_i = 0;
		
		$sql = "delete from MalaDireta where IdLoja = $local_IdLoja and IdMalaDireta = $local_IdMalaDireta;";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		if($lin[IdTipoMensagem] > 999999){
			$sql = "delete from HistoricoMensagem where IdLoja = $local_IdLoja and IdTipoMensagem = $lin[IdTipoMensagem] and IdStatus not in(2, 4, 5);";
			$local_transaction[$tr_i] = @mysql_query($sql,$con);
			$tr_i++;
			
			$sql = "delete from TipoMensagem where IdLoja = $local_IdLoja and IdTipoMensagem = $lin[IdTipoMensagem];";
			$local_transaction[$tr_i] = @mysql_query($sql,$con);
			$tr_i++;
		}
		
		for($i = 0; $i < $tr_i; $i++){
			if(!$local_transaction[$i]){
				$local_transaction = false;
				break;
			}
		}
		
		if($local_transaction){
			$EnderecoArquivo = "../../anexos/mala_direta/$local_IdLoja/$local_IdMalaDireta";
			
			for($x = 0; $x < 2; $x++){
				for($y = 0; ; $y++){
					if(!@unlink($EnderecoArquivo . "/" . md5($x . "_" . $y) . ".jpg")){
						break;
					}
				}
			}
			
			if($lin[ExtModelo] != "jpg"){
				@unlink("$EnderecoArquivo/$local_IdMalaDireta.$lin[ExtModelo]");
			}
			
			@rmdir($EnderecoArquivo);
			
			$sql = "commit;";
			echo $local_Erro = 7;
		} else{
			$sql = "rollback;";
			echo $local_Erro = 163;
		}
		
		@mysql_query($sql,$con);
	}
?>