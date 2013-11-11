<?
	$localModulo = 1;
	
	include("../../../files/conecta.php");
	include("../../../files/funcoes.php");
	include("../../../rotinas/verifica.php");
	
	function get_CaixaMovimentacao(){
		global $con;
		global $_GET;
		
		$local_IdLoja				= $_SESSION["IdLoja"];
		$local_Login				= $_SESSION["Login"];
		$local_IdCaixa				= $_GET["IdCaixa"];
		$local_IdCaixaMovimentacao	= $_GET["IdCaixaMovimentacao"];
		$where						= "";
		
		if($local_IdCaixa != ''){
			$where .= " AND CaixaMovimentacao.IdCaixa = '$local_IdCaixa'";
		}
		
		if($local_IdCaixaMovimentacao != ''){
			$where .= " AND CaixaMovimentacao.IdCaixaMovimentacao = '$local_IdCaixaMovimentacao'";
		}
		
		$sql = "SELECT 
					CaixaMovimentacao.IdLoja,
					CaixaMovimentacao.IdCaixa,
					CaixaMovimentacao.IdCaixaMovimentacao,
					CaixaMovimentacao.TipoMovimentacao IdTipoMovimentacao,
					CaixaMovimentacao.IdStatus,
					CaixaMovimentacao.Obs,
					date_format(CaixaMovimentacao.DataHoraCriacao, '%d/%m/%Y %H:%i:%s') DataHoraCriacao
				FROM 
					CaixaMovimentacao
				WHERE 
					CaixaMovimentacao.IdLoja = '$local_IdLoja' 
					$where;";
		$res = mysql_query($sql, $con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$lin[TipoMovimentacao] = getParametroSistema(244, $lin[IdTipoMovimentacao]);
				
				$sql_cx = "SELECT 
								Caixa.LoginAbertura
							FROM 
								Caixa
							WHERE 
								Caixa.IdLoja = '$lin[IdLoja]' AND 
								Caixa.IdCaixa = '$lin[IdCaixa]' AND 
								Caixa.LoginAbertura = '$local_Login'";
				$res_cx = @mysql_query($sql_cx, $con);
				$lin[PermisaoCancelar] = @mysql_num_rows($res_cx);
				
				$dados .= "<IdCaixa>$lin[IdCaixa]</IdCaixa>";
				$dados .= "<IdCaixaMovimentacao><![CDATA[$lin[IdCaixaMovimentacao]]]></IdCaixaMovimentacao>";
				$dados .= "<IdTipoMovimentacao><![CDATA[$lin[IdTipoMovimentacao]]]></IdTipoMovimentacao>";
				$dados .= "<TipoMovimentacao><![CDATA[$lin[TipoMovimentacao]]]></TipoMovimentacao>";
				$dados .= "<Obs><![CDATA[$lin[Obs]]]></Obs>";
				$dados .= "<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				$dados .= "<PermisaoCancelar><![CDATA[$lin[PermisaoCancelar]]]></PermisaoCancelar>";
				$dados .= "<DataHoraCriacao><![CDATA[$lin[DataHoraCriacao]]]></DataHoraCriacao>";
				$dados .= "<Item>";
				
				$sql_it = "SELECT 
								CaixaMovimentacaoItem.IdCaixaItem,
								CaixaMovimentacaoItem.IdContaReceber,
								CaixaMovimentacaoItem.ValorItem,
								CaixaMovimentacaoItem.ValorMulta,
								CaixaMovimentacaoItem.ValorJuros,
								CaixaMovimentacaoItem.ValorDesconto,
								CaixaMovimentacaoItem.ValorFinal,
								ContaReceberDados.NumeroDocumento,
								ContaReceberDados.DataVencimento,
								substr(Pessoa.Nome,1,30) Nome,
								substr(Pessoa.RazaoSocial,1,30) RazaoSocial
							FROM 
								CaixaMovimentacaoItem,
								ContaReceberDados,
								Pessoa
							WHERE 
								CaixaMovimentacaoItem.IdLoja = '".$lin["IdLoja"]."' AND 
								CaixaMovimentacaoItem.IdCaixa = '".$lin["IdCaixa"]."' AND 
								CaixaMovimentacaoItem.IdCaixaMovimentacao = '".$lin["IdCaixaMovimentacao"]."' AND 
								CaixaMovimentacaoItem.IdLoja = ContaReceberDados.IdLoja AND 
								CaixaMovimentacaoItem.IdContaReceber = ContaReceberDados.IdContaReceber AND 
								ContaReceberDados.IdPessoa = Pessoa.IdPessoa
							ORDER BY 
								CaixaMovimentacaoItem.IdCaixaItem;";
				$res_it = mysql_query($sql_it, $con);
				
				while($lin_it = mysql_fetch_array($res_it)){
					$lin_it[DiaUtil] = dia_util($lin_it[DataVencimento]);
					
					if($lin_it[DiaUtil] != date("Y-m-d") && str_replace("-", "", $lin_it[DiaUtil]) < date("Ymd")){
						$lin_it[DiaAtraso] = diferencaDiaData($lin_it[DiaUtil]." 00:00:00", date("Y-m-d 00:00:00"));
					} else{
						$lin_it[DiaAtraso] = 0;
					}
					
					$dados .= "<IdCaixaItem><![CDATA[$lin_it[IdCaixaItem]]]></IdCaixaItem>";
					$dados .= "<IdContaReceberItem><![CDATA[$lin_it[IdContaReceber]]]></IdContaReceberItem>";
					$dados .= "<ValorItem><![CDATA[$lin_it[ValorItem]]]></ValorItem>";
					$dados .= "<ValorMultaItem><![CDATA[$lin_it[ValorMulta]]]></ValorMultaItem>";
					$dados .= "<ValorJurosItem><![CDATA[$lin_it[ValorJuros]]]></ValorJurosItem>";
					$dados .= "<ValorDescontoItem><![CDATA[$lin_it[ValorDesconto]]]></ValorDescontoItem>";
					$dados .= "<ValorFinalItem><![CDATA[$lin_it[ValorFinal]]]></ValorFinalItem>";
					$dados .= "<NumeroDocumentoItem><![CDATA[$lin_it[NumeroDocumento]]]></NumeroDocumentoItem>";
					$dados .= "<DataVencimentoItem><![CDATA[$lin_it[DataVencimento]]]></DataVencimentoItem>";
					$dados .= "<DiaAtrasoItem><![CDATA[$lin_it[DiaAtraso]]]></DiaAtrasoItem>";
					$dados .= "<NomeItem><![CDATA[$lin_it[Nome]]]></NomeItem>";
					$dados .= "<RazaoSocialItem><![CDATA[$lin_it[RazaoSocial]]]></RazaoSocialItem>";
				}
				
				$dados .= "</Item>";
				$dados .= "<FormaPagamento>";
				
				$sql_fc = "SELECT 
								CaixaMovimentacaoFormaPagamento.IdFormaPagamento,
								CaixaMovimentacaoFormaPagamento.ValorFormaPagamento,
								CaixaMovimentacaoFormaPagamento.QtdParcelas,
								CaixaMovimentacaoFormaPagamento.ValorParcela,
								CaixaMovimentacaoFormaPagamento.ValorJuros,
								CaixaMovimentacaoFormaPagamento.ValorTotal
							FROM 
								CaixaMovimentacaoFormaPagamento
							WHERE
								CaixaMovimentacaoFormaPagamento.IdLoja = '".$lin["IdLoja"]."' AND 
								CaixaMovimentacaoFormaPagamento.IdCaixa = '".$lin["IdCaixa"]."' AND 
								CaixaMovimentacaoFormaPagamento.IdCaixaMovimentacao = '".$lin["IdCaixaMovimentacao"]."'
							ORDER BY 
								CaixaMovimentacaoFormaPagamento.IdCaixaMovimentacao;";
				$res_fc = mysql_query($sql_fc, $con);
				
				while($lin_fc = mysql_fetch_array($res_fc)){
					$dados .= "<IdFormaPagamento><![CDATA[$lin_fc[IdFormaPagamento]]]></IdFormaPagamento>";
					$dados .= "<ValorFormaPagamento><![CDATA[$lin_fc[ValorFormaPagamento]]]></ValorFormaPagamento>";
					$dados .= "<QtdParcelas><![CDATA[$lin_fc[QtdParcelas]]]></QtdParcelas>";
					$dados .= "<ValorParcela><![CDATA[$lin_fc[ValorParcela]]]></ValorParcela>";
					$dados .= "<ValorJuros><![CDATA[$lin_fc[ValorJuros]]]></ValorJuros>";
					$dados .= "<ValorTotal><![CDATA[$lin_fc[ValorTotal]]]></ValorTotal>";
				}
				
				$dados .= "</FormaPagamento>";
			}
			
			$dados .= "</reg>";
			
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_CaixaMovimentacao();
?>