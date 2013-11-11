<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	
	function get_LocalCobranca(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$local_IdLoja			= $_GET["IdLoja"];
		$IdLocalCobranca	 	= $_GET['IdLocalCobranca'];
		$Nome					= $_GET['Nome'];
		$Abreviacao				= $_GET['Abreviacao'];
		$IdTipoExc			  	= $_GET['IdTipoExc'];
		$IdTipo				  	= $_GET['IdTipo'];
		$IdStatus				= $_GET['IdStatus'];
		$where			= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($Abreviacao !=''){	
			$where .= " and AbreviacaoNomeLocalCobranca like '$Abreviacao%'";	
		}
		
		if($IdLocalCobranca != ''){	
			$where .= " and LocalCobranca.IdLocalCobranca=$IdLocalCobranca";	
		}
		
		if($Nome !=''){	
			$where .= " and LocalCobranca.DescricaoLocalCobranca like '$Nome%'";	
		}
		
		if($IdTipoExc != ""){
			$where .= " and LocalCobranca.IdTipoLocalCobranca != $IdTipoExc";
		}
		
		if($IdTipo != ""){
			$where .= " and LocalCobranca.IdTipoLocalCobranca = $IdTipo";
		}
		
		if($IdStatus != ""){
			$where .= " and LocalCobranca.IdStatus = $IdStatus";
		}
		
		$sql	=	"select
						LocalCobranca.IdLoja,
						LocalCobranca.IdLocalCobranca, 
						LocalCobranca.IdTipoLocalCobranca,
						LocalCobranca.IdLojaCobrancaUnificada,
						LocalCobranca.IdLocalCobrancaUnificada,	
						LocalCobranca.IdPessoa,	
						LocalCobranca.IdNotaFiscalTipo,
						LocalCobranca.DiasCompensacao,
						LocalCobranca.DiasAvisoRegressivo,
						LocalCobranca.AvisoFaturaAtraso,
						LocalCobranca.InicioNossoNumero,
						Pessoa.TipoPessoa,
						Pessoa.Nome,
						Pessoa.RazaoSocial,
						Pessoa.CPF_CNPJ,
						Pessoa.Email,
						DescricaoLocalCobranca, 
						AbreviacaoNomeLocalCobranca,
						LocalCobranca.IdLocalCobrancaLayout,
						LocalCobrancaLayout.DescricaoLocalCobrancaLayout,
						ValorDespesaLocalCobranca,
						LocalCobranca.IdArquivoRetornoTipo,
						ArquivoRetornoTipo.DescricaoArquivoRetornoTipo,
						PercentualJurosDiarios,
						PercentualMulta,
						ValorCobrancaMinima,
						LocalCobranca.IdArquivoRemessaTipo,
						ArquivoRemessaTipo.DescricaoArquivoRemessaTipo,
						ValorTaxaReImpressaoBoleto,
						ExtLogo,
						LocalCobranca.DataCriacao, 
						LocalCobranca.LoginCriacao, 
						LocalCobranca.DataAlteracao, 
						LocalCobranca.LoginAlteracao,
						LocalCobranca.IdStatus
					from 
						LocalCobranca LEFT JOIN LocalCobrancaLayout ON (LocalCobranca.IdLocalCobrancaLayout = LocalCobrancaLayout.IdLocalCobrancaLayout) LEFT JOIN ArquivoRetornoTipo ON (LocalCobranca.IdArquivoRetornoTipo = ArquivoRetornoTipo.IdArquivoRetornoTipo) LEFT JOIN ArquivoRemessaTipo ON (ArquivoRemessaTipo.IdArquivoRemessaTipo = LocalCobranca.IdArquivoRemessaTipo) LEFT JOIN Pessoa ON (LocalCobranca.IdPessoa = Pessoa.IdPessoa)
					where
						LocalCobranca.IdLoja = $local_IdLoja $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
			$dados	.=	"\n<IdLocalCobranca>$lin[IdLocalCobranca]</IdLocalCobranca>";
			$dados	.=	"\n<IdTipoLocalCobranca><![CDATA[$lin[IdTipoLocalCobranca]]]></IdTipoLocalCobranca>";
			$dados	.=	"\n<IdLojaCobrancaUnificada><![CDATA[$lin[IdLojaCobrancaUnificada]]]></IdLojaCobrancaUnificada>";
			$dados	.=	"\n<IdLocalCobrancaUnificada><![CDATA[$lin[IdLocalCobrancaUnificada]]]></IdLocalCobrancaUnificada>";
			$dados	.=	"\n<IdNotaFiscalTipo><![CDATA[$lin[IdNotaFiscalTipo]]]></IdNotaFiscalTipo>";
			$dados	.=	"\n<InicioNossoNumero><![CDATA[$lin[InicioNossoNumero]]]></InicioNossoNumero>";			
			$dados	.=	"\n<DiasCompensacao><![CDATA[$lin[DiasCompensacao]]]></DiasCompensacao>";
			$dados	.=	"\n<DiasAvisoRegressivo><![CDATA[$lin[DiasAvisoRegressivo]]]></DiasAvisoRegressivo>";
			$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<TipoPessoa><![CDATA[$lin[TipoPessoa]]]></TipoPessoa>";
			$dados	.=	"\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";			
			$dados	.=	"\n<Email><![CDATA[$lin[Email]]]></Email>";
			$dados	.=	"\n<CPF_CNPJ><![CDATA[$lin[CPF_CNPJ]]]></CPF_CNPJ>";
			$dados	.=	"\n<DescricaoLocalCobranca><![CDATA[$lin[DescricaoLocalCobranca]]]></DescricaoLocalCobranca>";
			$dados	.=	"\n<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";
			$dados	.=	"\n<IdLocalCobrancaLayout><![CDATA[$lin[IdLocalCobrancaLayout]]]></IdLocalCobrancaLayout>";
			$dados	.=	"\n<DescricaoLocalCobrancaLayout><![CDATA[$lin[DescricaoLocalCobrancaLayout]]]></DescricaoLocalCobrancaLayout>";
			$dados	.=	"\n<ValorDespesaLocalCobranca><![CDATA[$lin[ValorDespesaLocalCobranca]]]></ValorDespesaLocalCobranca>";
			$dados	.=	"\n<IdArquivoRetornoTipo><![CDATA[$lin[IdArquivoRetornoTipo]]]></IdArquivoRetornoTipo>";
			$dados	.=	"\n<DescricaoArquivoRetornoTipo><![CDATA[$lin[DescricaoArquivoRetornoTipo]]]></DescricaoArquivoRetornoTipo>";
			$dados	.=	"\n<PercentualJurosDiarios><![CDATA[$lin[PercentualJurosDiarios]]]></PercentualJurosDiarios>";
			$dados	.=	"\n<PercentualMulta><![CDATA[$lin[PercentualMulta]]]></PercentualMulta>";
			$dados	.=	"\n<ValorCobrancaMinima><![CDATA[$lin[ValorCobrancaMinima]]]></ValorCobrancaMinima>";
			$dados	.=	"\n<IdArquivoRemessaTipo><![CDATA[$lin[IdArquivoRemessaTipo]]]></IdArquivoRemessaTipo>";
			$dados	.=	"\n<DescricaoArquivoRemessaTipo><![CDATA[$lin[DescricaoArquivoRemessaTipo]]]></DescricaoArquivoRemessaTipo>";
			$dados	.=	"\n<ValorTaxaReImpressaoBoleto><![CDATA[$lin[ValorTaxaReImpressaoBoleto]]]></ValorTaxaReImpressaoBoleto>";
			$dados	.=	"\n<ExtLogo><![CDATA[$lin[ExtLogo]]]></ExtLogo>";
			$dados	.=	"\n<AvisoFaturaAtraso><![CDATA[$lin[AvisoFaturaAtraso]]]></AvisoFaturaAtraso>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_LocalCobranca();
?>