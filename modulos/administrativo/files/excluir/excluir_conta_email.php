<?
	$localModulo		= 1;
	$localOperacao		= 165;
	
	include('../../../../files/conecta.php');
	include('../../../../files/funcoes.php');
	include('../../../../rotinas/verifica.php');
	
	$local_IdLoja		= $_SESSION['IdLoja'];
	$local_IdContaEmail	= $_GET['IdContaEmail'];
	
	if(!permissaoSubOperacao($localModulo,$localOperacao,"D")){
		echo $local_Erro = 2;
	} else{			
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;

		$sql = "select
					IdTipoMensagem				
				from
					TipoMensagem
				where
					IdLoja = $local_IdLoja and
					IdContaEmail = $local_IdContaEmail and
					Titulo = 'Teste de Conta de Email'";
		$res = mysql_query($sql,$con);
		if($lin = @mysql_fetch_array($res)){
			$sql = "delete from TipoMensagemParametro where IdLoja = $local_IdLoja and IdTipoMensagem = $lin[IdTipoMensagem];";
			$local_transaction[$tr_i] = mysql_query($sql,$con);	
			$tr_i++;
			
			$sql = "delete from TipoMensagem where IdLoja = $local_IdLoja and IdTipoMensagem = $lin[IdTipoMensagem];";
			$local_transaction[$tr_i] = mysql_query($sql,$con);	
			$tr_i++;	
		}

		$sql = "delete from ContaEmail where IdLoja = $local_IdLoja and IdContaEmail = $local_IdContaEmail;";
		$local_transaction[$tr_i] = mysql_query($sql,$con);	
		$tr_i++;		

		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
				break;
			}
		}
		
		if($local_transaction == true) {		
			$sql = "COMMIT;";
			@mysql_query($sql,$con);
			
			echo $local_Erro = 7;
		} else {
			$sql = "ROLLBACK;";
			@mysql_query($sql,$con);			
			
			echo $local_Erro = 33;
		}		
	}
?>