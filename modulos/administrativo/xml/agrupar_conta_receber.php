<?
	$localModulo = 1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_agrupar_conta_receber() {
		global $con;
		global $_GET;
		
		$local_IdLoja					= $_SESSION["IdLoja"];
		$local_IdContaReceberAgrupador	= $_GET['IdContaReceberAgrupador'];
		
		$sql = "SELECT 
					ContaReceberAgrupado.IdContaReceberAgrupador,
					ContaReceberAgrupado.IdPessoa,
					ContaReceberAgrupado.IdPessoaEndereco,
					ContaReceberAgrupado.IdLocalCobranca,
					ContaReceberAgrupado.QtdParcela,
					ContaReceberAgrupado.OcultarReferencia,
					ContaReceberAgrupado.ValorDespesaLocalCobranca,
					ContaReceberAgrupado.ValorDesconto,
					ContaReceberAgrupado.ValorMulta,
					ContaReceberAgrupado.ValorJuros,
					ContaReceberAgrupado.ValorReImpressaoBoleto,
					ContaReceberAgrupado.ValorOutrasDespesas,
					ContaReceberAgrupado.ValorTotal,
					ContaReceberAgrupado.IdStatus,
					ContaReceberAgrupado.DataCriacao,
					ContaReceberAgrupado.LoginCriacao,
					ContaReceberAgrupadoParcela.IdContaReceber,
					ContaReceberAgrupadoParcela.NumParcelaContaReceberAgrupado,
					ContaReceberAgrupadoParcela.ValorParcela,
					ContaReceberAgrupadoParcela.ValorDespesas,
					ContaReceberAgrupadoParcela.DataVencimento
				FROM
					ContaReceberAgrupado,
					ContaReceberAgrupadoParcela
				WHERE ContaReceberAgrupado.IdLoja = '$local_IdLoja' AND 
					ContaReceberAgrupado.IdContaReceberAgrupador = '$local_IdContaReceberAgrupador' AND 
					ContaReceberAgrupado.IdLoja = ContaReceberAgrupadoParcela.IdLoja AND 
					ContaReceberAgrupado.IdContaReceberAgrupador = ContaReceberAgrupadoParcela.IdContaReceberAgrupador 
				ORDER BY 
					ContaReceberAgrupadoParcela.DataVencimento ASC;";
		$res = @mysql_query($sql, $con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			$cont = 0;
			
			while($lin	= @mysql_fetch_array($res)){
				if($cont < 1){
					$IdContaReceberAgrupados = '';
					$sql0 = "SELECT
								IdContaReceber
							FROM
								ContaReceberAgrupadoItem
							WHERE
								IdLoja = '$local_IdLoja' AND
								IdContaReceberAgrupador = '$local_IdContaReceberAgrupador';";
					$res0 = @mysql_query($sql0, $con);
					
					while($lin0 = @mysql_fetch_array($res0)){
						if($IdContaReceberAgrupados != ""){
							$IdContaReceberAgrupados .= ",".$lin0[IdContaReceber];
						} else{
							$IdContaReceberAgrupados = $lin0[IdContaReceber];
						}
					}
					# AGRUPADOR
					$dados	.=	"\n<IdContaReceberAgrupador>$lin[IdContaReceberAgrupador]</IdContaReceberAgrupador>";
					$dados	.=	"\n<IdPessoa>$lin[IdPessoa]</IdPessoa>";
					$dados	.=	"\n<IdPessoaEndereco>$lin[IdPessoaEndereco]</IdPessoaEndereco>";
					$dados	.=	"\n<IdContaReceberAgrupados><![CDATA[$IdContaReceberAgrupados]]></IdContaReceberAgrupados>";
					$dados	.=	"\n<IdLocalCobranca>$lin[IdLocalCobranca]</IdLocalCobranca>";
					$dados	.=	"\n<QtdParcela>$lin[QtdParcela]</QtdParcela>";
					$dados	.=	"\n<OcultarReferencia>$lin[OcultarReferencia]</OcultarReferencia>";
					$dados	.=	"\n<ValorDespesaLocalCobranca><![CDATA[$lin[ValorDespesaLocalCobranca]]]></ValorDespesaLocalCobranca>";
					$dados	.=	"\n<ValorDesconto><![CDATA[$lin[ValorDesconto]]]></ValorDesconto>";
					$dados	.=	"\n<ValorMulta><![CDATA[$lin[ValorMulta]]]></ValorMulta>";
					$dados	.=	"\n<ValorJuros><![CDATA[$lin[ValorJuros]]]></ValorJuros>";
					$dados	.=	"\n<ValorTaxaReImpressaoBoleto><![CDATA[$lin[ValorReImpressaoBoleto]]]></ValorTaxaReImpressaoBoleto>";
					$dados	.=	"\n<ValorOutrasDespesas><![CDATA[$lin[ValorOutrasDespesas]]]></ValorOutrasDespesas>";
					$dados	.=	"\n<ValorTotal><![CDATA[$lin[ValorTotal]]]></ValorTotal>";
					$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
					$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
					$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
					$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
					# PARCELAS
					$dados	.=	"\n<ContaReceberAgrupadoParcela>";
					$dados	.=	"\n<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
					$dados	.=	"\n<NumParcelaContaReceberAgrupado><![CDATA[$lin[NumParcelaContaReceberAgrupado]]]></NumParcelaContaReceberAgrupado>";
					$dados	.=	"\n<ValorParcela><![CDATA[$lin[ValorParcela]]]></ValorParcela>";
					$dados	.=	"\n<ValorDespesas><![CDATA[$lin[ValorDespesas]]]></ValorDespesas>";
					$dados	.=	"\n<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
				} else{
					$dados	.=	"\n<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
					$dados	.=	"\n<NumParcelaContaReceberAgrupado><![CDATA[$lin[NumParcelaContaReceberAgrupado]]]></NumParcelaContaReceberAgrupado>";
					$dados	.=	"\n<ValorParcela><![CDATA[$lin[ValorParcela]]]></ValorParcela>";
					$dados	.=	"\n<ValorDespesas><![CDATA[$lin[ValorDespesas]]]></ValorDespesas>";
					$dados	.=	"\n<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
				}
				
				$cont++;
			}
			
			$dados	.=	"\n</ContaReceberAgrupadoParcela>";
			$dados	.=	"\n</reg>";
			
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_agrupar_conta_receber();
?>