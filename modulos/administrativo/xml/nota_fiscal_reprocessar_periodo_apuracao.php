<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_NotaFiscalReprocessarPeriodoApuracao(){
		
		global $con;
		global $_GET;
		
		$IdLoja					= $_SESSION['IdLoja'];	
		$Limit 					= $_GET['Limit'];
		$IdNotaFiscalLayout		= $_GET['IdNotaFiscalLayout'];		
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdNotaFiscalLayout != ''){	
			$where .= " and IdNotaFiscalLayout=$IdNotaFiscalLayout";	
		}		
		
		$sql	=	"select
						distinct
						PeriodoApuracao
					from
						NotaFiscal
					where
						IdLoja = $IdLoja 
						$where						
					order by
						PeriodoApuracao
					";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
				
		while($lin	=	@mysql_fetch_array($res)){				
			$lin[PeriodoApuracao] = dataConv($lin[PeriodoApuracao],"Y-m","m/Y");
			$dados	.=	"\n<PeriodoApuracao><![CDATA[$lin[PeriodoApuracao]]]></PeriodoApuracao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_NotaFiscalReprocessarPeriodoApuracao();
?>
