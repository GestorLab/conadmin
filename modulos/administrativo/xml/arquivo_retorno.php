<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Arquivo_Retorno(){
		
		global $con;
		global $_GET;
		
		$Limit 						= $_GET['Limit'];
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdArquivoRetorno 			= $_GET['IdArquivoRetorno'];
		$DataRetorno			 	= $_GET['DataRetorno'];
		$LocalRecebimento			= $_GET['DescricaoLocalRecebimento'];
		$IdLocalRecebimento			= $_GET['IdLocalRecebimento'];
		$NomeArquivo				= $_GET['EndArquivo'];
		$Processado					= $_GET['Processado'];
		$IdStatus					= $_GET['IdStatus'];
		$NomeArquivo				= @end(explode("\\",$NomeArquivo));
		
		$where			= "";
		
		if($DataRetorno != ''){
			$DataRetorno	=	dataConv($DataRetorno,'d/m/Y','Y-m-d');
		}
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdArquivoRetorno != ''){	$where .= " and ArquivoRetorno.IdArquivoRetorno = $IdArquivoRetorno";	}
		if($DataRetorno !=''){			$where .= " and ArquivoRetorno.DataRetorno = '$DataRetorno'";	}
		if($LocalRecebimento !=''){		$where .= " and LocalCobranca.DescricaoLocalCobranca like '$LocalRecebimento%'";	}
		if($IdLocalRecebimento !=''){	$where .= " and ArquivoRetorno.IdLocalCobranca = $IdLocalRecebimento";	}
		if($NomeArquivo !=''){			$where .= " and ArquivoRetorno.NomeArquivo = '$NomeArquivo'";	}
		if($Processado !=''){			$where .= " and ArquivoRetorno.Processado = '$Processado'";	}
		if($IdStatus !=''){				$where .= " and ArquivoRetorno.IdStatus in ($IdStatus)";	}

		$sql = "select
					ArquivoRetorno.IdArquivoRetorno,
					LocalCobranca.DescricaoLocalCobranca DescricaoLocalRecebimento,
					LocalCobranca.IdTipoLocalCobranca,
					ArquivoRetorno.DataCriacao,
					ArquivoRetorno.LoginCriacao,
					ArquivoRetorno.DataProcessamento,
					ArquivoRetorno.LoginProcessamento,
					ArquivoRetorno.ValorTotal,
					ArquivoRetorno.ValorTotalTaxa,
					ArquivoRetorno.ValorLiquido,
					ArquivoRetorno.DataRetorno,
					ArquivoRetorno.QtdRegistro,
					ArquivoRetorno.FileSize,
					ArquivoRetorno.IdLocalCobranca IdLocalRecebimento,
					ArquivoRetorno.EndArquivo,
					ArquivoRetorno.NomeArquivo,
					ArquivoRetorno.IdStatus,
					ArquivoRetorno.NumSeqArquivo,
					ArquivoRetorno.LogRetorno
				from 
					ArquivoRetorno,
					LocalCobranca
				where
					ArquivoRetorno.IdLoja=$IdLoja and
					ArquivoRetorno.IdLocalCobranca = LocalCobranca.IdLocalCobranca
					$where 
					$Limit";
		$res = mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$lin[Status]	= getParametroSistema(195, $lin[IdStatus]);
			$lin[Cor]		= getCodigoInterno(39, $lin[IdStatus]);
			
			$sql2	=	"select
					   	  	ArquivoRetornoTipo.IdArquivoRetornoTipo,
					    	ArquivoRetornoTipo.DescricaoArquivoRetornoTipo
						from 
					     	ArquivoRetornoTipo LEFT JOIN LocalCobranca ON (LocalCobranca.IdArquivoRetornoTipo = ArquivoRetornoTipo.IdArquivoRetornoTipo and LocalCobranca.IdLoja = $IdLoja)
						where
							LocalCobranca.IdLocalCobranca = $lin[IdLocalRecebimento]
						group by
							ArquivoRetornoTipo.IdArquivoRetornoTipo";
			$res2	=	@mysql_query($sql2,$con);
			$lin2	=	@mysql_fetch_array($res2);
			
			$dados	.=	"\n<IdArquivoRetorno>$lin[IdArquivoRetorno]</IdArquivoRetorno>";
			$dados	.=	"\n<DescricaoLocalRecebimento><![CDATA[$lin[DescricaoLocalRecebimento]]]></DescricaoLocalRecebimento>";
			$dados	.=	"\n<IdTipoLocalCobranca><![CDATA[$lin[IdTipoLocalCobranca]]]></IdTipoLocalCobranca>";
			$dados	.=	"\n<ValorTotal><![CDATA[$lin[ValorTotal]]]></ValorTotal>";
			$dados	.=	"\n<ValorTotalTaxa><![CDATA[$lin[ValorTotalTaxa]]]></ValorTotalTaxa>";
			$dados	.=	"\n<ValorLiquido><![CDATA[$lin[ValorLiquido]]]></ValorLiquido>";
			$dados	.=	"\n<DataRetorno><![CDATA[$lin[DataRetorno]]]></DataRetorno>";
			$dados	.=	"\n<QtdRegistro><![CDATA[$lin[QtdRegistro]]]></QtdRegistro>";
			$dados	.=	"\n<FileSize><![CDATA[$lin[FileSize]]]></FileSize>";
			$dados	.=	"\n<IdLocalRecebimento>$lin[IdLocalRecebimento]</IdLocalRecebimento>";
			$dados	.=	"\n<EndArquivo><![CDATA[$lin[EndArquivo]]]></EndArquivo>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<Status><![CDATA[$lin[Status]]]></Status>";
			$dados	.=	"\n<Cor><![CDATA[$lin[Cor]]]></Cor>";
			$dados	.=	"\n<NomeArquivo><![CDATA[$lin[NomeArquivo]]]></NomeArquivo>";
			$dados	.=	"\n<NumSeqArquivo><![CDATA[$lin[NumSeqArquivo]]]></NumSeqArquivo>";
			$dados	.=	"\n<LogRetorno><![CDATA[$lin[LogRetorno]]]></LogRetorno>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataProcessamento><![CDATA[$lin[DataProcessamento]]]></DataProcessamento>";
			$dados	.=	"\n<LoginProcessamento><![CDATA[$lin[LoginProcessamento]]]></LoginProcessamento>";
			$dados	.=	"\n<IdArquivoRetornoTipo><![CDATA[$lin2[IdArquivoRetornoTipo]]]></IdArquivoRetornoTipo>";
			$dados	.=	"\n<DescricaoArquivoRetornoTipo><![CDATA[$lin2[DescricaoArquivoRetornoTipo]]]></DescricaoArquivoRetornoTipo>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Arquivo_Retorno();
?>
