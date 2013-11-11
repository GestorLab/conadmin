<?
	$localModulo	=	1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_servico_parametro_nota_fiscal(){
	
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$local_IdLoja					= $_SESSION['IdLoja'];
		$IdServico						= $_GET['IdServico'];	
		$IdNotaFiscalLayout				= $_GET['IdNotaFiscalLayout'];
		$where							= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdServico != ''){
			$where	.=	" and ServicoNotaFiscalLayoutParametro.IdServico = ".$IdServico;
		}	
		
		if($IdNotaFiscalLayout != ''){
			$where	.=	" and ServicoNotaFiscalLayoutParametro.IdNotaFiscalLayout = ".$IdNotaFiscalLayout;
		}	
		
		$sql	=	"
					 select				
						ServicoNotaFiscalLayoutParametro.IdNotaFiscalLayout,				    
						ServicoNotaFiscalLayoutParametro.IdNotaFiscalLayoutParametro,
				     	ServicoNotaFiscalLayoutParametro.Valor				      	
	 				 from	 				 	
	 				 	ServicoNotaFiscalLayoutParametro
					 where	
					 	ServicoNotaFiscalLayoutParametro.IdLoja = $local_IdLoja
					    $where $Limit;";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdNotaFiscalLayout><![CDATA[$lin[IdNotaFiscalLayout]]]></IdNotaFiscalLayout>";
			$dados	.=	"\n<IdNotaFiscalLayoutParametro><![CDATA[$lin[IdNotaFiscalLayoutParametro]]]></IdNotaFiscalLayoutParametro>";	
			$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";	
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_servico_parametro_nota_fiscal();
?>
