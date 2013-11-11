<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_lote_repasse_item(){
		
		global $con;
		global $_GET;
		
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdContaReceber 			= $_GET['IdContaReceber'];
		$IdLoteRepasse				= $_GET['IdLoteRepasse'];
		$where						= "";
		
		if($IdContaReceber != ''){	$where .= " and Demonstrativo.IdContaReceber=$IdContaReceber";	}
		if($IdLoteRepasse != ''){	$where .= " and LoteRepasseTerceiroItem.IdLoteRepasse=$IdLoteRepasse";	}
		
		$sql	=	"select
						Demonstrativo.IdLoja,
						Demonstrativo.IdContaReceber,
						Demonstrativo.IdLancamentoFinanceiro,
						Demonstrativo.IdProcessoFinanceiro,
						Demonstrativo.IdPessoa,
						substr(Pessoa.Nome,1,30) Nome,
						substr(Pessoa.RazaoSocial,1,30) RazaoSocial,
						Demonstrativo.Tipo,
						Demonstrativo.Codigo,
						Demonstrativo.Descricao,
						Demonstrativo.Referencia,
						Demonstrativo.Valor,
						LancamentoFinanceiro.ValorRepasseTerceiro,
						Demonstrativo.ValorDespesas,
						Demonstrativo.IdStatus,
						LoteRepasseTerceiroItem.ValorItemRepasse,
						LoteRepasseTerceiroItem.ValorDescontoItemRepasse,
						Demonstrativo.DataLancamento,
						Demonstrativo.DataVencimento,
						ContaReceberRecebimento.DataRecebimento
					from
						ContaReceberRecebimento,
						Demonstrativo,
						Pessoa,
						LoteRepasseTerceiroItem,
						LancamentoFinanceiro 
					where
						LoteRepasseTerceiroItem.IdLoja = $IdLoja and
						ContaReceberRecebimento.IdLoja = Demonstrativo.IdLoja and
						LancamentoFinanceiro.IdLoja = LoteRepasseTerceiroItem.IdLoja and
						ContaReceberRecebimento.IdContaReceber = Demonstrativo.IdContaReceber and
						(
							(
								if(
									Demonstrativo.Tipo = 'CO',
									Demonstrativo.Codigo = LancamentoFinanceiro.IdContrato,
									null
								)
							) 
							or (
								if(
									Demonstrativo.Tipo = 'OS',
									Demonstrativo.Codigo = LancamentoFinanceiro.IdOrdemServico,
									null
								)
							)
							or (
								if(
									Demonstrativo.Tipo = 'EF',
									Demonstrativo.Codigo = LancamentoFinanceiro.IdEncargoFinanceiro,
									null
								)
							)
							or (
								if(
									Demonstrativo.Tipo = 'EV',
									Demonstrativo.Codigo = LancamentoFinanceiro.IdContaEventual,
									null
								)
							)
							or (
								if(
									Demonstrativo.Tipo = 'CR',
									Demonstrativo.Codigo = LancamentoFinanceiro.IdContaReceberAgrupado,
									null
								)
							)
						) and
						Demonstrativo.IdPessoa = Pessoa.IdPessoa and
						Demonstrativo.IdLoja = LoteRepasseTerceiroItem.IdLoja and
						Demonstrativo.IdLancamentoFinanceiro = LoteRepasseTerceiroItem.IdLancamentoFinanceiro and
						LancamentoFinanceiro.IdLancamentoFinanceiro = LoteRepasseTerceiroItem.IdLancamentoFinanceiro $where
					group by
						Demonstrativo.IdLoja,
						Demonstrativo.IdContaReceber,
						Demonstrativo.IdLancamentoFinanceiro
					order by
					    Demonstrativo.IdPessoa,
						Demonstrativo.Tipo,
						Demonstrativo.Codigo,
						Demonstrativo.IdLancamentoFinanceiro $Limit";					     
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		$total = 0;
		while($lin	=	@mysql_fetch_array($res)){
			
			$lin[Nome]	=	$lin[getCodigoInterno(3,24)];	
			
			$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
			$dados	.=	"\n<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
			$dados	.=	"\n<IdLancamentoFinanceiro>$lin[IdLancamentoFinanceiro]</IdLancamentoFinanceiro>";
			$dados	.=	"\n<IdProcessoFinanceiro><![CDATA[$lin[IdProcessoFinanceiro]]]></IdProcessoFinanceiro>";
			$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<Referencia><![CDATA[$lin[Referencia]]]></Referencia>";
			$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";
			$dados	.=	"\n<ValorRepasseTerceiro><![CDATA[$lin[ValorRepasseTerceiro]]]></ValorRepasseTerceiro>";
			$dados	.=	"\n<ValorDespesas><![CDATA[$lin[ValorDespesas]]]></ValorDespesas>";
			$dados	.=	"\n<Descricao><![CDATA[$lin[Descricao]]]></Descricao>";
			$dados	.=	"\n<Tipo><![CDATA[$lin[Tipo]]]></Tipo>";
			$dados	.=	"\n<Codigo><![CDATA[$lin[Codigo]]]></Codigo>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<ValorItemRepasse><![CDATA[$lin[ValorItemRepasse]]]></ValorItemRepasse>";
			$dados	.=	"\n<ValorDescontoItemRepasse><![CDATA[$lin[ValorDescontoItemRepasse]]]></ValorDescontoItemRepasse>";
			$dados	.=	"\n<DataLancamento><![CDATA[$lin[DataLancamento]]]></DataLancamento>";
			$dados	.=	"\n<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
			$dados	.=	"\n<DataRecebimento><![CDATA[$lin[DataRecebimento]]]></DataRecebimento>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_lote_repasse_item();
?>