<?
	$localModulo		= 1;
	$localOperacao		= 138;
	
	$local_IdLoja 		= $_SESSION["IdLoja"];
	$local_IdServico	= $_GET['IdServico'];
	$local_CFOP			= $_GET['CFOP'];
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		echo $local_Erro = 2;
	} else{
		// Sql de Inserção de Help Desk //
		$sql	=	"START TRANSACTION;";
		mysql_query($sql, $con);
		$tr_i = 0;
		
		$temp = explode(',', $local_CFOP);
		for($i = 1; $i < count($temp); $i++){
			$sql = "
				INSERT INTO ServicoCFOP SET
					IdLoja			= '$local_IdLoja',
					IdServico		= '$local_IdServico',
					CFOP			= '$temp[$i]';";
			$local_transaction[$tr_i] = @mysql_query($sql, $con);
			$tr_i++;
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		} else{
			$sql = "ROLLBACK;";
			$local_Erro = 8;			// Mensagem de Inserção Negativa
		}
		
		mysql_query($sql, $con);
		echo $local_Erro;
	}
?>
