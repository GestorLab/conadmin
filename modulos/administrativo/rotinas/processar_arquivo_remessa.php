<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"P") == false){
		$local_Erro = 2;
	} else{
		
		$sql	= "START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i	= 0;

		$sql1 = "select 
					ArquivoRemessa.IdLoja,
					ArquivoRemessa.IdArquivoRemessa,
					ArquivoRemessa.IdLocalCobranca,
					ArquivoRemessa.IdStatus
				from
					Loja,
					ArquivoRemessa,
					LocalCobranca 
				where 
					ArquivoRemessa.IdLoja = $local_IdLoja and
					ArquivoRemessa.IdLoja = Loja.IdLoja and
					ArquivoRemessa.IdLoja = LocalCobranca.IdLoja and
					ArquivoRemessa.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
					ArquivoRemessa.IdStatus = 3 and
					ArquivoRemessa.IdLocalCobranca = $local_IdLocalCobranca";
		$res1 = mysql_query($sql1,$con);
		if(mysql_num_rows($res1) == 0){

			include("rotinas/processar_arquivo_remessa_prioridade.php"); # Verifica e trata duplicidade de solicitação de remessa
			include("rotinas/processar_arquivo_remessa_etapa1.php"); # Define quais serão os conta a receber quitados que entrarão na remessa
			include("rotinas/processar_arquivo_remessa_etapa2.php"); # Define quais serão os conta a receber aguardando envio que entrarão na remessa

			$sql	= "select
							count(*) Qtd
					   from
							ArquivoRemessa						
					   where 
							IdLoja = '$local_IdLoja' and 
							IdLocalCobranca = '$local_IdLocalCobranca' and 
							IdStatus = 2";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);
			
			if($lin[Qtd] == 0){
				$sql2	= "select				
								distinct
								ContaReceber.IdContaReceber,
								count(*) Qtd,
								sum(ValorLancamento) ValorTotal
							from
								ContaReceber,
								ContaReceberPosicaoCobranca,
								LocalCobranca
							where
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

								ContaReceber.IdLoja = ContaReceberPosicaoCobranca.IdLoja and	
								ContaReceber.IdLoja = LocalCobranca.IdLoja and
								ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
								ContaReceber.IdArquivoRemessa = $local_IdArquivoRemessa and
								ContaReceber.IdContaReceber = ContaReceberPosicaoCobranca.IdContaReceber and
								ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00'";
				$res2 = mysql_query($sql2,$con);
				$lin2 = mysql_fetch_array($res2);
				if($lin2[Qtd] > 0){
					$LogRemessa = date("d/m/Y H:i:s")." [$local_Login] - $lin2[Qtd] conta(s) à receber processado(s).";

					$sql = "update ArquivoRemessa set 
								ValorTotal = '$lin2[ValorTotal]',
								DataRemessa = curdate(),
								QtdRegistro = '$lin2[Qtd]',
								IdStatus='2',
								LogRemessa = concat('$LogRemessa','\n',LogRemessa),
								LoginProcessamento = '$local_Login',
								DataProcessamento = concat(curdate(),' ',curtime())
							where 
								IdLoja = '$local_IdLoja' and 
								IdLocalCobranca = '$local_IdLocalCobranca' and 
								IdArquivoRemessa = '$local_IdArquivoRemessa'";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;

					for($i=0; $i<$tr_i; $i++){
						if($local_transaction[$i] == false){
							$local_transaction = false;
						}
					}
				
					if($local_transaction == true){
						$sql = "COMMIT;";
						$local_Erro = 51;
					}else{
						$sql = "ROLLBACK;";
						$local_Erro = 50;
					}						
					mysql_query($sql,$con);	
				}else{
					$local_Erro = 180;//Processo não executado! Este local de cobrança não possui remessas a serem geradas. 
				}
			}else{
				 // Há arquivos de remessa  a ser concluido neste local de cobrança.
				$local_Erro = 141;
			}
		}else{
			 // Verificar confimação de entrega para este local de cobrança.
			$local_Erro = 173;
		}
	}	
?>