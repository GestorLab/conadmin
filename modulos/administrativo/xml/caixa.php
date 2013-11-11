<?
	$localModulo = 1;
	
	include("../../../files/conecta.php");
	include("../../../files/funcoes.php");
	include("../../../rotinas/verifica.php");
	
	function get_Caixa(){
		global $con;
		global $_GET;
		
		$local_Login	= $_SESSION["Login"];
		$local_IdLoja	= $_SESSION["IdLoja"];
		$local_IdCaixa	= $_GET["IdCaixa"];
		$where			= "";
		
		if($local_IdCaixa != ''){
			$where .= " AND Caixa.IdCaixa = '$local_IdCaixa'";
		}
		
		$sql = "SELECT 
					Caixa.IdCaixa,
					Caixa.IdStatus,
					Caixa.DataAbertura,
					Caixa.LoginAbertura,
					Caixa.DataFechamento,
					Caixa.LoginFechamento
				FROM 
					Caixa
				WHERE 
					Caixa.IdLoja = '$local_IdLoja'
					$where;";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$lin[Status] = getParametroSistema(243, $lin[IdStatus]);
				$lin[CorStatus] = getCodigoInterno(52, $lin[IdStatus]);
				
				$sql_rp = "SELECT 
								Pessoa.Nome,
								Pessoa.RazaoSocial
							FROM 
								Usuario,
								Pessoa
							WHERE
								Usuario.Login = '".$lin[LoginAbertura]."' AND
								Usuario.IdPessoa = Pessoa.IdPessoa;";
				$res_rp = @mysql_query($sql_rp, $con);
				$lin_rp = @mysql_fetch_array($res_rp);
				
				if(!empty($lin_rp[RazaoSocial])) {
					$lin_rp[Nome] = $lin_rp[RazaoSocial];
				}
				
				$lin[Titular] = 2;
				
				if($local_Login === $lin[LoginAbertura]){
					$lin[Titular] = 1;
				}
				
				$dados .= "\n<IdCaixa>$lin[IdCaixa]</IdCaixa>";
				$dados .= "\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				$dados .= "\n<Status><![CDATA[$lin[Status]]]></Status>";
				$dados .= "\n<CorStatus><![CDATA[$lin[CorStatus]]]></CorStatus>";
				$dados .= "\n<NomeResponsavel><![CDATA[$lin_rp[Nome]]]></NomeResponsavel>";
				$dados .= "\n<Titular><![CDATA[$lin[Titular]]]></Titular>";
				$dados .= "\n<DataAbertura><![CDATA[$lin[DataAbertura]]]></DataAbertura>";
				$dados .= "\n<LoginAbertura><![CDATA[$lin[LoginAbertura]]]></LoginAbertura>";
				$dados .= "\n<DataFechamento><![CDATA[$lin[DataFechamento]]]></DataFechamento>";
				$dados .= "\n<LoginFechamento><![CDATA[$lin[LoginFechamento]]]></LoginFechamento>";
				$dados .= "\n<FormaPagamento>";
				
				$sql_fp = "SELECT 
								CaixaFormaPagamento.IdLoja,
								CaixaFormaPagamento.IdCaixa,
								CaixaFormaPagamento.IdFormaPagamento,
								CaixaFormaPagamento.ValorAbertura
							FROM 
								CaixaFormaPagamento
							WHERE
								CaixaFormaPagamento.IdLoja = '$local_IdLoja' AND
								CaixaFormaPagamento.IdCaixa = '".$lin[IdCaixa]."'
							Order by 
								CaixaFormaPagamento.IdFormaPagamento desc";
				$res_fp = @mysql_query($sql_fp, $con);
				
				while($lin_fp = @mysql_fetch_array($res_fp)){
					$sql_cl = "SELECT 
									SUM(CaixaMovimentacaoFormaPagamento.ValorTotal) ValorCancelado
								FROM
									CaixaMovimentacao,
									CaixaMovimentacaoFormaPagamento,
									CaixaFormaPagamento 
								WHERE 
									CaixaMovimentacao.IdStatus = '0' AND 
									CaixaMovimentacao.IdLoja = CaixaMovimentacaoFormaPagamento.IdLoja AND 
									CaixaMovimentacao.IdCaixa = CaixaMovimentacaoFormaPagamento.IdCaixa AND 
									CaixaMovimentacao.IdCaixaMovimentacao = CaixaMovimentacaoFormaPagamento.IdCaixaMovimentacao AND 
									CaixaMovimentacaoFormaPagamento.IdLoja = '".$lin_fp[IdLoja]."' AND 
									CaixaMovimentacaoFormaPagamento.IdCaixa = '".$lin_fp[IdCaixa]."' AND 
									CaixaMovimentacaoFormaPagamento.IdFormaPagamento = '".$lin_fp[IdFormaPagamento]."' AND 
									CaixaMovimentacaoFormaPagamento.IdLoja = CaixaFormaPagamento.IdLoja AND 
									CaixaMovimentacaoFormaPagamento.IdCaixa = CaixaFormaPagamento.IdCaixa AND 
									CaixaMovimentacaoFormaPagamento.IdFormaPagamento = CaixaFormaPagamento.IdFormaPagamento 
								GROUP BY 
									CaixaFormaPagamento.IdFormaPagamento";
					$res_cl = @mysql_query($sql_cl, $con);
					$lin_cl = @mysql_fetch_array($res_cl);
					
					$sql_aux = "SELECT
									COUNT(*) quant
								FROM
									CaixaMovimentacao 
								WHERE 
									IdCaixa = $lin_fp[IdCaixa];";
					
					$res_aux = mysql_query($sql_aux,$con);
					$count = mysql_fetch_array($res_aux);
					if($count[quant] > 0){				
						$sql_at = "SELECT 
										(SUM(CaixaMovimentacaoFormaPagamento.ValorTotal) + CaixaFormaPagamento.ValorAbertura) ValorAtual
									FROM
										CaixaMovimentacao,
										CaixaMovimentacaoFormaPagamento,
										CaixaFormaPagamento 
									WHERE 
										CaixaMovimentacao.IdLoja = CaixaMovimentacaoFormaPagamento.IdLoja AND 
										CaixaMovimentacao.IdCaixa = CaixaMovimentacaoFormaPagamento.IdCaixa AND 
										CaixaMovimentacao.IdCaixaMovimentacao = CaixaMovimentacaoFormaPagamento.IdCaixaMovimentacao AND 
										CaixaMovimentacaoFormaPagamento.IdLoja = '".$lin_fp[IdLoja]."' AND 
										CaixaMovimentacaoFormaPagamento.IdCaixa = '".$lin_fp[IdCaixa]."' AND 
										CaixaMovimentacaoFormaPagamento.IdFormaPagamento = '".$lin_fp[IdFormaPagamento]."' AND 
										CaixaMovimentacaoFormaPagamento.IdLoja = CaixaFormaPagamento.IdLoja AND 
										CaixaMovimentacaoFormaPagamento.IdCaixa = CaixaFormaPagamento.IdCaixa AND 
										CaixaMovimentacaoFormaPagamento.IdFormaPagamento = CaixaFormaPagamento.IdFormaPagamento 
									GROUP BY 
										CaixaFormaPagamento.IdFormaPagamento";
						$res_at = @mysql_query($sql_at, $con);
						$lin_at = @mysql_fetch_array($res_at);
					}else{
						$sql_at = "SELECT 
										SUM(CaixaFormaPagamento.ValorAbertura) ValorAtual
									FROM
										CaixaFormaPagamento 
									WHERE 
										CaixaFormaPagamento.IdLoja = '".$lin_fp[IdLoja]."' AND 
										CaixaFormaPagamento.IdCaixa = '".$lin_fp[IdCaixa]."' AND 
										CaixaFormaPagamento.IdFormaPagamento = '".$lin_fp[IdFormaPagamento]."'
									GROUP BY 
										CaixaFormaPagamento.IdFormaPagamento";
						$res_at = @mysql_query($sql_at, $con);
						$lin_at = @mysql_fetch_array($res_at);					
					}
					
					$dados .= "\n<IdFormaPagamento><![CDATA[$lin_fp[IdFormaPagamento]]]></IdFormaPagamento>";
					$dados .= "\n<ValorAbertura><![CDATA[$lin_fp[ValorAbertura]]]></ValorAbertura>";
					$dados .= "\n<ValorCancelado><![CDATA[$lin_cl[ValorCancelado]]]></ValorCancelado>";
					$dados .= "\n<ValorAtual><![CDATA[$lin_at[ValorAtual]]]></ValorAtual>";
				}
				
				$dados .= "\n</FormaPagamento>";
			}
			
			$dados .= "\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_Caixa();
?>