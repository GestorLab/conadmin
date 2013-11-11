<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_nota_fiscal_layout_parametro(){
		
		global $con;
		global $_GET;
		
		$IdLoja					= $_SESSION['IdLoja'];	
		$Limit 					= $_GET['Limit'];
		$IdNotaFiscalTipo		= $_GET['IdNotaFiscalTipo'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdNotaFiscalTipo != ''){	
			$where .= " and NotaFiscalTipo.IdNotaFiscalTipo=$IdNotaFiscalTipo";	
		}
		
		$sql	=	"select 
						NotaFiscalTipo.IdNotaFiscalTipo,
						NotaFiscalLayoutParametro.IdNotaFiscalLayout,
						NotaFiscalLayoutParametro.IdNotaFiscalLayoutParametro,
						NotaFiscalLayoutParametro.DescricaoParametro,	
						NotaFiscalLayoutParametro.Destino,
						NotaFiscalLayoutParametro.ValorDefault,
						NotaFiscalLayoutParametro.OpcaoValor
					from
						NotaFiscalTipo,			
						NotaFiscalLayoutParametro
					where
						NotaFiscalTipo.IdLoja = $IdLoja and
						NotaFiscalTipo.IdNotaFiscalLayout = NotaFiscalLayoutParametro.IdNotaFiscalLayout and
						NotaFiscalLayoutParametro.Destino like ''				
					    $where $Limit";				
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdNotaFiscalTipo>$lin[IdNotaFiscalTipo]</IdNotaFiscalTipo>";
			$dados	.=	"\n<IdNotaFiscalLayout>$lin[IdNotaFiscalLayout]</IdNotaFiscalLayout>";
			$dados	.=	"\n<IdNotaFiscalLayoutParametro><![CDATA[$lin[IdNotaFiscalLayoutParametro]]]></IdNotaFiscalLayoutParametro>";
			$dados	.=	"\n<DescricaoParametro><![CDATA[$lin[DescricaoParametro]]]></DescricaoParametro>";
			$dados	.=	"\n<Destino><![CDATA[$lin[Destino]]]></Destino>";
			$dados	.=	"\n<ValorDefault><![CDATA[$lin[ValorDefault]]]></ValorDefault>";		
			$dados	.=	"\n<OpcaoValor><![CDATA[$lin[OpcaoValor]]]></OpcaoValor>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_nota_fiscal_layout_parametro();
?>
