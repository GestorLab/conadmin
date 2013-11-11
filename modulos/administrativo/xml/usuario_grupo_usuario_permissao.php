<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_usuario_grupo_usuario_permissao(){
		
		global $con;
		global $_GET;
		
		$IdLoja				= $_SESSION['IdLoja'];
		$Login				= $_GET['Login'];
		$IdGrupoUsuario		= $_GET['IdGrupoUsuario'];
		$where				= "";
	
		if($Login != ''){
			$where = " and Login = '$Login'";
		}
		if($IdGrupoUsuario != ''){
			$where = " order by UsuarioGrupoUsuario.DataCriacao DESC limit 0,1";
		}
		
		$sql	=	"select 
						UsuarioGrupoUsuario.IdGrupoUsuario,
						GrupoUsuario.DescricaoGrupoUsuario,
						UsuarioGrupoUsuario.DataCriacao,
						UsuarioGrupoUsuario.LoginCriacao
					from 
						GrupoUsuario,
						UsuarioGrupoUsuario
					where   
						UsuarioGrupoUsuario.IdLoja = $IdLoja and
						UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and
						UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario $where;";
		$res	= @mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		
		while($lin = @mysql_fetch_array($res)){
			$dados	.=	"\n<IdGrupoUsuario>$lin[IdGrupoUsuario]</IdGrupoUsuario>";
			$dados	.=	"\n<DescricaoGrupoUsuario><![CDATA[$lin[DescricaoGrupoUsuario]]]></DescricaoGrupoUsuario>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
		}
		
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_usuario_grupo_usuario_permissao();
?>
