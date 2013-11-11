<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function grupo_permissao_usuario(){
		
		global $con;
		global $_GET;
		
		$IdLoja				= $_SESSION['IdLoja'];
		$Login				= $_GET['Login'];
		$IdGrupoUsuario		= $_GET['IdGrupoUsuario'];
		$where				= "";
	
		if($Login != ''){
			$where = " 	and UsuarioGrupoPermissao.Login = '$Login' order by IdGrupoUsuario,DescricaoGrupoPermissao asc";
		}
		if($IdGrupoUsuario != ''){
			$where = " order by UsuarioGrupoUsuario.DataCriacao DESC limit 0,1";
		}
		
		$sql = "select
					UsuarioGrupoUsuario.IdGrupoUsuario,
					GrupoUsuario.DescricaoGrupoUsuario,
					GrupoPermissao.DescricaoGrupoPermissao,
					GrupoPermissao.IdGrupoPermissao,
					UsuarioGrupoUsuario.DataCriacao,
					UsuarioGrupoUsuario.LoginCriacao,
					Usuario.Login 
				from
					GrupoUsuario,
					GrupoPermissao,
					Usuario,
					UsuarioGrupoUsuario,
					UsuarioGrupoPermissao 
				where
					UsuarioGrupoUsuario.IdLoja = $IdLoja and
					UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and
					UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and
					UsuarioGrupoPermissao.IdGrupoPermissao = GrupoPermissao.IdGrupoPermissao and
					Usuario.Login = UsuarioGrupoUsuario.Login and
					UsuarioGrupoUsuario.Login = UsuarioGrupoPermissao.Login $where;";
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
			$dados	.=	"\n<IdGrupoPermissao>$lin[IdGrupoPermissao]</IdGrupoPermissao>";
			$dados	.=	"\n<DescricaoGrupoUsuario><![CDATA[$lin[DescricaoGrupoUsuario]]]></DescricaoGrupoUsuario>";
			$dados	.=	"\n<DescricaoGrupoPermissao><![CDATA[$lin[DescricaoGrupoPermissao]]]></DescricaoGrupoPermissao>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<Login><![CDATA[$lin[Login]]]></Login>";
		}
		
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo grupo_permissao_usuario();
?>