<?
	$localModulo			=	1;
	$localOperacao			=	142;
	
	$local_IdLoja			=	$_SESSION['IdLoja'];
	$local_IdPessoa			=	$_GET['IdPessoa'];
	$local_IdContaDebito	=	$_GET['IdContaDebito'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	} else{		
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;
		
		$sql = "SELECT
					count(*) Qtd
				FROM
					Contrato
				WHERE
					IdLoja = $local_IdLoja AND 
					IdPessoa = $local_IdPessoa AND 
					IdContaDebito = $local_IdContaDebito";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		if($lin[Qtd] == 1){
			$local_transaction[$tr_i]	=	false;
			$tr_i++;
		}

		$sql = "SELECT
					count(*) Qtd
				FROM
					ContaReceber
				WHERE
					IdLoja = $local_IdLoja AND 
					IdPessoa = $local_IdPessoa AND 
					IdContaDebito = $local_IdContaDebito";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		if($lin[Qtd] == 1){
			$local_transaction[$tr_i]	=	false;
			$tr_i++;
		}

		$sql = "SELECT
					count(*) Qtd
				FROM
					ContaReceberPosicaoCobranca
				WHERE
					IdLoja = $local_IdLoja AND 
					IdPessoa = $local_IdPessoa AND 
					IdContaDebito = $local_IdContaDebito";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		if($lin[Qtd] == 1){
			$local_transaction[$tr_i]	=	false;
			$tr_i++;
		}

		$sql = "DELETE FROM 
					PessoaContaDebito 
				WHERE 
					IdLoja = $local_IdLoja AND 
					IdPessoa = $local_IdPessoa AND 
					IdContaDebito = $local_IdContaDebito;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			echo $local_Erro = 7;
			$sql = "COMMIT;";
		}else{
			echo $local_Erro = 33;
			$sql = "ROLLBACK;";
		}
		mysql_query($sql,$con);		
	}
?>