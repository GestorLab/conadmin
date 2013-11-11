<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	} else{
		$validade = $local_MesValidade."/".$local_AnoValidade;
		
		if($local_IdBandeiraCartao == ""){
			$local_IdBandeiraCartao = $local_IdBandeiraCartaoTemp;
		}
		if(($local_MesValidade == "" && $local_AnoValidade == "") || ($local_MesValidade == "" || $local_AnoValidade == "")){
			$validade = $local_MesValidadeTemp."/".$local_AnoValidadeTemp;
		}
		
		$sql = "UPDATE PessoaCartao SET 
					IdBandeira				='$local_IdBandeiraCartao',
					NomeTitular				='$local_NomeTitular',
					NumeroCartao			='$local_NumeroCartao',
					Validade				='$validade',
					CodigoSeguranca			='$local_CodigoSeguranca',
					DiaVencimentoFatura		='$local_DiaVencimentoFatura',
					IdStatus				='$local_IdStatus',
					LoginAlteracao			='$local_Login', 
					DataAlteracao			=(concat(curdate(),' ',curtime()))
				WHERE
					IdLoja		= $local_IdLoja and
					IdPessoa 	= $local_IdPessoa and
					IdCartao	= $local_IdCartao";
		if(mysql_query($sql, $con) == true){
			$local_Erro = 4;			
		} else{
			$local_Erro = 5;			
		}
	}
?>