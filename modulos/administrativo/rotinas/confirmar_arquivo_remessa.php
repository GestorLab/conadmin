<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false){
		$local_Erro = 2;
	}else{
		$sql = "SELECT 
					DISTINCT
					ContaReceber.IdLoja,
					ContaReceber.IdContaReceber
				FROM
					ContaReceber,
					ContaReceberPosicaoCobranca,
					LocalCobranca
				WHERE
					(
						(
							LocalCobranca.IdLoja = $local_IdLoja and
							LocalCobranca.IdLocalCobranca = $local_IdLocalCobranca
						) or
						(
							LocalCobranca.IdLojaCobrancaUnificada = $local_IdLoja and
							LocalCobranca.IdLocalCobrancaUnificada = $local_IdLocalCobranca
						)
					) and

					ContaReceber.IdLoja = LocalCobranca.IdLoja AND
					ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca AND 

					ContaReceber.IdLoja = ContaReceberPosicaoCobranca.IdLoja AND 
					ContaReceber.IdContaReceber = ContaReceberPosicaoCobranca.IdContaReceber AND 

				/*	ContaReceber.IdLocalCobranca = ContaReceberPosicaoCobranca.IdLocalCobrancaRemessa AND */
					
					ContaReceber.IdArquivoRemessa = $local_IdArquivoRemessa AND
					((ContaReceber.DataVencimento <= CURDATE() AND LocalCobranca.IdTipoLocalCobranca != 6) OR (LocalCobranca.IdTipoLocalCobranca = 6 and ContaReceber.DataVencimento > CURDATE())) AND
					ContaReceberPosicaoCobranca.IdArquivoRemessa IS NULL AND
					ContaReceberPosicaoCobranca.IdPosicaoCobranca IN (1,6,9)";
		$res = mysql_query($sql,$con);
		if(mysql_num_rows($res) == 0){
			$sql	=	"START TRANSACTION;";
			mysql_query($sql,$con);
			$tr_i = 0;

			$sql = "select
						IdLocalCobrancaParametro,
						ValorLocalCobrancaParametro
					from
						LocalCobrancaParametro
					where
						IdLoja = $local_IdLoja and
						IdLocalCobranca = $local_IdLocalCobranca";
			$res = mysql_query($sql,$con);
			while($lin = mysql_fetch_array($res)){
				$LocalCobrancaParametro[$lin[IdLocalCobrancaParametro]] = $lin[ValorLocalCobrancaParametro];
			}

			$sql = "select
						NumSeqArquivo,
						DataProcessamento
					from
						ArquivoRemessa
					where
						IdLoja = $local_IdLoja and
						IdLocalCobranca = $local_IdLocalCobranca";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);
			
			$DataProcessamento = $lin[DataProcessamento];
			
			$sql = "select
						max(NumSeqArquivo) NumSeqArquivo				
					from
						ArquivoRemessa
					where
						IdLoja = $local_IdLoja and
						IdLocalCobranca = $local_IdLocalCobranca";
			$res2 = mysql_query($sql,$con);
			$lin2 = mysql_fetch_array($res2);
			
			if($lin2[NumSeqArquivo] == ""){
				$lin2[NumSeqArquivo] = 0;
			}	
			$NumSeqArquivo = $lin2[NumSeqArquivo]+1;	
			if($NumSeqArquivo < $LocalCobrancaParametro[NumeroArquivoRemessaInicial]){
				$NumSeqArquivo = $LocalCobrancaParametro[NumeroArquivoRemessaInicial];
			}
					
			$sql = "select
						IdPessoa
					from
						LocalCobranca
					where
						IdLoja = $local_IdLoja and
						IdLocalCobranca = $local_IdLocalCobranca";
			$res = mysql_query($sql,$con);
			$DadosEmpresa = mysql_fetch_array($res);

			if($DadosEmpresa[IdPessoa] == ''){
				$sql = "select
							IdPessoa
						from
							Loja
						where
							IdLoja = $local_IdLoja";
				$res = mysql_query($sql,$con);
				$DadosEmpresa = mysql_fetch_array($res);
			}
					
			$sql = "select
						Pessoa.Nome,
						Pessoa.RazaoSocial,
						Pessoa.TipoPessoa,
						Pessoa.CPF_CNPJ
					from
						Pessoa
					where
						Pessoa.IdPessoa = $DadosEmpresa[IdPessoa]";
			$res = mysql_query($sql,$con);
			$DadosEmpresa = mysql_fetch_array($res);		

			$sql = "select
						IdPessoa,
						PercentualMulta,
						PercentualJurosDiarios
					from
						LocalCobranca
					where
						IdLoja = $local_IdLoja and
						IdLocalCobranca = $local_IdLocalCobranca";
			$res = mysql_query($sql,$con);
			$DadosLocalCobranca = mysql_fetch_array($res);
				
			$sql = "select
						IdLocalCobrancaParametro,
						ValorLocalCobrancaParametro
					from
						LocalCobrancaParametro
					where
						IdLoja = $local_IdLoja and
						IdLocalCobranca = $local_IdLocalCobranca";
			$res = mysql_query($sql,$con);
			while($lin = mysql_fetch_array($res)){
				$ParametroLocalCobranca[$lin[IdLocalCobrancaParametro]] = $lin[ValorLocalCobrancaParametro];
			}

			include("layout_remessa/$local_IdArquivoRemessaTipo/remessa.php");

			$String = '';
			
			for($i=0; $i<count($Linha); $i++){
				if(strlen($Linha[$i]) == 400 || strlen($Linha[$i]) == 240 || strlen($Linha[$i]) == 17 || strlen($Linha[$i]) == 150 || strlen($Linha[$i]) == 250){
					if($local_IdArquivoRemessaTipo != 13){
						$String .= strtoupper(substituir_string($Linha[$i]))."\r\n";
					}else{
						$String .= strtoupper($Linha[$i])."\r\n";
					}
				}
			}

			$FileName = $Patch;

			@unlink($FileName);

			if($File = fopen($FileName, 'a')) {
				if(fwrite($File, $String)){
					
					$FileSize = filesize($FileName)/1024;
					$FileSize = number_format($FileSize, 2, '.', '');

				}
			}

			fclose($File);

			$LogRemessa = date("d/m/Y H:i:s")." [$local_Login] - Arquivo de Remessa Disponível para Download.";

			$sqlUpdate = "SELECT 
							LocalCobranca.IdLoja,
							LocalCobranca.IdLocalCobranca
						FROM
							LocalCobranca
						WHERE
							(
								LocalCobranca.IdLoja = $local_IdLoja and
								LocalCobranca.IdLocalCobranca = $local_IdLocalCobranca
							) or
							(
								LocalCobranca.IdLojaCobrancaUnificada = $local_IdLoja and
								LocalCobranca.IdLocalCobrancaUnificada = $local_IdLocalCobranca
							)";
			$resUpdate = mysql_query($sqlUpdate,$con);
			while($linUpdate = mysql_fetch_array($resUpdate)){
				$sql = "update ContaReceber set 
								IdStatus = 1,
								IdPosicaoCobranca = NULL
							where
								IdLoja = $linUpdate[IdLoja] and
								IdLocalCobranca = $linUpdate[IdLocalCobranca] and
								IdStatus = 3 and
								IdArquivoRemessa = $local_IdArquivoRemessa;";
				$local_transaction[$tr_i]	= mysql_query($sql,$con);
				$tr_i++;
			}
			
			$sql = "update ArquivoRemessa set 
						EndArquivo='$Patch', 
						FileSize='$FileSize', 
						NomeArquivo='$NomeArquivo', 
						NumSeqArquivo=$NumSeqArquivo, 
						LogRemessa=concat('$LogRemessa','\n',LogRemessa), 
						IdStatus = 3 ,
						LoginConfirmacao = '$local_Login',
						DataConfirmacao = concat(curdate(),' ',curtime())
					where 
						IdLoja='$local_IdLoja' and 
						IdLocalCobranca = $local_IdLocalCobranca and 
						IdArquivoRemessa=$local_IdArquivoRemessa";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;

			// Os que tem que ser enviados ainda...
			$sql = "SELECT
						ContaReceber.IdLoja,
						ContaReceber.IdContaReceber
					FROM
						ContaReceber,
						ContaReceberPosicaoCobranca,
						LocalCobranca
					WHERE					
						(
							(
								LocalCobranca.IdLoja = $local_IdLoja and
								LocalCobranca.IdLocalCobranca = $local_IdLocalCobranca
							) or
							(
								LocalCobranca.IdLojaCobrancaUnificada = $local_IdLoja and
								LocalCobranca.IdLocalCobrancaUnificada = $local_IdLocalCobranca
							)
						) and
						ContaReceber.IdLoja = LocalCobranca.IdLoja AND
						ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca AND
						ContaReceber.IdArquivoRemessa = $local_IdArquivoRemessa AND
						ContaReceber.IdLoja = ContaReceberPosicaoCobranca.IdLoja AND
						ContaReceber.IdContaReceber = ContaReceberPosicaoCobranca.IdContaReceber AND
						ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00'";
			$res = mysql_query($sql,$con);
			while($lin = mysql_fetch_array($res)){
				$sql = "update ContaReceber set
							IdStatus = 3
						where 
							IdLoja = $lin[IdLoja] and 
							IdContaReceber = $lin[IdContaReceber]";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
			}
			
			for($i=0; $i<$tr_i; $i++){
				if($local_transaction[$i] == false){
					$local_transaction = false;
				}
			}
			
			if($local_transaction == true){
				$sql = "COMMIT;";
				$local_Erro = 47;
			}else{
				$sql = "ROLLBACK;";
				$local_Erro = 50;
			}
			//$sql = "ROLLBACK;";
			mysql_query($sql,$con);
		}else{			
			// Há contas a receber vencido
			$local_Erro = 138;
		}
	}
?>
