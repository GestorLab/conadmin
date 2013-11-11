<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_grupo_parametro_sistema(){
		
		global $con;
		global $_GET;
		
		$Limit 								= $_GET['Limit'];
		$IdGrupoParametroSistema			= $_GET['IdGrupoParametroSistema'];
		$DescricaoGrupoParametroSistema  	= $_GET['Nome'];
		$where1							= "";
		$where2							= "";
		$where							= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdGrupoParametroSistema!='' ||  $DescricaoGrupoParametroSistema!=''){	 $where   =	"where";	}
		if($IdGrupoParametroSistema != ''){											 $where1 .= "IdGrupoParametroSistema=$IdGrupoParametroSistema";	}
		if($IdGrupoParametroSistema!='' &&  $DescricaoGrupoParametroSistema!=''){	 $where1 .=  "and";	}
		if($DescricaoGrupoParametroSistema !=''){									 $where2  = "DescricaoGrupoParametroSistema like '$DescricaoGrupoParametroSistema%'";	 }
		
		$sql	=	"select
						IdGrupoParametroSistema,
						DescricaoGrupoParametroSistema
					from 
						GrupoParametroSistema $where $where1 $where2 $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdGrupoParametroSistema>$lin[IdGrupoParametroSistema]</IdGrupoParametroSistema>";
			$dados	.=	"\n<DescricaoGrupoParametroSistema><![CDATA[$lin[DescricaoGrupoParametroSistema]]]></DescricaoGrupoParametroSistema>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_grupo_parametro_sistema();
?>
