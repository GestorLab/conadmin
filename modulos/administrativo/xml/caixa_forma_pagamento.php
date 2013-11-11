<?
	$localModulo = 1;
	
	include("../../../files/conecta.php");
	include("../../../files/funcoes.php");
	include("../../../rotinas/verifica.php");
	
	function get_CaixaFormaPagamento(){
		global $con;
		global $_GET;
		
		$local_IdLoja	= $_SESSION["IdLoja"];
		$local_IdCaixa	= $_GET["IdCaixa"];
		
		$sql = "SELECT DISTINCT
					CaixaFormaPagamento.IdFormaPagamento,
					CaixaFormaPagamento.ValorAbertura,
					FormaPagamento.DescricaoFormaPagamento
				FROM 
					CaixaFormaPagamento,
					FormaPagamento
				WHERE
					CaixaFormaPagamento.IdLoja = '".$local_IdLoja."' AND
					CaixaFormaPagamento.IdCaixa = '".$local_IdCaixa."' AND
					CaixaFormaPagamento.IdLoja = FormaPagamento.IdLoja AND
					CaixaFormaPagamento.IdFormaPagamento = FormaPagamento.IdFormaPagamento;";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$dados .= "\n<IdFormaPagamento>$lin[IdFormaPagamento]</IdFormaPagamento>";
				$dados .= "\n<DescricaoFormaPagamento><![CDATA[$lin[DescricaoFormaPagamento]]]></DescricaoFormaPagamento>";
				$dados .= "\n<ValorAbertura><![CDATA[$lin[ValorAbertura]]]></ValorAbertura>";
				$dados .= "\n<Parcela>";
				
				$sql_pc = "SELECT 
								FormaPagamentoParcela.QtdParcela,
								FormaPagamentoParcela.PercentualJurosMes 
							FROM
								FormaPagamentoParcela 
							WHERE 
								FormaPagamentoParcela.IdLoja = '".$local_IdLoja."' AND 
								FormaPagamentoParcela.IdFormaPagamento = '".$lin[IdFormaPagamento]."' ";
				$res_pc = mysql_query($sql_pc, $con);
				
				while($lin_pc = mysql_fetch_array($res_pc)){
					$dados .= "\n<QtdParcela><![CDATA[$lin_pc[QtdParcela]]]></QtdParcela>";
					$dados .= "\n<PercentualJurosMes><![CDATA[$lin_pc[PercentualJurosMes]]]></PercentualJurosMes>";
				}
				
				$dados .= "\n</Parcela>";
			}
			
			$dados .= "\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_CaixaFormaPagamento();
?>