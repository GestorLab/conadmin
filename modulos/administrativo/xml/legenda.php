<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Legenda(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$mes					= $_GET['mes'];
		$ano					= $_GET['ano'];
		$where					=	"";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($mes!='' && $ano!=''){			
			$where .= " and substr(DatasEspeciais.Data,1,7) = '$ano-$mes'";
		}
		
		$sql	=	"select 
						ValorParametroSistema 
					from 
						ParametroSistema,
						DatasEspeciais 
					where 
						IdGrupoParametroSistema=52 and 
						DatasEspeciais.TipoData = ParametroSistema.IdParametroSistema $where
					group by 
						IdParametroSistema 
					order by 
						ValorParametroSistema ASC $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$lin[ValorParametroSistema]	=	explode("\n",$lin[ValorParametroSistema]);
			
			$lin[Descricao]	=	$lin[ValorParametroSistema][0];
			$lin[Cor]		=	$lin[ValorParametroSistema][1];
		
			$dados	.=	"\n<DescricaoData><![CDATA[$lin[Descricao]]]></DescricaoData>";
			$dados	.=	"\n<Cor><![CDATA[$lin[Cor]]]></Cor>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Legenda();
?>
