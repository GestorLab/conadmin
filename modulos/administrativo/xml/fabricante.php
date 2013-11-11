<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Fabricante(){
		
		global $con;
		global $_GET;
		
		$IdLoja					= $_SESSION['IdLoja'];	
		$Limit 					= $_GET['Limit'];
		$IdFabricante	 		= $_GET['IdFabricante'];
		$DescricaoFabricante  	= $_GET['DescricaoFabricante'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdFabricante != ''){	
			$where .= " and IdFabricante=$IdFabricante";	
		}
		if($DescricaoFabricante !=''){	
			$where .= " and DescricaoFabricante like '$DescricaoFabricante%'";	
		}
		
		$sql	=	"select
						IdFabricante, 
						DescricaoFabricante, 
						DataCriacao, 
						LoginCriacao, 
						DataAlteracao, 
						LoginAlteracao 
					from 
						Fabricante
					where
						IdLoja = $IdLoja $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdFabricante>$lin[IdFabricante]</IdFabricante>";
			$dados	.=	"\n<DescricaoFabricante><![CDATA[$lin[DescricaoFabricante]]]></DescricaoFabricante>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Fabricante();
?>
