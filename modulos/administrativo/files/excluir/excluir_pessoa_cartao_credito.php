<?
	$localModulo			=	1;
	$localOperacao			=	142;
	
	$local_IdLoja			=	$_SESSION['IdLoja'];
	$local_IdPessoa			=	$_GET['IdPessoa'];
	$local_IdCartao			=	$_GET['IdCartao'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	} else{
		$sql = "DELETE FROM 
					PessoaCartao 
				WHERE 
					IdLoja = $local_IdLoja AND 
					IdPessoa = $local_IdPessoa AND 
					IdCartao = $local_IdCartao;";
		if(mysql_query($sql,$con) == true){
			echo $local_Erro = 7;			
		} else{
			echo $local_Erro = 33;			
		}
	}
?>