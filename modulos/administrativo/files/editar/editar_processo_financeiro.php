<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		if($local_MenorVencimento == ''){				$local_MenorVencimento				=	'NULL';	}
		if($local_Filtro_IdLocalCobranca == ''){		$local_Filtro_IdLocalCobranca		=	'NULL';	}
		if($local_Filtro_FormaAvisoCobranca == ''){		$local_Filtro_FormaAvisoCobranca	=	'NULL';	}
		if($local_Filtro_TipoLancamento == ''){			$local_Filtro_TipoLancamento		=	'NULL';	}
		if($local_Filtro_IdTipoPessoa == ''){			$local_Filtro_IdTipoPessoa			=	'NULL';	}
		
		if($local_DataNotaFiscal == ''){				
			$local_DataNotaFiscal		=	'NULL';	
		}else{
			$local_DataNotaFiscal		= 	"'".dataConv($local_DataNotaFiscal,'d/m/Y','Y-m-d')."'"; 	
		}
				
		if($local_IdStatus == 1){
			$sql	=	"UPDATE ProcessoFinanceiro SET
							MesVencimento					= '$local_MesVencimento', 
							MenorVencimento					= $local_MenorVencimento, 
							MesReferencia					= '$local_MesReferencia',
							DataNotaFiscal					= $local_DataNotaFiscal, 
							LoginAlteracao					= '$local_Login',
							Filtro_IdPessoa					= '$local_Filtro_IdPessoa',
							Filtro_IdLocalCobranca			= $local_Filtro_IdLocalCobranca,
							Filtro_TipoLancamento			= $local_Filtro_TipoLancamento,
							Filtro_TipoPessoa				= $local_Filtro_IdTipoPessoa,
							Filtro_IdPaisEstadoCidade		= '$local_Filtro_IdPaisEstadoCidade',
							Filtro_IdTipoContrato			= '$local_Filtro_TipoContrato',
							Filtro_IdContrato				= '$local_Filtro_IdContrato',
							Filtro_IdStatusContrato			= '$local_Filtro_IdStatusContrato',
							Filtro_FormaAvisoCobranca		= $local_Filtro_FormaAvisoCobranca,
							Filtro_IdServico				= '$local_Filtro_IdServico',
							Filtro_IdAgenteAutorizado		= '$local_Filtro_IdAgenteAutorizado',
							Filtro_TipoCobranca				= $local_Filtro_TipoCobranca,
							Filtro_IdGrupoPessoa			= '$local_Filtro_IdGrupoPessoa',
							Filtro_DiaCobranca				= '$local_Filtro_VencimentoContrato',
							IdStatus						= '$local_IdStatus',
							DataAlteracao					= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja							= '$local_IdLoja' and
							IdProcessoFinanceiro			= '$local_IdProcessoFinanceiro'";
			if(mysql_query($sql,$con) == true){
				$local_Erro = 4;
			}else{
				$local_Erro = 5;
			}
		}else{
			$local_Erro = 46;
		}
	}
?>
