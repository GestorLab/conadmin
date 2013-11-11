<?php
	$localModulo = 1;
	
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_ContaReceberOcorrencia(){
		global $con;
		global $_GET;
		
		$IdLoja		 		= $_SESSION["IdLoja"];
		$IdArquivoRetorno	= $_GET['IdArquivoRetorno'];
		$IdLocalRecebimento	= $_GET['IdLocalRecebimento'];
		$Lista				= false;
		$where				= "";
		
		if($IdArquivoRetorno != ''){
			$where .= " and ContaReceberRecebimento.IdArquivoRetorno=$IdArquivoRetorno";
			$where .= " and ContaReceberRecebimento.IdLojaRecebimento=$IdLoja";
		}
		
		if($IdLocalRecebimento != ''){
			$where .= " and ContaReceberRecebimento.IdLocalCobranca=$IdLocalRecebimento";
		}
		
		$sql = "SELECT DISTINCT
					ContaReceberDados.IdLoja,
					ContaReceberDados.IdContaReceber,
					ContaReceberDados.DataLancamento,
					ADDDATE(ContaReceberDados.DataVencimento, INTERVAL ContaReceberDados.LimiteDesconto DAY ) DataLimiteDesconto,
					ContaReceberDados.DataVencimento,
					ContaReceberDados.ValorFinal, 
					ContaReceberDados.NumeroDocumento,
					ContaReceberDados.IdStatus IdStatusContaReceber,
					Pessoa.TipoPessoa,
					Pessoa.Nome,
					Pessoa.RazaoSocial,
					ContaReceberRecebimento.IdContaReceberRecebimento,
					ContaReceberRecebimento.ValorRecebido,
					ContaReceberRecebimento.ValorDesconto ValorDescontoRecebimento, 
					ContaReceberRecebimento.ValorOutrasDespesas,
					ContaReceberRecebimento.DataRecebimento,
					ContaReceberRecebimento.ValorMoraMulta,
					ContaReceberRecebimento.IdArquivoRetorno,
					ContaReceberRecebimento.IdContratoEstorno,
					ContaReceberRecebimento.MD5,
					ContaReceberRecebimento.IdRecibo,
					ContaReceberRecebimento.IdLocalCobranca IdLocalRecebimento,
					ContaReceberRecebimento.ValorDesconto,
					ContaReceberRecebimento.Obs,
					ContaReceberRecebimento.IdStatus IdStatusRecebimento, 
					ContaReceberRecebimento.DataCriacao,
					ContaReceberRecebimento.LoginCriacao,
					ContaReceberRecebimento.DataAlteracao,
					ContaReceberRecebimento.LoginAlteracao
				FROM
					ContaReceberDados,
					Pessoa,
					ContaReceberRecebimento,
					LancamentoFinanceiroContaReceber,
					LancamentoFinanceiro
				WHERE 
					ContaReceberDados.IdLoja = $IdLoja AND 
					ContaReceberDados.IdPessoa = Pessoa.IdPessoa AND 
					ContaReceberDados.IdLoja = ContaReceberRecebimento.IdLoja AND 
					ContaReceberDados.IdContaReceber = ContaReceberRecebimento.IdContaReceber AND 
					ContaReceberDados.IdLoja = LancamentoFinanceiroContaReceber.IdLoja AND 
					ContaReceberDados.IdContaReceber = LancamentoFinanceiroContaReceber.IdContareceber AND 
					LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja AND 
					LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro AND 
					ContaReceberRecebimento.IdStatus != 0
					$where";
		$res = @mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$lin[MargemMoraMulta] = (float) str_replace(",", ".", preg_replace("/([ \r\n\t])/", null, getCodigoInterno(17, 2)));
				$lin[DataVencimentoDiaUtil] = dia_util($lin[DataVencimento]);
				$lin[DataLimiteDesconto] = dia_util($lin[DataLimiteDesconto]);
				
				if(((((float) $lin[ValorFinal] - $lin[MargemMoraMulta]) < (float) $lin[ValorRecebido]) && ((int) str_replace("-", null, $lin[DataLimiteDesconto]) < (int) str_replace("-", null, $lin[DataRecebimento]))) || (((int) str_replace("-", null, $lin[DataVencimentoDiaUtil]) < (int) str_replace("-", null, $lin[DataRecebimento]))	&& ((float) $lin[ValorMoraMulta] == 0.00))){
					$Lista = true;
					$lin[Status] = null;
					
					if((int) str_replace("-", null, $lin[DataLimiteDesconto]) < (int) str_replace("-", null, $lin[DataRecebimento]) && (float) $lin[ValorDesconto] > 0.00){
						$lin[IdStatus] = 1; # Desconto fora do prazo
					} elseif(((float) $lin[ValorFinal] - $lin[MargemMoraMulta]) > (float) $lin[ValorRecebido]){
						$lin[IdStatus] = 2; # Valor inferior
					} else{
						$lin[IdStatus] = 3; # Multa/Juros incorreta
					}
					
					if(!empty($lin[IdStatus])){
						$lin[Status] = getParametroSistema(255, $lin[IdStatus]);
					}
					
					if((int)$lin[TipoPessoa] == 1){
						$lin[Nome] = $lin[getCodigoInterno(3,24)];
					}
					
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
					$dados	.=	"\n<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
					$dados	.=	"\n<DataVencimentoDiaUtil><![CDATA[$lin[DataVencimentoDiaUtil]]]></DataVencimentoDiaUtil>";
					$dados	.=	"\n<DataLimiteDesconto><![CDATA[$lin[DataLimiteDesconto]]]></DataLimiteDesconto>";
					$dados	.=	"\n<MargemMoraMulta><![CDATA[$lin[MargemMoraMulta]]]></MargemMoraMulta>";
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
					$dados	.=	"\n<IdContratoEstorno><![CDATA[$lin[IdContratoEstorno]]]></IdContratoEstorno>";
					$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
					$dados	.=	"\n<ValorParcela><![CDATA[$lin[ValorFinal]]]></ValorParcela>";
					$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
					$dados	.=	"\n<Status><![CDATA[$lin[Status]]]></Status>";
				}
			}
			
			$dados	.=	"\n</reg>";
			
			if($Lista){
				header("content-type: text/xml");
				
				return $dados;
			}
		}
		
		return "false";
	}
	
	echo get_ContaReceberOcorrencia();
?>