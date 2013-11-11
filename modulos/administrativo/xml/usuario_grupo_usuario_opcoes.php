<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_UsuarioGrupoUsuarioOpcoes(){
		
		global $con;
		global $_GET;
		
		$IdLoja			 	= $_SESSION["IdLoja"];
		$Login			 	= $_GET['Login'];
		$where				= "";
		
		if($Login != ''){			$where .= " and Login='$Login'";	}
		
		$sql	=	"select
					 	IdGrupoUsuario,
					 	DescricaoGrupoUsuario
					from
					 	GrupoUsuario
					where
					 	IdLoja = $IdLoja and
						IdGrupoUsuario not in (select IdGrupoUsuario from UsuarioGrupoUsuario where IdLoja = $IdLoja $where )";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdGrupoUsuario>$lin[IdGrupoUsuario]</IdGrupoUsuario>";
			$dados	.=	"\n<DescricaoGrupoUsuario><![CDATA[$lin[DescricaoGrupoUsuario]]]></DescricaoGrupoUsuario>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_UsuarioGrupoUsuarioOpcoes();
?>
