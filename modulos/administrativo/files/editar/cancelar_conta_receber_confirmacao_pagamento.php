<?
	$localModulo		=	1;
	$localOperacao		=	137;
	$localSuboperacao	=	"U";	
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_Login	= $_SESSION["Login"];
	$local_IdLoja	= $_SESSION["IdLoja"];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,$localSuboperacao) == false){
		$local_Erro = 2;
	}else{
		$local_IdContaReceber = $_GET['IdContaReceber'];
		$where = "";
		
		$sql1 = "SELECT 
					Obs,
					IdStatusConfirmacaoPagamento				
				FROM 
					ContaReceber 
				WHERE 
					IdLoja = '$local_IdLoja' and 
					IdContaReceber = '$local_IdContaReceber';";
		$res1 = mysql_query($sql1,$con);
		$lin1 = mysql_fetch_array($res1);
		
		if($lin1[IdStatusConfirmacaoPagamento] == 1){
			$local_Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Cancelamento de Confirmação de Pagamento Pré-Confirmado.\n".trim($lin1[Obs]);
			$local_IdStatusConfirmacaoPagamento = "NULL";
			$where = "and IdStatusConfirmacaoPagamento	= '1'";
		} else if($lin1[IdStatusConfirmacaoPagamento] == 2){ 
			$local_Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Cancelamento de Confirmação de Pagamento Negada.\n".trim($lin1[Obs]);
			$local_IdStatusConfirmacaoPagamento = 3;
			$where = "and IdStatusConfirmacaoPagamento	= '2'";
		}
		
		$sql = "UPDATE 
					ContaReceber 
				SET	
					Obs								= '$local_Obs',
					IdStatusConfirmacaoPagamento	= $local_IdStatusConfirmacaoPagamento,
					LoginAlteracao					= '$local_Login',
					DataAlteracao					= concat(curdate(),' ',curtime())
				WHERE 
					IdLoja = '$local_IdLoja' AND
					IdContaReceber = '$local_IdContaReceber'
					$where;";
		
		if(mysql_query($sql,$con) == true){
			echo 67;			// Mensagem de Cancelamento Positiva
		}else{
			echo 68;			// Mensagem de Cancelamento Negativa
		}
	}
?>
