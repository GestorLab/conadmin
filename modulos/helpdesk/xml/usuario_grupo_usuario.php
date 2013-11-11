<?
	include ('../../../files/conecta.php');
	include ('../../../files/conecta_cntsistemas.php');
	include ('../../../files/funcoes.php');
	include ('../rotinas/verifica.php');
	
	function get_usuario_grupo_usuario(){
		
		global $conCNT;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$IdGrupoUsuario				  	= $_GET['IdGrupoUsuario'];
		$where						  	= "";
		
		if($IdGrupoUsuario !=''){   $where .= " and UsuarioGrupoUsuario.IdGrupoUsuario = '$IdGrupoUsuario'";		}	
		
		$sql	=	"select 
						UsuarioGrupoUsuario.IdGrupoUsuario,
						GrupoUsuario.DescricaoGrupoUsuario,
						Usuario.Login,
						Pessoa.Nome,
						UsuarioGrupoUsuario.DataCriacao,
						UsuarioGrupoUsuario.LoginCriacao
					from 
						UsuarioGrupoUsuario, 
						GrupoUsuario, 
						Usuario, 
						Pessoa 
					where 
						UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
						UsuarioGrupoUsuario.Login = Usuario.Login and 
						UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
						Usuario.IdPessoa = Pessoa.IdPessoa and 
						Pessoa.TipoUsuario = 1 and
						Usuario.IdStatus = 1 $where 
					order by
						Usuario.Login $Limit";
		$res	=	mysql_query($sql,$conCNT);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<Login>$lin[Login]</Login>";
			$dados	.=	"\n<NomeUsuario><![CDATA[$lin[Nome]]]></NomeUsuario>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_usuario_grupo_usuario();
?>
