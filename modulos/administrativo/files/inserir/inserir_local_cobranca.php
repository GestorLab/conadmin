<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"I")){
		$local_Erro = 2;
	}else{
		$sql	=	"select (max(IdLocalCobranca)+1) IdLocalCobranca from LocalCobranca where IdLoja = $local_IdLoja";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdLocalCobranca]!=NULL){
			$local_IdLocalCobranca	=	$lin[IdLocalCobranca];
		}else{
			$local_IdLocalCobranca	=	1;
		}
		
		$local_ValorDespesaLocalCobranca	=	str_replace(".", "", $local_ValorDespesaLocalCobranca);	
		$local_ValorDespesaLocalCobranca	= 	str_replace(",", ".", $local_ValorDespesaLocalCobranca);
		
		$local_ValorCobrancaMinima			=	str_replace(".", "", $local_ValorCobrancaMinima);	
		$local_ValorCobrancaMinima			= 	str_replace(",", ".", $local_ValorCobrancaMinima);
		
		$local_PercentualJurosDiarios		=	str_replace(".", "", $local_PercentualJurosDiarios);	
		$local_PercentualJurosDiarios		= 	str_replace(",", ".", $local_PercentualJurosDiarios);
		
		$local_PercentualMulta				=	str_replace(".", "", $local_PercentualMulta);	
		$local_PercentualMulta				= 	str_replace(",", ".", $local_PercentualMulta);
		
		$local_ValorTaxaReImpressaoBoleto	=	str_replace(".", "", $local_ValorTaxaReImpressaoBoleto);	
		$local_ValorTaxaReImpressaoBoleto	= 	str_replace(",", ".", $local_ValorTaxaReImpressaoBoleto);
		
		
		if($local_IdLocalCobrancaLayout == '')				$local_IdLocalCobrancaLayout			=	'NULL';
		if($local_IdArquivoRetornoTipo == '')				$local_IdArquivoRetornoTipo				=	'NULL';
		if($local_IdArquivoRemessaTipo == '')				$local_IdArquivoRemessaTipo				=	'NULL';
		if($local_IdLojaCobrancaUnificada == '')			$local_IdLojaCobrancaUnificada			=	'NULL';
		if($local_IdLocalCobrancaUnificada == '')			$local_IdLocalCobrancaUnificada			=	'NULL';
		if($local_IdPessoa == '')							$local_IdPessoa							=	'NULL';
		if($local_IdNotaFiscalTipo == '')					$local_IdNotaFiscalTipo					=	'NULL';
		if($local_IdContraApresentacao == '')				$local_IdContraApresentacao				=	'NULL';
		if($local_IdCobrarMultaJurosProximaFatura == '')	$local_IdCobrarMultaJurosProximaFatura	=	'NULL';
		if($local_IdDuplicataLayout  == '')					$local_IdDuplicataLayout 				=	'NULL';
		if($local_AvisoFaturaAtraso == '')					$local_AvisoFaturaAtraso				= 	1;
		if($local_InicioNossoNumero == '')					$local_InicioNossoNumero				= 	0;
		
		if($local_IdAtualizarVencimentoViaCDA == ''){
			if($local_IdTipoLocalCobranca != '3'){
				$local_IdAtualizarVencimentoViaCDA	=	'NULL';
			} else{
				$local_IdAtualizarVencimentoViaCDA	=	'2';
			}
		}
		$arquivos_Suportados	=	array('gif','GIF','jpg','JPG','png','PNG');
		$temp_EndArquivo		= $_FILES['EndArquivo']['name'];
		$temp_tempEndArquivo	= $_POST['tempEndArquivo'];
		$temp_ExtArquivo		= @strtolower(end(explode(".",$temp_tempEndArquivo)));
		$local_NomeArquivo		= @end(explode("\\",$temp_tempEndArquivo));
		$tempNomeArquivos		= "$local_IdLocalCobranca.".$temp_ExtArquivo;
		$tempCaminhoArquivos	= "local_cobranca/personalizacao/$local_IdLoja";
		
		if($temp_EndArquivo != ""){
			if(array_search($temp_ExtArquivo, $arquivos_Suportados) === false){
				$local_Erro = 192;
			}else{
				$sql=	"INSERT INTO LocalCobranca SET 
							IdLoja								= $local_IdLoja,
							IdPessoa							= $local_IdPessoa,
							IdLocalCobranca						= $local_IdLocalCobranca, 
							IdTipoLocalCobranca					= $local_IdTipoLocalCobranca,
							DescricaoLocalCobranca				= '$local_DescricaoLocalCobranca',
							AbreviacaoNomeLocalCobranca 		= '$local_AbreviacaoNomeLocalCobranca',
							ValorDespesaLocalCobranca			= '$local_ValorDespesaLocalCobranca',
							PercentualJurosDiarios				= '$local_PercentualJurosDiarios',
							ValorTaxaReImpressaoBoleto			= '$local_ValorTaxaReImpressaoBoleto',
							ValorCobrancaMinima					= '$local_ValorCobrancaMinima',
							PercentualMulta						= '$local_PercentualMulta',
							DiasCompensacao						= '$local_DiasCompensacao',
							DiasAvisoRegressivo 				= '$local_AvisoRegressivo',
							ExtLogo								= '$temp_ExtArquivo',  	
							IdLocalCobrancaLayout				= $local_IdLocalCobrancaLayout,
							IdDuplicataLayout					= $local_IdDuplicataLayout,
							IdArquivoRetornoTipo				= $local_IdArquivoRetornoTipo,
							IdArquivoRemessaTipo				= $local_IdArquivoRemessaTipo,
							IdNotaFiscalTipo					= $local_IdNotaFiscalTipo,
							AtualizarVencimentoViaCDA			= $local_IdAtualizarVencimentoViaCDA, 
							RemessaAtualizarContaReceberViaCDA	= $local_IdAtualizarRemessaViaCDA,
							RemessaAtualizarContaReceber		= $local_IdAtualizarRemessaViaContaReceber,
							IdLojaCobrancaUnificada				= $local_IdLojaCobrancaUnificada,
							IdLocalCobrancaUnificada			= $local_IdLocalCobrancaUnificada,
							AvisoFaturaAtraso					= '$local_AvisoFaturaAtraso',
							InicioNossoNumero					= $local_InicioNossoNumero,
							ContraApresentacao					= $local_IdContraApresentacao,
							CobrarMultaJurosProximaFatura		= $local_IdCobrarMultaJurosProximaFatura,
							IdStatus							= $local_IdStatus,
							DataCriacao							= (concat(curdate(),' ',curtime())),
							MsgDemonstrativo					= '$local_MsgDemonstrativo',
							LoginCriacao						= '$local_Login';";
				if(mysql_query($sql,$con) == true){
					$local_Acao = 'alterar';
					$local_Erro = 3;		// Mensagem de Alteraчуo Positiva
					
					if($temp_EndArquivo != ""){
						@mkdir($tempCaminhoArquivos, 0770);
						
						if(!@copy($_FILES['EndArquivo']['tmp_name'],$tempCaminhoArquivos."/".$tempNomeArquivos)){
							$local_Erro = 9;	
						}	
					}
				}else{
					$local_Acao = 'inserir';
					$local_Erro = 8;		// Mensagem de Alteraчуo Negativa
				}
			}
		}else{
			$sql=	"INSERT INTO LocalCobranca SET 
						IdLoja								= $local_IdLoja,
						IdPessoa							= $local_IdPessoa,
						IdLocalCobranca						= $local_IdLocalCobranca, 
						IdTipoLocalCobranca					= $local_IdTipoLocalCobranca,
						DescricaoLocalCobranca				= '$local_DescricaoLocalCobranca',
						AbreviacaoNomeLocalCobranca 		= '$local_AbreviacaoNomeLocalCobranca',
						ValorDespesaLocalCobranca			= '$local_ValorDespesaLocalCobranca',
						PercentualJurosDiarios				= '$local_PercentualJurosDiarios',
						ValorTaxaReImpressaoBoleto			= '$local_ValorTaxaReImpressaoBoleto',
						ValorCobrancaMinima					= '$local_ValorCobrancaMinima',
						PercentualMulta						= '$local_PercentualMulta',
						DiasCompensacao						= '$local_DiasCompensacao',
						DiasAvisoRegressivo 				= '$local_AvisoRegressivo',
						ExtLogo								= '$temp_ExtArquivo',  	
						IdLocalCobrancaLayout				= $local_IdLocalCobrancaLayout,
						IdDuplicataLayout					= $local_IdDuplicataLayout,
						IdArquivoRetornoTipo				= $local_IdArquivoRetornoTipo,
						IdArquivoRemessaTipo				= $local_IdArquivoRemessaTipo,
						IdNotaFiscalTipo					= $local_IdNotaFiscalTipo,
						AtualizarVencimentoViaCDA			= $local_IdAtualizarVencimentoViaCDA, 
						RemessaAtualizarContaReceberViaCDA	= $local_IdAtualizarRemessaViaCDA,
						RemessaAtualizarContaReceber		= $local_IdAtualizarRemessaViaContaReceber,
						IdLojaCobrancaUnificada				= $local_IdLojaCobrancaUnificada,
						IdLocalCobrancaUnificada			= $local_IdLocalCobrancaUnificada,
						AvisoFaturaAtraso					= '$local_AvisoFaturaAtraso',
						InicioNossoNumero					= $local_InicioNossoNumero,
						ContraApresentacao					= $local_IdContraApresentacao,
						CobrarMultaJurosProximaFatura		= $local_IdCobrarMultaJurosProximaFatura,
						IdStatus							= $local_IdStatus,
						DataCriacao							= (concat(curdate(),' ',curtime())),
						MsgDemonstrativo					= '$local_MsgDemonstrativo',
						LoginCriacao						= '$local_Login';";
			if(mysql_query($sql,$con) == true){
				$local_Acao = 'alterar';
				$local_Erro = 3;		// Mensagem de Alteraчуo Positiva
				
				if($temp_EndArquivo != ""){
					@mkdir($tempCaminhoArquivos, 0770);
					
					if(!@copy($_FILES['EndArquivo']['tmp_name'],$tempCaminhoArquivos."/".$tempNomeArquivos)){
						$local_Erro = 9;	
					}	
				}
			}else{
				$local_Acao = 'inserir';
				$local_Erro = 8;		// Mensagem de Alteraчуo Negativa
			}
		}
	}
?>