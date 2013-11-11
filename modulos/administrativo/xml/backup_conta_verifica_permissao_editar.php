<?
	$localModulo	= 1;
	
	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_BackupContaVerificaPermissaoEditar(){
		global $con;
		global $_GET;
		
		$Login			= $_SESSION["Login"];
		$IdLoja			= $_SESSION["IdLoja"];
		
		$sql = "select 
					UsuarioGrupoPermissao.Login,
					UsuarioGrupoPermissao.IdGrupoPermissao,
					GrupoPermissaoSubOperacao.IdSubOperacao,
					GrupoPermissaoSubOperacao.IdOperacao 
				from
					UsuarioGrupoPermissao,
					GrupoPermissaoSubOperacao 
				where 
					GrupoPermissaoSubOperacao.IdLoja = $IdLoja and
					UsuarioGrupoPermissao.Login = '$Login' and
					GrupoPermissaoSubOperacao.IdGrupoPermissao = UsuarioGrupoPermissao.IdGrupoPermissao and
					GrupoPermissaoSubOperacao.IdOperacao = 172 and
					GrupoPermissaoSubOperacao.IdSubOperacao = 'U'";
		$res = mysql_query($sql,$con);
		
		if(mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			$type = "text";
			
			$dados .= "\n<Type><![CDATA[$type]]></Type>";
			$dados .= "\n</reg>";
			return $dados;
		} else{
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			$type = "password";
			
			$dados .= "\n<Type><![CDATA[$type]]></Type>";
			$dados .= "\n</reg>";
			return $dados;
		}
	}
	
	echo get_BackupContaVerificaPermissaoEditar();
?>