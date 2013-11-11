<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"I")){
		$local_Erro = 2;
	} else{
		$local_PeriodoApuracao = dataConv($local_PeriodoApuracao, "m/Y", "Y-m");
		$sql = "INSERT INTO SICI SET
					PeriodoApuracao		= '$local_PeriodoApuracao',
					IdNotaFiscalLayout	= '$local_ModeloNF',
					IdStatus			= '1',
					LoginCriacao		= '$local_Login',
					DataCriacao			= (concat(curdate(),' ',curtime()));";
		
		if(@mysql_query($sql,$con)){
			$local_Acao = "alterar";
			$local_Erro = 3;
		} else{
			$local_Acao = "inserir";
			$local_Erro = 8;
		}
	}
?>