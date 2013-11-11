<?
	$localModulo		=	1;
	$localOperacao		=	1;
	
	$local_IdPessoa		=	$_GET['IdPessoa'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	} else{
		$sql = "start transaction;";
		mysql_query($sql,$con);
		
		$tr_i = 0;
		$sql = "select
					IdAnexo,
					NomeOriginal
				from
					PessoaAnexo
				where
					IdPessoa = '$local_IdPessoa';";
		$res = @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			$ext = end(explode(".", $lin[NomeOriginal]));
			$url = "../../anexos/pessoa/".$local_IdPessoa."/".$lin[IdAnexo].".".$ext;
		
			$sql0 = "delete from 
						PessoaAnexo 
					where 
						IdPessoa = '$local_IdPessoa' and 
						IdAnexo = '$lin[IdAnexo]';";
			$local_transaction[$tr_i] = mysql_query($sql0, $con);
			
			if($local_transaction[$tr_i] == true){
				@unlink($url);
			}
			
			$tr_i++;
		}
		
		$sql = "delete from LogAcessoCDA where IdPessoa='$local_IdPessoa';";
		$local_transaction[$tr_i] = mysql_query($sql,$con);
		$tr_i++;
		
		$sql = "delete from PessoaEndereco where IdPessoa = $local_IdPessoa;";
		$local_transaction[$tr_i] = mysql_query($sql,$con);
		$tr_i++;

		$sql = "delete from PessoaGrupoPessoa where IdPessoa = $local_IdPessoa;";
		$local_transaction[$tr_i] = mysql_query($sql,$con);
		$tr_i++;
		
		$sql = "delete from Pessoa where IdPessoa = $local_IdPessoa;";
		$local_transaction[$tr_i] = mysql_query($sql,$con);
		$tr_i++;
		
		$URLRotinasExclusao = preg_split("/([\r\n]+)/", getCodigoInterno(56, 3));
		
		foreach($URLRotinasExclusao as $URLRotinaExclusao){
			if(!empty($URLRotinaExclusao)){
				@include($URLRotinaExclusao);
			}
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			@rmdir("../../anexos/pessoa/".$local_IdPessoa);
			$sql = "commit;";
			echo $local_Erro = 7;			
		}else{
			$sql = "rollback;";
			echo $local_Erro = 33;			
		}
		
		mysql_query($sql,$con);
	}
?>