<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_FormaPagamento(){
		global $con;
		global $_GET;
		
		$IdLoja						= $_SESSION['IdLoja'];	
		$Limit 						= $_GET['Limit'];
		$IdFormaPagamento	 		= $_GET['IdFormaPagamento'];
		$DescricaoFormaPagamento  	= $_GET['DescricaoFormaPagamento'];
		$where						= "";
		
		if($Limit != ''){
			$Limit = "limit 0, $Limit";
		}
		
		if($IdFormaPagamento != ''){	
			$where .= " and IdFormaPagamento=$IdFormaPagamento";	
		}
		
		if($DescricaoFormaPagamento !=''){	
			$where .= " and DescricaoFormaPagamento like '$DescricaoFormaPagamento%'";	
		}
		
		$sql = "select
					IdFormaPagamento, 
					DescricaoFormaPagamento, 
					DataCriacao, 
					LoginCriacao, 
					DataAlteracao, 
					LoginAlteracao 
				from 
					FormaPagamento
				where
					IdLoja = $IdLoja 
					$where 
				$Limit";
		$res = @mysql_query($sql,$con);
		if(@mysql_num_rows($res) > 0){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin	=	@mysql_fetch_array($res)){
				$dados	.=	"\n<IdFormaPagamento>$lin[IdFormaPagamento]</IdFormaPagamento>";
				$dados	.=	"\n<DescricaoFormaPagamento><![CDATA[$lin[DescricaoFormaPagamento]]]></DescricaoFormaPagamento>";
				$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
				$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
				$dados	.=	"\n<FormaPagamentoParcela>";
				
				$sql_fpp = "SELECT 
								IdFormaPagamento,
								QtdParcela,
								PercentualJurosMes
							FROM
								FormaPagamentoParcela
							WHERE
								IdLoja = '$IdLoja' AND
								IdFormaPagamento = '$lin[IdFormaPagamento]';";
				$res_fpp = @mysql_query($sql_fpp, $con);
				
				while($lin_fpp = @mysql_fetch_array($res_fpp)){
					$sql_cx_fpp = "SELECT 
										IdCaixaMovimentacao
									FROM 
										CaixaMovimentacaoFormaPagamento 
									WHERE 
										IdFormaPagamento = '$lin_fpp[IdFormaPagamento]' AND 
										QtdParcelas = '$lin_fpp[QtdParcela]'";
					$res_cx_fpp = @mysql_query($sql_cx_fpp,$con);
					
					if(@mysql_num_rows($res_cx_fpp) == 0){
						$lin_fpp[ExcluirParcela] = 1;
					} else {
						$lin_fpp[ExcluirParcela] = 2;
					}
					
					$dados	.=	"\n<QtdParcela><![CDATA[$lin_fpp[QtdParcela]]]></QtdParcela>";
					$dados	.=	"\n<PercentualJurosMes><![CDATA[$lin_fpp[PercentualJurosMes]]]></PercentualJurosMes>";
					$dados	.=	"\n<ExcluirParcela><![CDATA[$lin_fpp[ExcluirParcela]]]></ExcluirParcela>";
				}
				
				$dados	.=	"\n</FormaPagamentoParcela>";
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_FormaPagamento();
?>