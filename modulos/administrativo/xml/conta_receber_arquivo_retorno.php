<?
	$localModulo	=	1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ContaReceberRecebimento(){
		global $con;
		global $_GET;
		
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdContaReceber 			= $_GET['IdContaReceber'];
		$IdContaReceberRecebimento 	= $_GET['IdContaReceberRecebimento'];
		$IdArquivoRetorno			= $_GET['IdArquivoRetorno'];
		$IdLocalCobranca			= $_GET['IdLocalCobranca'];
		$where						= "";
		
		if($IdContaReceber != ''){
			$where .= " and ContaReceberRecebimento.IdContaReceber=$IdContaReceber";
			$where .= " and ContaReceberRecebimento.IdLoja=$IdLoja";
		}
		
		if($IdContaReceberRecebimento != ''){
			$where .= " and ContaReceberRecebimento.IdContaReceberRecebimento=$IdContaReceberRecebimento";
		}
		
		if($IdArquivoRetorno != ''){
			$where .= " and ContaReceberRecebimento.IdArquivoRetorno=$IdArquivoRetorno";
			$where .= " and ContaReceberRecebimento.IdLojaRecebimento=$IdLoja";
		}
		
		if($IdLocalCobranca != ''){
			$where .= " and ContaReceberRecebimento.IdLocalCobranca=$IdLocalCobranca";
		}
		
		$sql = "select
						ContaReceberRecebimento.IdLoja,
						ContaReceberRecebimento.IdContaReceberRecebimento, 
						ContaReceberRecebimento.DataRecebimento,
						ContaReceberDados.DataLancamento,
						ContaReceberRecebimento.IdLocalCobranca IdLocalRecebimento, 
						ContaReceberRecebimento.ValorRecebido, 
						ContaReceberRecebimento.IdLoja, 
						ContaReceberRecebimento.ValorDesconto ValorDescontoRecebimento, 
						ContaReceberRecebimento.ValorOutrasDespesas, 
						ContaReceberRecebimento.ValorMoraMulta, 
						ContaReceberRecebimento.IdContaReceber, 
						ContaReceberRecebimento.MD5, 
						ContaReceberRecebimento.IdRecibo, 
						ContaReceberRecebimento.IdArquivoRetorno, 
						ContaReceberRecebimento.IdContratoEstorno,
						ContaReceberDados.NumeroDocumento,
						ContaReceberDados.ValorDesconto, 
						ContaReceberDados.ValorDespesas, 
						ContaReceberDados.DataVencimento, 
						ContaReceberDados.LimiteDesconto, 
						ContaReceberDados.ValorFinal, 
						ContaReceberDados.IdStatus IdStatusContaReceber,
						ContaReceberRecebimento.IdStatus IdStatusRecebimento, 
						ContaReceberRecebimento.DataCriacao, 
						ContaReceberRecebimento.LoginCriacao, 
						ContaReceberRecebimento.DataAlteracao, 
						ContaReceberRecebimento.LoginAlteracao, 
						ContaReceberRecebimento.Obs,
						ContaReceberDados.IdPessoa
					from
						ContaReceberRecebimento, 
						ContaReceberDados 
					where 
						ContaReceberRecebimento.IdLoja = ContaReceberDados.IdLoja and 
						ContaReceberRecebimento.IdContaReceber = ContaReceberDados.IdContaReceber $where
					order by
						ContaReceberRecebimento.IdLoja,
						ContaReceberRecebimento.IdRecibo";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) >= 1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$sql2 = "select 
							TipoPessoa,
							Nome,
							RazaoSocial 
						from
							Pessoa
						where 
							Pessoa.IdPessoa = $lin[IdPessoa]";
				$res2 = @mysql_query($sql2,$con);
				$lin2 = @mysql_fetch_array($res2);
				
				if($lin2[TipoPessoa]=='1'){
					$lin2[Nome] = $lin2[getCodigoInterno(3,24)];
				}
				
				$lin[DataLimiteDesconto] = incrementaData($lin[DataVencimento], $lin[LimiteDesconto]);
				$lin[DataVencimentoDiaUtil] = dia_util($lin[DataVencimento]);
				$lin[DataLimiteDesconto] = dia_util($lin[DataLimiteDesconto]);
				$lin[MargemMoraMulta] = (float) str_replace(",", ".", preg_replace("/([ \r\n\t])/", null, getCodigoInterno(17, 2)));
				
				$dados	.=	"\n<IdLoja><![CDATA[$lin[IdLoja]]]></IdLoja>";
				$dados	.=	"\n<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
				$dados	.=	"\n<IdContaReceberRecebimento><![CDATA[$lin[IdContaReceberRecebimento]]]></IdContaReceberRecebimento>";
				$dados	.=	"\n<DataRecebimento><![CDATA[$lin[DataRecebimento]]]></DataRecebimento>";
				$dados	.=	"\n<DataLancamento><![CDATA[$lin[DataLancamento]]]></DataLancamento>";
				$dados	.=	"\n<ValorRecebido><![CDATA[$lin[ValorRecebido]]]></ValorRecebido>";
				$dados	.=	"\n<ValorDescontoRecebimento><![CDATA[$lin[ValorDescontoRecebimento]]]></ValorDescontoRecebimento>";
				$dados	.=	"\n<ValorDesconto><![CDATA[$lin[ValorDesconto]]]></ValorDesconto>";
				$dados	.=	"\n<ValorOutrasDespesas><![CDATA[$lin[ValorOutrasDespesas]]]></ValorOutrasDespesas>";
				$dados	.=	"\n<ValorMoraMulta><![CDATA[$lin[ValorMoraMulta]]]></ValorMoraMulta>";
				$dados	.=	"\n<IdArquivoRetorno><![CDATA[$lin[IdArquivoRetorno]]]></IdArquivoRetorno>";
				$dados	.=	"\n<DataVencimentoDiaUtil><![CDATA[$lin[DataVencimentoDiaUtil]]]></DataVencimentoDiaUtil>";
				$dados	.=	"\n<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
				$dados	.=	"\n<DataLimiteDesconto><![CDATA[$lin[DataLimiteDesconto]]]></DataLimiteDesconto>";
				$dados	.=	"\n<NumeroDocumento><![CDATA[$lin[NumeroDocumento]]]></NumeroDocumento>";
				$dados	.=	"\n<MD5><![CDATA[$lin[MD5]]]></MD5>";
				$dados	.=	"\n<IdRecibo><![CDATA[$lin[IdRecibo]]]></IdRecibo>";
				$dados	.=	"\n<IdLocalRecebimento><![CDATA[$lin[IdLocalRecebimento]]]></IdLocalRecebimento>";
				$dados	.=	"\n<ValorContaReceber><![CDATA[$lin[ValorFinal]]]></ValorContaReceber>";
				$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
				$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
				$dados	.=	"\n<IdStatusContaReceber><![CDATA[$lin[IdStatusContaReceber]]]></IdStatusContaReceber>";
				$dados	.=	"\n<IdStatusRecebimento><![CDATA[$lin[IdStatusRecebimento]]]></IdStatusRecebimento>";
				$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
				$dados	.=	"\n<Nome><![CDATA[$lin2[Nome]]]></Nome>";
				$dados	.=	"\n<ValorParcela><![CDATA[$lin[ValorFinal]]]></ValorParcela>";
				$dados	.=	"\n<IdContratoEstorno><![CDATA[$lin[IdContratoEstorno]]]></IdContratoEstorno>";
				$dados	.=	"\n<MargemMoraMulta><![CDATA[$lin[MargemMoraMulta]]]></MargemMoraMulta>";
			}
			
			$dados	.=	"\n</reg>";
			
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_ContaReceberRecebimento();
?>