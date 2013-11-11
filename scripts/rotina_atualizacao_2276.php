<?php 
	include("../files/conecta.php");
	include("../files/funcoes.php");
	
	$sql = "SELECT 
				IdPessoa,
				IdPessoaSolicitacao
			FROM 
				PessoaSolicitacao 
			WHERE 
				IdLogAcesso IS NULL";
	$res = @mysql_query($sql, $con);
	
	while($lin = @mysql_fetch_array($res)) {
		$sql_tmp = "SELECT 
						MAX(IdLogAcesso) IdLogAcesso
					FROM 
						LogAcessoCDA 
					WHERE 
						IdPessoa = '".$lin["IdPessoa"]."'";
		$res_tmp = @mysql_query($sql_tmp, $con);
		$lin_tmp = @mysql_fetch_array($res_tmp);
		
		$sql_tmp = "UPDATE 
						PessoaSolicitacao 
					SET
						IdLogAcesso = '".$lin_tmp["IdLogAcesso"]."'
					WHERE
						IdPessoa = '".$lin["IdPessoa"]."' AND
						IdPessoaSolicitacao = '".$lin["IdPessoaSolicitacao"]."';";
		@mysql_query($sql_tmp, $con);
	}
?>