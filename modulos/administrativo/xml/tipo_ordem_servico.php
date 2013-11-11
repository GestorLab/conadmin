<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_TipoOrdemServico(){
		
		global $con;
		global $_GET;
		
		$Limit 				 		= $_GET['Limit'];
		$IdLoja				 		= $_SESSION["IdLoja"];
		$IdTipoOrdemServico  		= $_GET['IdTipoOrdemServico'];
		$DescricaoTipoOrdemServico  = $_GET['DescricaoTipoOrdemServico'];
		$where							= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdTipoOrdemServico != ''){	
			$where .= " and IdTipoOrdemServico=$IdTipoOrdemServico";	
		}
		if($DescricaoTipoOrdemServico !=''){	
			$where .= " and DescricaoTipoOrdemServico like '$DescricaoTipoOrdemServico%'";	
		}
		
		$sql	=	"select
						IdLoja,
						IdTipoOrdemServico, 
						DescricaoTipoOrdemServico, 
						Cor,
						DataAlteracao, 
						LoginAlteracao
					from 
						TipoOrdemServico
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
			$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
			$dados	.=	"\n<IdTipoOrdemServico>$lin[IdTipoOrdemServico]</IdTipoOrdemServico>";
			$dados	.=	"\n<DescricaoTipoOrdemServico><![CDATA[$lin[DescricaoTipoOrdemServico]]]></DescricaoTipoOrdemServico>";
			$dados	.=	"\n<Cor><![CDATA[$lin[Cor]]]></Cor>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_TipoOrdemServico();
?>
