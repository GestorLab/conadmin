<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_local_cobranca_layout_parametro_default(){
		
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$local_IdLoja					= $_SESSION['IdLoja'];
		$IdLocalCobranca				= $_GET['IdLocalCobranca'];
		$where							= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
					
		if($IdLocalCobranca == ''){
			$IdLocalCobranca = 0;
		}
		$sql	=	"select	 
						IdLocalCobrancaLayout 
					from
						LocalCobranca
					where
						IdLoja = $local_IdLoja and
						IdLocalCobranca = $IdLocalCobranca";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);

		$IdLocalCobrancaLayout = $lin[IdLocalCobrancaLayout];
				
		$sql2	=	"select	
						ValorLocalCobrancaParametroDefault	      	
	 				 from	 				 	
	 				 	LocalCobrancaLayoutParametro 						  
					 where	
						IdLocalCobrancaLayout = $IdLocalCobrancaLayout and
						IdLocalCobrancaParametro = 'VisivelDigitoAgencia'";
		$res2	=	mysql_query($sql2,$con);
		if(@mysql_num_rows($res2) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin2	=	@mysql_fetch_array($res2)){
			$dados	.=	"\n<ValorLocalCobrancaParametroDefault><![CDATA[$lin2[ValorLocalCobrancaParametroDefault]]]></ValorLocalCobrancaParametroDefault>";
		}
		if(mysql_num_rows($res2) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_local_cobranca_layout_parametro_default();
?>
