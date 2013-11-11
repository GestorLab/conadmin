<?
	/*if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{*/
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;
		
		$local_ValorDespesaLocalCobranca	=	str_replace(".", "", $local_ValorDespesaLocalCobranca);	
		$local_ValorDespesaLocalCobranca	= 	str_replace(",", ".", $local_ValorDespesaLocalCobranca);
		
		$local_ValorCobrancaMinima		=	str_replace(".", "", $local_ValorCobrancaMinima);	
		$local_ValorCobrancaMinima		= 	str_replace(",", ".", $local_ValorCobrancaMinima);
		
		$local_PercentualJurosDiarios	=	str_replace(".", "", $local_PercentualJurosDiarios);	
		$local_PercentualJurosDiarios	= 	str_replace(",", ".", $local_PercentualJurosDiarios);
		
		$local_PercentualMulta		=	str_replace(".", "", $local_PercentualMulta);	
		$local_PercentualMulta		= 	str_replace(",", ".", $local_PercentualMulta);
		
		$local_ValorTaxaReImpressaoBoleto	=	str_replace(".", "", $local_ValorTaxaReImpressaoBoleto);	
		$local_ValorTaxaReImpressaoBoleto	= 	str_replace(",", ".", $local_ValorTaxaReImpressaoBoleto);
		
		if($local_IdLocalCobrancaLayout == '')				$local_IdLocalCobrancaLayout			=	'NULL';
		if($local_IdDuplicataLayout == '')					$local_IdDuplicataLayout				=	'NULL';
		if($local_IdArquivoRetornoTipo == '')				$local_IdArquivoRetornoTipo				=	'NULL';
		if($local_IdArquivoRemessaTipo == '')				$local_IdArquivoRemessaTipo				=	'NULL';
		if($local_IdLojaCobrancaUnificada == '')			$local_IdLojaCobrancaUnificada			=	'NULL';
		if($local_IdLocalCobrancaUnificada == '')			$local_IdLocalCobrancaUnificada			=	'NULL';
		if($local_IdPessoa == '')							$local_IdPessoa							=	'NULL';
		if($local_IdNotaFiscalTipo == '')					$local_IdNotaFiscalTipo					=	'NULL';	
		if($local_IdContraApresentacao == '')				$local_IdContraApresentacao				=	'NULL';
		if($local_IdCobrarMultaJurosProximaFatura == '')	$local_IdCobrarMultaJurosProximaFatura	=	'NULL';
		if($local_InicioNossoNumero == '')					$local_InicioNossoNumero				= 	0;	
		
		if($local_IdAtualizarVencimentoViaCDA == ''){
			if($local_IdTipoLocalCobranca != '3'){
				$local_IdAtualizarVencimentoViaCDA	=	'NULL';
			} else{
				$local_IdAtualizarVencimentoViaCDA	=	'2';
			}
		}
		
		if($local_IdTipoLocalCobranca < 3 && 4 > $local_IdTipoLocalCobranca){
			$local_IdArquivoRemessaTipo	= 'NULL';
		}
		$arquivos_Suportados	=	array('gif','GIF','jpg','JPG','png','PNG');
		$temp_EndArquivo		=	$_FILES['EndArquivo']['name'];
		$temp_tempEndArquivo	=	$_POST['tempEndArquivo'];
		$temp_ExtArquivo		=	@strtolower(end(explode(".",$temp_tempEndArquivo)));
		$local_NomeArquivo		=	@end(explode("\\",$temp_tempEndArquivo));
		$tempCaminhoArquivos	=	"local_cobranca/personalizacao/$local_IdLoja/$local_IdLocalCobranca".".".$temp_ExtArquivo;
		
		if($temp_EndArquivo !=""){
			if(array_search($temp_ExtArquivo, $arquivos_Suportados) === false){
				$local_Erro = 192;
			}else{
				$sql = "UPDATE LocalCobranca SET 
							IdTipoLocalCobranca					= '$local_IdTipoLocalCobranca',
							IdPessoa							= $local_IdPessoa,
							DescricaoLocalCobranca				= '$local_DescricaoLocalCobranca',
							AbreviacaoNomeLocalCobranca 		= '$local_AbreviacaoNomeLocalCobranca',
							ValorDespesaLocalCobranca			= '$local_ValorDespesaLocalCobranca',
							PercentualJurosDiarios				= '$local_PercentualJurosDiarios',
							ValorCobrancaMinima					= '$local_ValorCobrancaMinima',
							PercentualMulta						= '$local_PercentualMulta',
							DiasCompensacao						= '$local_DiasCompensacao',
							DiasAvisoRegressivo 				= '$local_AvisoRegressivo',
							ExtLogo								= '$temp_ExtArquivo',
							ValorTaxaReImpressaoBoleto			= '$local_ValorTaxaReImpressaoBoleto',
							IdLocalCobrancaLayout				= $local_IdLocalCobrancaLayout,
							IdDuplicataLayout					= $local_IdDuplicataLayout,
							IdArquivoRetornoTipo				= $local_IdArquivoRetornoTipo,
							IdArquivoRemessaTipo				= $local_IdArquivoRemessaTipo,
							IdNotaFiscalTipo					= $local_IdNotaFiscalTipo,
							IdLojaCobrancaUnificada				= $local_IdLojaCobrancaUnificada,
							IdLocalCobrancaUnificada			= $local_IdLocalCobrancaUnificada,
							AvisoFaturaAtraso					= '$local_AvisoFaturaAtraso',
							AtualizarVencimentoViaCDA			= $local_IdAtualizarVencimentoViaCDA,
							RemessaAtualizarContaReceberViaCDA	= $local_IdAtualizarRemessaViaCDA,
							RemessaAtualizarContaReceber		= $local_IdAtualizarRemessaViaContaReceber,
							InicioNossoNumero					= $local_InicioNossoNumero,
							IdStatus							= $local_IdStatus,
							ContraApresentacao					= $local_IdContraApresentacao,
							CobrarMultaJurosProximaFatura		= $local_IdCobrarMultaJurosProximaFatura,
							LoginAlteracao						='$local_Login',
							MsgDemonstrativo					= '$local_MsgDemonstrativo',
							DataAlteracao						= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja						= $local_IdLoja and
							IdLocalCobranca				= '$local_IdLocalCobranca'";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;

				$sql = "DELETE FROM 
							LocalCobrancaParametro 
						WHERE 
							LocalCobrancaParametro.IdLoja = '$local_IdLoja' AND 
							LocalCobrancaParametro.IdLocalCobranca = '$local_IdLocalCobranca' AND 
							LocalCobrancaParametro.IdLocalCobrancaLayout NOT IN (
								SELECT 
									IdLocalCobrancaLayout 
								FROM
									LocalCobranca 
								WHERE 
									IdLoja = '$local_IdLoja' AND 
									IdLocalCobranca = '$local_IdLocalCobranca'
							);";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
				
				if($temp_EndArquivo != ""){	
					@mkdir("local_cobranca/personalizacao/$local_IdLoja");

					$local_transaction[$tr_i]	=	@copy($_FILES['EndArquivo']['tmp_name'],$tempCaminhoArquivos);
					$tr_i++;
				}		

				if($local_OpcaoImagem == 2){
					$sql = "SELECT 
								IdLocalCobranca, 
								ExtLogo 
							FROM 
								LocalCobranca 
							WHERE 
								IdLoja = '$local_IdLoja' and
								IdLocalCobranca = $local_IdLocalCobranca";
					$res = mysql_query($sql,$con);			
					$lin = mysql_fetch_array($res);				
					if(file_exists("local_cobranca/personalizacao/$local_IdLoja/$lin[IdLocalCobranca].$lin[ExtLogo]")){
						@unlink("local_cobranca/personalizacao/$local_IdLoja/$lin[IdLocalCobranca].$lin[ExtLogo]");

						$sql = "UPDATE LocalCobranca SET 
										ExtLogo = ''
								WHERE
									IdLoja = '$local_IdLoja' and
									IdLocalCobranca = $local_IdLocalCobranca";
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);
						$tr_i++;
					}			
				}
				//}
				
				for($i=0; $i<$tr_i; $i++){
					if($local_transaction[$i] == false){
						$local_transaction = false;				
					}
				}
				
				if($local_transaction == true){
					$sql = "COMMIT;";
					$local_Erro = 4;			// Mensagem de Alteração Positiva
				}else{
					$sql = "ROLLBACK;";
					$local_Erro = 5;			// Mensagem de Alteração Negativa
				}
				mysql_query($sql,$con);
			}
		}else{
			$sql = "UPDATE LocalCobranca SET 
						IdTipoLocalCobranca					= '$local_IdTipoLocalCobranca',
						IdPessoa							= $local_IdPessoa,
						DescricaoLocalCobranca				= '$local_DescricaoLocalCobranca',
						AbreviacaoNomeLocalCobranca 		= '$local_AbreviacaoNomeLocalCobranca',
						ValorDespesaLocalCobranca			= '$local_ValorDespesaLocalCobranca',
						PercentualJurosDiarios				= '$local_PercentualJurosDiarios',
						ValorCobrancaMinima					= '$local_ValorCobrancaMinima',
						PercentualMulta						= '$local_PercentualMulta',
						DiasCompensacao						= '$local_DiasCompensacao',
						DiasAvisoRegressivo 				= '$local_AvisoRegressivo',
						ExtLogo								= '$temp_ExtArquivo',
						ValorTaxaReImpressaoBoleto			= '$local_ValorTaxaReImpressaoBoleto',
						IdLocalCobrancaLayout				= $local_IdLocalCobrancaLayout,
						IdDuplicataLayout					= $local_IdDuplicataLayout,
						IdArquivoRetornoTipo				= $local_IdArquivoRetornoTipo,
						IdArquivoRemessaTipo				= $local_IdArquivoRemessaTipo,
						IdNotaFiscalTipo					= $local_IdNotaFiscalTipo,
						IdLojaCobrancaUnificada				= $local_IdLojaCobrancaUnificada,
						IdLocalCobrancaUnificada			= $local_IdLocalCobrancaUnificada,
						AvisoFaturaAtraso					= '$local_AvisoFaturaAtraso',
						AtualizarVencimentoViaCDA			= $local_IdAtualizarVencimentoViaCDA,
						RemessaAtualizarContaReceberViaCDA	= $local_IdAtualizarRemessaViaCDA,
						RemessaAtualizarContaReceber		= $local_IdAtualizarRemessaViaContaReceber,
						InicioNossoNumero					= $local_InicioNossoNumero,
						IdStatus							= $local_IdStatus,
						ContraApresentacao					= $local_IdContraApresentacao,
						CobrarMultaJurosProximaFatura		= $local_IdCobrarMultaJurosProximaFatura,
						LoginAlteracao						='$local_Login',
						MsgDemonstrativo					= '$local_MsgDemonstrativo',
						DataAlteracao						= concat(curdate(),' ',curtime())
					WHERE 
						IdLoja						= $local_IdLoja and
						IdLocalCobranca				= '$local_IdLocalCobranca'";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;

			$sql = "DELETE FROM 
						LocalCobrancaParametro 
					WHERE 
						LocalCobrancaParametro.IdLoja = '$local_IdLoja' AND 
						LocalCobrancaParametro.IdLocalCobranca = '$local_IdLocalCobranca' AND 
						LocalCobrancaParametro.IdLocalCobrancaLayout NOT IN (
							SELECT 
								IdLocalCobrancaLayout 
							FROM
								LocalCobranca 
							WHERE 
								IdLoja = '$local_IdLoja' AND 
								IdLocalCobranca = '$local_IdLocalCobranca'
						);";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			if($temp_EndArquivo != ""){	
				@mkdir("local_cobranca/personalizacao/$local_IdLoja");

				$local_transaction[$tr_i]	=	@copy($_FILES['EndArquivo']['tmp_name'],$tempCaminhoArquivos);
				$tr_i++;
			}		

			if($local_OpcaoImagem == 2){
				$sql = "SELECT 
							IdLocalCobranca, 
							ExtLogo 
						FROM 
							LocalCobranca 
						WHERE 
							IdLoja = '$local_IdLoja' and
							IdLocalCobranca = $local_IdLocalCobranca";
				$res = mysql_query($sql,$con);			
				$lin = mysql_fetch_array($res);				
				if(file_exists("local_cobranca/personalizacao/$local_IdLoja/$lin[IdLocalCobranca].$lin[ExtLogo]")){
					@unlink("local_cobranca/personalizacao/$local_IdLoja/$lin[IdLocalCobranca].$lin[ExtLogo]");

					$sql = "UPDATE LocalCobranca SET 
									ExtLogo = ''
							WHERE
								IdLoja = '$local_IdLoja' and
								IdLocalCobranca = $local_IdLocalCobranca";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}			
			}
			//}
			
			for($i=0; $i<$tr_i; $i++){
				if($local_transaction[$i] == false){
					$local_transaction = false;				
				}
			}
			
			if($local_transaction == true){
				$sql = "COMMIT;";
				$local_Erro = 4;			// Mensagem de Alteração Positiva
			}else{
				$sql = "ROLLBACK;";
				$local_Erro = 5;			// Mensagem de Alteração Negativa
			}
			mysql_query($sql,$con);
		}
?>