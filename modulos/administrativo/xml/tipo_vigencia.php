<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ContratoTipoVigencia(){
		
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$IdLoja					 		= $_SESSION["IdLoja"];
		$IdContratoTipoVigencia	 		= $_GET['IdContratoTipoVigencia'];
		$DescricaoContratoTipoVigencia  = $_GET['DescricaoContratoTipoVigencia'];
		$where							= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdContratoTipoVigencia != ''){	
			$where .= " and IdContratoTipoVigencia=$IdContratoTipoVigencia";	
		}
		if($DescricaoContratoTipoVigencia !=''){	
			$where .= " and DescricaoContratoTipoVigencia like '$DescricaoContratoTipoVigencia%'";	
		}
		
		$sql	=	"select
						IdContratoTipoVigencia, 
						DescricaoContratoTipoVigencia, 
						Isento,
						DataCriacao, 
						LoginCriacao, 
						DataAlteracao, 
						LoginAlteracao 
					from 
						ContratoTipoVigencia
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
			$dados	.=	"\n<IdContratoTipoVigencia>$lin[IdContratoTipoVigencia]</IdContratoTipoVigencia>";
			$dados	.=	"\n<DescricaoContratoTipoVigencia><![CDATA[$lin[DescricaoContratoTipoVigencia]]]></DescricaoContratoTipoVigencia>";
			$dados	.=	"\n<Isento><![CDATA[$lin[Isento]]]></Isento>";
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
	echo get_ContratoTipoVigencia();
?>
