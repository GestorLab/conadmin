<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ContaReceberVencimento(){
		
		global $con;
		global $_GET;
		
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdContaReceber 			= $_GET['IdContaReceber'];
		$DataVencimento			 	= $_GET['DataVencimento'];
		$DataVencimentoAntiga	 	= $_GET['DataVencimentoAntiga'];
		$DataVencimentoAntiga		= dataConv($DataVencimentoAntiga, 'd/m/Y', 'Y-m-d');
		$DataVencimentoAntiga		= dia_util($DataVencimentoAntiga);
		$where						= "";
		
		if($IdContaReceber !=''){
			$where .= " and ContaReceberVencimento.IdContaReceber=$IdContaReceber";	
		}

		if($DataVencimento !=''){	
			$where .= " and ContaReceberVencimento.DataVencimento='$DataVencimento'";	
		}
		
		$sql	=	"select
						IdContaReceber,
						DataVencimento,
						ValorContaReceber,
						ValorMulta,
						ValorJuros,
						ValorTaxaReImpressaoBoleto,
						ValorDesconto,
						ValorOutrasDespesas,
						ManterDescontoAConceber,
						LoginCriacao,
						DataCriacao
					from
						ContaReceberVencimento
					where 
				 		IdLoja = $IdLoja $where
					order by
						DataCriacao ASC";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$lin[ValorTotal]	=	$lin[ValorContaReceber] + $lin[ValorMulta] + $lin[ValorJuros] + $lin[ValorTaxaReImpressaoBoleto] + $lin[ValorOutrasDespesas];
			$lin[ValorFinal]	=	$lin[ValorTotal] - $lin[ValorDesconto];
		
			$dados	.=	"\n<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
			$dados	.=	"\n<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
			$dados	.=	"\n<DataVencimentoAntiga><![CDATA[$DataVencimentoAntiga]]></DataVencimentoAntiga>";
			$dados	.=	"\n<ValorContaReceber><![CDATA[$lin[ValorContaReceber]]]></ValorContaReceber>";
			$dados	.=	"\n<ValorMulta><![CDATA[$lin[ValorMulta]]]></ValorMulta>";
			$dados	.=	"\n<ValorJuros><![CDATA[$lin[ValorJuros]]]></ValorJuros>";
			$dados	.=	"\n<ValorTaxaReImpressaoBoleto><![CDATA[$lin[ValorTaxaReImpressaoBoleto]]]></ValorTaxaReImpressaoBoleto>";
			$dados	.=	"\n<ValorOutrasDespesas><![CDATA[$lin[ValorOutrasDespesas]]]></ValorOutrasDespesas>";
			$dados	.=	"\n<ValorDesconto><![CDATA[$lin[ValorDesconto]]]></ValorDesconto>";
			$dados	.=	"\n<ValorTotal><![CDATA[$lin[ValorTotal]]]></ValorTotal>";
			$dados	.=	"\n<ValorFinal><![CDATA[$lin[ValorFinal]]]></ValorFinal>";
			$dados	.=	"\n<ManterDescontoAConceber><![CDATA[$lin[ManterDescontoAConceber]]]></ManterDescontoAConceber>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_ContaReceberVencimento();
?>
