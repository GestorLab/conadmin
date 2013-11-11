<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Estoque(){
		
		global $con;
		global $_GET;
		
		$IdLoja				= $_SESSION['IdLoja'];
		$Limit 				= $_GET['Limit'];
		$IdEstoque	 		= $_GET['IdEstoque'];
		$DescricaoEstoque  	= $_GET['DescricaoEstoque'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdEstoque != ''){	
			$where .= " and IdEstoque=$IdEstoque";	
		}
		if($DescricaoEstoque !=''){	
			$where .= " and DescricaoEstoque like '$DescricaoEstoque%'";	
		}
		
		$sql	=	"select
						IdEstoque, 
						DescricaoEstoque, 
						DataCriacao, 
						LoginCriacao, 
						DataAlteracao, 
						LoginAlteracao 
					from 
						Estoque
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
			$dados	.=	"\n<IdEstoque>$lin[IdEstoque]</IdEstoque>";
			$dados	.=	"\n<DescricaoEstoque><![CDATA[$lin[DescricaoEstoque]]]></DescricaoEstoque>";
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
	echo get_Estoque();
?>
