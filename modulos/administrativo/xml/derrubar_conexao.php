<?
	$localModulo	=	1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_derrubar_conexao(){
		global $con;
		global $_GET;
		
		$local_Login			= $_SESSION["Login"];
		$local_IdGrupoPermissao	= $_GET['IdGrupoPermissao'];
		
		header("content-type: text/xml");
		$dados	 =	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		$dados	.=	"\n<reg>";
		
		$sql = "UPDATE
					UsuarioGrupoPermissao,
					LogAcesso
				SET
					LogAcesso.Fechada = '1'
				WHERE 
					UsuarioGrupoPermissao.IdGrupoPermissao = '$local_IdGrupoPermissao' AND 
					UsuarioGrupoPermissao.Login = LogAcesso.Login AND 
					LogAcesso.Fechada = '2';";
		if(mysql_query($sql,$con)){
			$sql = "SELECT 
						COUNT(*) Qtd
					FROM
						UsuarioGrupoPermissao 
					WHERE 
						IdGrupoPermissao = '$local_IdGrupoPermissao' AND 
						Login = '$local_Login';";
			$res = @mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);
			
			$dados	.=	"\n<Atualizar><![CDATA[$lin[Qtd]]]></Atualizar>";
			$dados	.=	"\n<Deslogado><![CDATA[1]]></Deslogado>";
		} else{
			$dados	.=	"\n<Atualizar><![CDATA[0]]></Atualizar>";
			$dados	.=	"\n<Deslogado><![CDATA[0]]></Deslogado>";
		}
		
		$dados	.=	"\n</reg>";
		
		return $dados;
	}
	
	echo get_derrubar_conexao();
?>