<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_usuario_grupo_permissao(){
		
		global $con;
		global $_GET;
		
		$Login				= $_GET['Login'];
		$IdGrupoPermissao	= $_GET['IdGrupoPermissao'];
		
		if($IdGrupoPermissao != ''){
			$sql = "and GrupoPermissao.IdGrupoPermissao = $IdGrupoPermissao";
		}else{
			$sql = "";
		}
		
		$sql	=	"select
					    UsuarioGrupoPermissao.IdGrupoPermissao,
					    GrupoPermissao.DescricaoGrupoPermissao,
					    UsuarioGrupoPermissao.LoginCriacao,
					    UsuarioGrupoPermissao.DataCriacao
					from
					    UsuarioGrupoPermissao,
					    GrupoPermissao
					where
					    UsuarioGrupoPermissao.Login='$Login' and
					    GrupoPermissao.IdGrupoPermissao = UsuarioGrupoPermissao.IdGrupoPermissao $sql";
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
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";		
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";		
		}
		
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_usuario_grupo_permissao();
?>
