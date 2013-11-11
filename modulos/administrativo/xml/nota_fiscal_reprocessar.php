<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_nota_fiscal_reprocessar(){
		
		global $con;
		global $_GET;
		
		$IdLoja					= $_SESSION['IdLoja'];	
		$Limit 					= $_GET['Limit'];

		$IdNotaFiscalLayout		= $_GET['IdNotaFiscalLayout'];
		$PeriodoApuracao  		= $_GET['PeriodoApuracao'];
		$IdNotaFiscal	  		= $_GET['IdNotaFiscal'];
		
		$PeriodoApuracao 		= dataConv($PeriodoApuracao,"m/Y","Y-m");
		
		$where					= "";
		
		if($IdNotaFiscalLayout != ''){	
			$where .= " and IdNotaFiscalLayout=$IdNotaFiscalLayout";	
		}
		if($PeriodoApuracao !=''){	
			$where .= " and PeriodoApuracao = '$PeriodoApuracao'";	
		}
		if($IdNotaFiscal !=''){	
			$where .= " and IdNotaFiscal = $IdNotaFiscal";	
		}
			
		$sql	=	"
					select
						DataEmissao,
						ValorTotal,
						LoginCriacao,
						DataCriacao					
					from 
						NotaFiscal
					where
						IdLoja = $IdLoja 
						$where";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		if($lin	=	@mysql_fetch_array($res)){			
			$dados	.=	"\n<DataEmissao><![CDATA[$lin[DataEmissao]]]></DataEmissao>";
			$dados	.=	"\n<ValorNotaFiscal><![CDATA[$lin[ValorTotal]]]></ValorNotaFiscal>";					
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_nota_fiscal_reprocessar();
?>
