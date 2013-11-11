<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_usuario_grupo_permissao_opcoes(){
		
		global $con;
		global $_GET;
		
		$Login				= $_GET['Login'];
		$LoginEditor		= $_GET['LoginEditor'];
		
		$sql	=	"select
					    GrupoPermissao.IdGrupoPermissao,
					    GrupoPermissao.DescricaoGrupoPermissao
					from
					    GrupoPermissao
					where
					    GrupoPermissao.IdGrupoPermissao not in (select UsuarioGrupoPermissao.IdGrupoPermissao from UsuarioGrupoPermissao where UsuarioGrupoPermissao.Login='$Login')";
		$res	= @mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		
		while($lin = @mysql_fetch_array($res)){
			$dados	.=	"\n<IdGrupoPermissao>$lin[IdGrupoPermissao]</IdGrupoPermissao>";
			$dados	.=	"\n<DescricaoGrupoPermissao><![CDATA[$lin[DescricaoGrupoPermissao]]]></DescricaoGrupoPermissao>";		
		}
		
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_usuario_grupo_permissao_opcoes();
?>
