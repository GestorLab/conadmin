<?
	$localModulo	=	1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_nota_fiscal_parametro(){
	
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$local_IdLoja					= $_SESSION['IdLoja'];
		$IdNotaFiscalTipo				= $_GET['IdNotaFiscalTipo'];	
		$IdNotaFiscalLayout				= $_GET['IdNotaFiscalLayout'];
		$where							= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdNotaFiscalTipo != ''){
			$where	.=	" and NotaFiscalTipoParametro.IdNotaFiscalTipo = ".$IdNotaFiscalTipo;
		}	
		
		if($IdNotaFiscalLayout != ''){
			$where	.=	" and NotaFiscalTipoParametro.IdNotaFiscalLayout = ".$IdNotaFiscalLayout;
		}	
		
		$sql	=	"
					 select	
						NotaFiscalTipoParametro.IdNotaFiscalLayoutParametro,
						NotaFiscalTipoParametro.IdNotaFiscalLayout,				    
				     	NotaFiscalTipoParametro.Valor				      	
	 				 from	 				 	
	 				 	NotaFiscalTipoParametro
					 where	
					 	1											
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
			$dados	.=	"\n<IdNotaFiscalLayoutParametro>$lin[IdNotaFiscalLayoutParametro]</IdNotaFiscalLayoutParametro>";
			$dados	.=	"\n<IdNotaFiscalLayout><![CDATA[$lin[IdNotaFiscalLayout]]]></IdNotaFiscalLayout>";
			$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";	
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_nota_fiscal_parametro();
?>
