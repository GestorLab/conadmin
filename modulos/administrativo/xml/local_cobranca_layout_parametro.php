<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_local_cobranca_layout_parametro(){
		
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$local_IdLoja					= $_SESSION['IdLoja'];
		$IdLocalCobrancaLayout			= $_GET['IdLocalCobrancaLayout'];
		$where							= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
					
		if($IdLocalCobrancaLayout != ''){
			$where	.=	" and LocalCobrancaLayoutParametro.IdLocalCobrancaLayout = ".$IdLocalCobrancaLayout;
		}	
		
		$sql	=	"select	
						LocalCobrancaLayoutParametro.IdLocalCobrancaParametro,
						LocalCobrancaLayoutParametro.IdLocalCobrancaLayout,
				     	LocalCobrancaLayoutParametro.DescricaoLocalCobrancaParametro,
				     	LocalCobrancaLayoutParametro.ValorLocalCobrancaParametroDefault,
				      	LocalCobrancaLayoutParametro.ObsLocalCobrancaParametro,	
						LocalCobrancaLayoutParametro.Obrigatorio			      	
	 				 from	 				 	
	 				 	LocalCobrancaLayoutParametro						  
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
			$dados	.=	"\n<IdLocalCobrancaLayout>$lin[IdLocalCobrancaLayout]</IdLocalCobrancaLayout>";
			$dados	.=	"\n<IdLocalCobrancaParametro><![CDATA[$lin[IdLocalCobrancaParametro]]]></IdLocalCobrancaParametro>";
			$dados	.=	"\n<DescricaoLocalCobrancaParametro><![CDATA[$lin[DescricaoLocalCobrancaParametro]]]></DescricaoLocalCobrancaParametro>";
			$dados	.=	"\n<ValorLocalCobrancaParametroDefault><![CDATA[$lin[ValorLocalCobrancaParametroDefault]]]></ValorLocalCobrancaParametroDefault>";
			$dados	.=	"\n<ObsLocalCobrancaParametro><![CDATA[$lin[ObsLocalCobrancaParametro]]]></ObsLocalCobrancaParametro>";
			$dados	.=	"\n<Obrigatorio><![CDATA[$lin[Obrigatorio]]]></Obrigatorio>";			
			$sql1 = "SELECT   
					 *
					FROM
						LocalCobrancaParametro
					WHERE
						IdLocalCobrancaLayout = $IdLocalCobrancaLayout";
			$res1 = mysql_query($sql1, $con);
			//"verificaLog" Verifica se tem log para evitar erro no JS.
			while($lin1 = mysql_fetch_array($res1)){
				$dados	.=	"\n<LogParametro><![CDATA[$lin1[LogParametro]]]></LogParametro>";				
				$dados	.=	"\n<verificaLog>1</verificaLog>";
				
			}
			$lin2 = mysql_fetch_array($res1);
			if($lin2[LogParametro] == ''){
					$dados	.=	"\n<verificaLog>2</verificaLog>";
				}
		}		
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_local_cobranca_layout_parametro();
?>
