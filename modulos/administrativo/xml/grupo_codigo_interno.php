<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_grupo_codigo_interno(){
		
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$IdGrupoCodigoInterno			= $_GET['IdGrupoCodigoInterno'];
		$DescricaoGrupoCodigoInterno  	= $_GET['Nome'];
		$where							= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdGrupoCodigoInterno != ''){									 $where .= " and IdGrupoCodigoInterno=$IdGrupoCodigoInterno";	}
		if($DescricaoGrupoCodigoInterno !=''){								 $where  = " and DescricaoGrupoCodigoInterno like '$DescricaoGrupoCodigoInterno%'";	 }
		
		$sql	=	"select
						IdGrupoCodigoInterno,
						DescricaoGrupoCodigoInterno
					from 
						GrupoCodigoInterno 
					where 
						IdGrupoCodigoInterno != '' $where $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdGrupoCodigoInterno>$lin[IdGrupoCodigoInterno]</IdGrupoCodigoInterno>";
			$dados	.=	"\n<DescricaoGrupoCodigoInterno><![CDATA[$lin[DescricaoGrupoCodigoInterno]]]></DescricaoGrupoCodigoInterno>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_grupo_codigo_interno();
?>
