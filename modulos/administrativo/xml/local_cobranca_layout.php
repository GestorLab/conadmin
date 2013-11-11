<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_local_cobranca_layout(){
		
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$IdLocalCobrancaLayout			= $_GET['IdLocalCobrancaLayout'];
		$IdStatus					  	= $_GET['IdStatus'];
		$Nome						  	= $_GET['Nome'];
		$where							= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($Nome!='' || $IdLocalCobrancaLayout!=''){
			$where	.=	" where";
		}
		
		if($Nome !=''){	 				 
			$where  .= " DescricaoLocalCobrancaLayout like '$Nome%'";	 
		}
		if($Nome!='' && $IdLocalCobrancaLayout!=''){
			$where	.=	" and ";
		}
		
		if($IdLocalCobrancaLayout!= ''){
			$where	.=	" IdLocalCobrancaLayout = ".$IdLocalCobrancaLayout;
		}
		
		if(($Nome != '' || $IdLocalCobrancaLayout != '') && $IdStatus != ''){
			$where	.=	" and ";
		}
		
		if($where == '' && $IdStatus != ''){
			$where	.=	" where";
		}
		
		if($IdStatus != ''){
			$where	.=	" IdStatus = '$IdStatus'";
		}	
		
		$sql	=	"select
				      IdLocalCobrancaLayout,
				      DescricaoLocalCobrancaLayout
				from
				      LocalCobrancaLayout $where $Limit";
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
			$dados	.=	"\n<DescricaoLocalCobrancaLayout><![CDATA[$lin[DescricaoLocalCobrancaLayout]]]></DescricaoLocalCobrancaLayout>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_local_cobranca_layout();
?>
