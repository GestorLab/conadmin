<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_SubGrupoProduto(){
		
		global $con;
		global $_GET;
		
		$Limit 						= $_GET['Limit'];
		$IdLoja						= $_SESSION["IdLoja"];
		$IdGrupoProduto	 			= $_GET['IdGrupoProduto'];
		$IdSubGrupoProduto	 		= $_GET['IdSubGrupoProduto'];
		$DescricaoGrupoProduto 		= $_GET['DescricaoGrupoProduto'];
		$DescricaoSubGrupoProduto 	= $_GET['DescricaoSubGrupoProduto'];
		$where					= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdGrupoProduto != ''){	
			$where .= " and SubGrupoProduto.IdGrupoProduto=$IdGrupoProduto";	
		}
		if($IdSubGrupoProduto != ''){	
			$where .= " and SubGrupoProduto.IdSubGrupoProduto=$IdSubGrupoProduto";	
		}
		if($DescricaoGrupoProduto !=''){	
			$where .= " and DescricaoGrupoProduto like '$DescricaoGrupoProduto%'";	
		}
		if($DescricaoSubGrupoProduto !=''){	
			$where .= " and DescricaoSubGrupoProduto like '$DescricaoSubGrupoProduto%'";	
		}
		
		$sql	=	"select
						SubGrupoProduto.IdGrupoProduto, 
						DescricaoGrupoProduto, 
						SubGrupoProduto.IdSubGrupoProduto,
						DescricaoSubGrupoProduto,
						SubGrupoProduto.DataCriacao, 
						SubGrupoProduto.LoginCriacao, 
						SubGrupoProduto.DataAlteracao, 
						SubGrupoProduto.LoginAlteracao 
					from 
						GrupoProduto,
						SubGrupoProduto
					where
						SubGrupoProduto.IdLoja = $IdLoja and
						SubGrupoProduto.IdLoja = GrupoProduto.IdLoja and
						SubGrupoProduto.IdGrupoProduto = GrupoProduto.IdGrupoProduto $where $Limit";
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
			$dados	.=	"\n<IdSubGrupoProduto>$lin[IdSubGrupoProduto]</IdSubGrupoProduto>";
			$dados	.=	"\n<DescricaoSubGrupoProduto><![CDATA[$lin[DescricaoSubGrupoProduto]]]></DescricaoSubGrupoProduto>";
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
	echo get_SubGrupoProduto();
?>
