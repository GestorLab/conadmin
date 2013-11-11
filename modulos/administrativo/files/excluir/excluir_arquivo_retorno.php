<?
	$localModulo		=	1;
	$localOperacao		=	22;
	
	$local_IdLoja				=	$_SESSION["IdLoja"];	
	$local_IdLocalRecebimento	=	$_GET['IdLocalRecebimento'];
	$local_IdArquivoRetorno		=	$_GET['IdArquivoRetorno'];
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"select IdStatus from ArquivoRetorno WHERE IdLoja = $local_IdLoja and IdLocalCobranca=$local_IdLocalRecebimento and IdArquivoRetorno=$local_IdArquivoRetorno;";
		$res	=	mysql_query($sql,$con);
		$lin	=	mysql_fetch_array($res);
		
		if($lin[IdStatus]=='1'){	
			$sql_	=  	"SELECT EndArquivo FROM ArquivoRetorno WHERE IdLoja = $local_IdLoja and IdLocalCobranca=$local_IdLocalRecebimento and IdArquivoRetorno=$local_IdArquivoRetorno;";		
			$res_	=	mysql_query($sql_,$con);
			$lin_	=	mysql_fetch_array($res_);
			
			$sql	=	"DELETE FROM ArquivoRetorno WHERE IdLoja = $local_IdLoja and IdLocalCobranca=$local_IdLocalRecebimento and IdArquivoRetorno=$local_IdArquivoRetorno;";
			if(mysql_query($sql,$con)==true){
				@unlink("../../$lin_[EndArquivo]");
				echo $local_Erro = 7;
			}else{
				echo $local_Erro = 33;
			}
		}else{
			echo $local_Erro = 61;
		}
	}
?>