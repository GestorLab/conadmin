<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ContaReceberRecebimento(){
		
		global $con;
		global $_GET;
		
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdProcessoFinanceiro		= $_GET['IdProcessoFinanceiro'];
		$Order						= $_GET['Order'];
		$OrderBy					= $_GET['OrderBy'];
		$where						= "";
		
		if($OrderBy != "" && $OrderBy != ""){
			$orderby = "ContaReceberDados.".$OrderBy." ".$Order."";
		}else{
			$orderby = "ContaReceberDados.IdLoja";
		}
		
		if($IdProcessoFinanceiro !=''){
			$IdContaReceberPF="";
			$sqlPF="select distinct 
						IdContaReceber 
					from
						LancamentoFinanceiroContaReceber 
					where
						IdLoja = $IdLoja and
						IdLancamentoFinanceiro in (
							select 
								IdLancamentoFinanceiro 
							from
								LancamentoFinanceiro 
							where
							IdLoja = $IdLoja and
							IdProcessoFinanceiro = $IdProcessoFinanceiro
						)";
			$resPF = mysql_query($sqlPF,$con);
			while($linPF = mysql_fetch_array($resPF)){
				if($IdContaReceberPF != ''){
					$IdContaReceberPF .= ',';
				}
				
				$IdContaReceberPF .= $linPF[IdContaReceber];
			}
			
			$where .= " ContaReceberDados.IdContaReceber in($IdContaReceberPF)";
			$where .= " and ContaReceberDados.IdLoja=$IdLoja";
		}
		
		$sql ="	select
					IdLoja,
					IdContaReceber,
					NumeroDocumento,
					NumeroNF,
					DataLancamento,
					DataVencimento,
					IdLocalCobranca IdLocalRecebimento,
					ValorContaReceber,
					ValorFinalAtualizado,
					IdStatus
				from
					ContaReceberDados
				where
					$where
				order by $orderby";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			if($lin[IdLocalRecebimento] != ""){
				$sql3	=	"select 
								LocalCobranca.DescricaoLocalCobranca DescricaoLocalRecebimento
							from
								LocalCobranca
							where 
								LocalCobranca.IdLoja = '$lin[IdLoja]' and 
								LocalCobranca.IdLocalCobranca = '$lin[IdLocalRecebimento]'";
				$res3	=	@mysql_query($sql3,$con);
				$lin3	=	@mysql_fetch_array($res3);
			}
			
			$sql2	=	"select 
							TipoPessoa,
							Nome,
							RazaoSocial 
						from
							Pessoa,
							Demonstrativo 
						where 
							Demonstrativo.IdLoja = $lin[IdLoja] and 
							Demonstrativo.IdContaReceber=$lin[IdContaReceber] and 
							Demonstrativo.IdPessoa = Pessoa.IdPessoa";
			$res2	=	@mysql_query($sql2,$con);
			$lin2	=	@mysql_fetch_array($res2);
			
			if($lin2[TipoPessoa]=='1'){
				$lin2[Nome]	=	$lin2[getCodigoInterno(3,24)];	
			}
								
			$dados	.=	"\n<IdLoja><![CDATA[$lin[IdLoja]]]></IdLoja>";
			$dados	.=	"\n<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
			$dados	.=	"\n<DescricaoLocalRecebimento><![CDATA[$lin3[DescricaoLocalRecebimento]]]></DescricaoLocalRecebimento>";
			$dados	.=	"\n<DataLancamento><![CDATA[$lin[DataLancamento]]]></DataLancamento>";
			$dados	.=	"\n<NumeroNF><![CDATA[$lin[NumeroNF]]]></NumeroNF>";
			$dados	.=	"\n<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
			$dados	.=	"\n<ValorContaReceber><![CDATA[$lin[ValorContaReceber]]]></ValorContaReceber>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<Nome><![CDATA[$lin2[Nome]]]></Nome>";
			$dados	.=	"\n<NumeroDocumento><![CDATA[$lin[NumeroDocumento]]]></NumeroDocumento>";
			
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	
	
	echo get_ContaReceberRecebimento();
?>
