<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Contrato_Autorizar_Credito(){
		
		global $con;
		global $_GET;
		
		$IdLoja					= $_SESSION["IdLoja"];
		$IdPessoaLogin			= $_SESSION['IdPessoa'];
		$IdContrato				= $_GET['IdContrato'];
		$DataCancelamento		= dataConv($_GET['DataCancelamento'], 'd/m/Y', 'Y-m-d');
		$DataCancelamento		= incrementaData($DataCancelamento,1);
			
		$sql	=	"select
						Contrato.IdLoja,
						Contrato.IdContrato,			
						Contrato.IdServico, 
						Servico.DescricaoServico,
						LancamentoFinanceiro.IdLancamentoFinanceiro,
						LancamentoFinanceiroContaReceber.IdContaReceber,			
						LancamentoFinanceiro.Valor,	
						LancamentoFinanceiro.DataReferenciaInicial,	
						LancamentoFinanceiro.DataReferenciaFinal
					from 
						Loja,
						Contrato,				
						Servico,
						Periodicidade,
						ContaReceberRecebimento,
						LancamentoFinanceiro,
						LancamentoFinanceiroContaReceber
					where 
						Contrato.IdLoja = $IdLoja and
						Contrato.IdLoja = Loja.IdLoja and
						Contrato.IdLoja = Servico.IdLoja and
						Contrato.IdLoja = Periodicidade.IdLoja and
						Contrato.IdLoja = LancamentoFinanceiro.IdLoja and
						Contrato.IdLoja = ContaReceberRecebimento.IdLoja and
						Contrato.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
						Contrato.IdServico = Servico.IdServico and
						Contrato.IdPeriodicidade = Periodicidade.IdPeriodicidade and
						LancamentoFinanceiro.IdContrato = Contrato.IdContrato and 	
						LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
						LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceberRecebimento.IdContaReceber and
						ContaReceberRecebimento.IdStatus='1' and
						LancamentoFinanceiro.DataReferenciaFinal > '$DataCancelamento' and 
						Contrato.IdContrato = $IdContrato";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			
			$nDiasIntervalo = nDiasIntervalo($lin[DataReferenciaInicial],$lin[DataReferenciaFinal]);
			
			if(dataConv($lin[DataReferenciaInicial], 'Y-m-d', 'Ymd') < dataConv($DataCancelamento,'Y-m-d', 'Ymd')){

				$lin[DataReferenciaInicial] = $DataCancelamento;

				$qtdDias = nDiasIntervalo($lin[DataReferenciaInicial],$lin[DataReferenciaFinal]);

				$lin[Valor] = $lin[Valor]/$nDiasIntervalo*$qtdDias;
				$lin[Valor] = round($lin[Valor],2);
			}else{
				$qtdDias = $nDiasIntervalo;
			}

			$lin[DataReferenciaInicial] = dataConv($lin[DataReferenciaInicial], "Y-m-d", "d/m/Y");
			$lin[DataReferenciaFinal] = dataConv($lin[DataReferenciaFinal], "Y-m-d", "d/m/Y");

			$dados	.=	"\n<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";					
			$dados	.=	"\n<IdLancamentoFinanceiro>$lin[IdLancamentoFinanceiro]</IdLancamentoFinanceiro>";
			$dados	.=	"\n<IdContrato>$lin[IdContrato]</IdContrato>";		
			$dados	.=	"\n<IdServico>$lin[IdServico]</IdServico>";		
			$dados	.=	"\n<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
			$dados	.=	"\n<DataReferenciaInicial><![CDATA[$lin[DataReferenciaInicial]]]></DataReferenciaInicial>";		
			$dados	.=	"\n<DataReferenciaFinal><![CDATA[$lin[DataReferenciaFinal]]]></DataReferenciaFinal>";			
			$dados	.=	"\n<QtdDias><![CDATA[$qtdDias]]></QtdDias>";		
			$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";			
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Contrato_Autorizar_Credito();
?>