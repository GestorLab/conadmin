<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inserção de Processo Financeiro
		$sql	=	"select (max(IdProcessoFinanceiro)+1) IdProcessoFinanceiro from ProcessoFinanceiro where IdLoja=$local_IdLoja";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdProcessoFinanceiro]!=NULL){
			$local_IdProcessoFinanceiro	=	$lin[IdProcessoFinanceiro];
		}else{
			$local_IdProcessoFinanceiro	=	1;
		}
		
		if($local_MenorVencimento == ''){				$local_MenorVencimento				=	'NULL';	}
		if($local_Filtro_IdLocalCobranca == ''){		$local_Filtro_IdLocalCobranca		=	'NULL';	}
		if($local_Filtro_FormaAvisoCobranca == ''){		$local_Filtro_FormaAvisoCobranca	=	'NULL';	}
		if($local_Filtro_TipoLancamento == ''){			$local_Filtro_TipoLancamento		=	'NULL';	}
		if($local_Filtro_IdTipoPessoa == ''){			$local_Filtro_IdTipoPessoa			=	'NULL';	}
		if($local_Filtro_TipoCobranca == ''){			$local_Filtro_TipoCobranca			=	'NULL';	}				
		if($local_Filtro_TipoContrato == ''){			$local_Filtro_TipoContrato			=	'NULL';	}				

		if($local_DataNotaFiscal == ''){				
			$local_DataNotaFiscal		=	'NULL';	
		}else{				
			$local_DataNotaFiscal		= 	"'".dataConv($local_DataNotaFiscal,'d/m/Y','Y-m-d')."'"; 
		}
		
	 	$sql	=	"INSERT INTO ProcessoFinanceiro SET 
						IdLoja							= $local_IdLoja, 
						IdProcessoFinanceiro			= $local_IdProcessoFinanceiro, 
						MesVencimento					= '$local_MesVencimento', 
						MenorVencimento					= $local_MenorVencimento, 
						MesReferencia					= '$local_MesReferencia', 
						DataNotaFiscal					= $local_DataNotaFiscal,
						IdStatus						= ".getCodigoInterno(3,13).", 
						Filtro_IdPessoa					= '$local_Filtro_IdPessoa',
						Filtro_IdLocalCobranca			= $local_Filtro_IdLocalCobranca,
						Filtro_TipoLancamento			= $local_Filtro_TipoLancamento,
						Filtro_TipoPessoa				= $local_Filtro_IdTipoPessoa,
						Filtro_IdPaisEstadoCidade		= '$local_Filtro_IdPaisEstadoCidade',
						Filtro_IdContrato				= '$local_Filtro_IdContrato',
						Filtro_IdStatusContrato			= '$local_Filtro_IdStatusContrato',
						Filtro_FormaAvisoCobranca		= $local_Filtro_FormaAvisoCobranca,
						Filtro_IdServico				= '$local_Filtro_IdServico',
						Filtro_IdAgenteAutorizado		= '$local_Filtro_IdAgenteAutorizado',
						Filtro_TipoCobranca				= $local_Filtro_TipoCobranca,
						Filtro_IdTipoContrato			= $local_Filtro_TipoContrato,
						Filtro_IdGrupoPessoa			= '$local_Filtro_IdGrupoPessoa',
						Filtro_DiaCobranca				= '$local_Filtro_VencimentoContrato',						
						LogProcessamento				= '$local_LogProcessamento',
						LoginCriacao					='$local_Login', 
						DataCriacao						=(concat(curdate(),' ',curtime()));";
					
			// Executa a Sql de Inserção de Processo Financeiro
		if(mysql_query($sql,$con) == true){						
			$local_Acao = 'alterar';	// Desabilita o inserir e Habilita alterar e excluir
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		}else{
			// Muda a ação para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 1;			// Mensagem de Inserção Negativa
		}
	}
?>
