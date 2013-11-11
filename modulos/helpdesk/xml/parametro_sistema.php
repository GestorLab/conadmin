<?
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../rotinas/verifica.php');
	
	function get_parametro_sistema(){
		
		global $con;
		global $_GET;
		
		$Limit 						= $_GET['Limit'];
		$IdGrupoParametroSistema	= $_GET['IdGrupoParametroSistema'];
		$IdParametroSistema 		= $_GET['IdParametroSistema'];
		$where						= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdGrupoParametroSistema	!= ''){	$where .= " and GrupoParametroSistema.IdGrupoParametroSistema=$IdGrupoParametroSistema";	}
		if($IdParametroSistema		!= ''){	$where .= " and ParametroSistema.IdParametroSistema=$IdParametroSistema";	}
		
		$sql	=	"select
					     ParametroSistema.IdParametroSistema,
						 ParametroSistema.ValorParametroSistema
					from 
					     ParametroSistema,
					     GrupoParametroSistema
					where
					     GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema $where $Limit";
					     
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			
			if($IdGrupoParametroSistema == '128'){
				$temp	=	substr($lin[IdParametroSistema],0,1);
				
				$lin[Cor]	=	getParametroSistema(147,$temp);
			}
			
			$dados	.=	"\n<IdParametroSistema>$lin[IdParametroSistema]</IdParametroSistema>";
			$dados	.=	"\n<ValorParametroSistema><![CDATA[$lin[ValorParametroSistema]]]></ValorParametroSistema>";
			$dados	.=	"\n<Cor><![CDATA[$lin[Cor]]]></Cor>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_parametro_sistema();
?>
