<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_GrupoProduto(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja					= $_SESSION["IdLoja"];
		$IdGrupoProduto	 		= $_GET['IdGrupoProduto'];
		$DescricaoGrupoProduto  = $_GET['DescricaoGrupoProduto'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdGrupoProduto != ''){	
			$where .= " and IdGrupoProduto=$IdGrupoProduto";	
		}
		if($DescricaoGrupoProduto !=''){	
			$where .= " and DescricaoGrupoProduto like '$DescricaoGrupoProduto%'";	
		}
		
		$sql	=	"select
						IdGrupoProduto, 
						DescricaoGrupoProduto, 
						DataCriacao, 
						LoginCriacao, 
						DataAlteracao, 
						LoginAlteracao 
					from 
						GrupoProduto
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
			$dados	.=	"\n<IdGrupoProduto>$lin[IdGrupoProduto]</IdGrupoProduto>";
			$dados	.=	"\n<DescricaoGrupoProduto><![CDATA[$lin[DescricaoGrupoProduto]]]></DescricaoGrupoProduto>";
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
	echo get_GrupoProduto();
?>
