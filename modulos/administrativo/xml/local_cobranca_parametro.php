<?
	$localModulo	=	1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_local_cobranca_parametro(){
	
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$local_IdLoja					= $_SESSION['IdLoja'];
		$IdLocalCobranca				= $_GET['IdLocalCobranca'];	
		$where							= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdLocalCobranca != ''){
			$where	.=	" and LocalCobrancaParametro.IdLocalCobranca = ".$IdLocalCobranca;
		}	
		
			$sql	=	"
					 select	
						LocalCobrancaParametro.IdLocalCobrancaParametro,
						LocalCobrancaParametro.IdLocalCobrancaLayout,				    
				     	LocalCobrancaParametro.ValorLocalCobrancaParametro				      	
	 				 from	 				 	
	 				 	LocalCobrancaParametro 						  
					 where					
						IdLoja = $local_IdLoja 											
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
			$dados	.=	"\n<IdLocalCobrancaLayout>$lin[IdLocalCobrancaLayout]</IdLocalCobrancaLayout>";
			$dados	.=	"\n<IdLocalCobrancaParametro><![CDATA[$lin[IdLocalCobrancaParametro]]]></IdLocalCobrancaParametro>";
			$dados	.=	"\n<ValorLocalCobrancaParametro><![CDATA[$lin[ValorLocalCobrancaParametro]]]></ValorLocalCobrancaParametro>";	
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_local_cobranca_parametro();
?>
