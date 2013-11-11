<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_usuario_sub_operacao(){
		
		global $con;
		global $_GET;
		
		$Login			= $_GET['Login'];
		$IdLoja			= $_GET['IdLoja'];
		$IdModulo		= $_GET['IdModulo'];
		$IdOperacao		= $_GET['IdOperacao'];
		$IdSubOperacao	= $_GET['IdSubOperacao'];
		
		if($IdSubOperacao != ''){
			$sql1 = "and UsuarioSubOperacao.IdSubOperacao='$IdSubOperacao'";
			$sql2 = "and GrupoPermissaoSubOperacao.IdSubOperacao='$IdSubOperacao'";
		}else{
			$sql1 = "";
			$sql2 = "";
		}

		$i=0;
		$IdSubOperacao[$i] = NULL;
		
		$sql	=	"select 
						UsuarioSubOperacao.IdSubOperacao,
						IdSubOperacao.DescricaoSubOperacao,
						UsuarioSubOperacao.DataCriacao,
						UsuarioSubOperacao.LoginCriacao
					from 
						UsuarioSubOperacao,
						IdSubOperacao
					where 
						UsuarioSubOperacao.Login='$Login' and 
						UsuarioSubOperacao.IdLoja=$IdLoja and 
						UsuarioSubOperacao.IdModulo=$IdModulo and 
						UsuarioSubOperacao.IdOperacao=$IdOperacao and
						UsuarioSubOperacao.IdSubOperacao = IdSubOperacao.IdSubOperacao $sql1";
		$res	= @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			$IdSubOperacao[$i] 			= $lin[IdSubOperacao];
			$DescricaoSubOperacao[$i] 	= $lin[DescricaoSubOperacao];
			$DataCriacao[$i] 			= $lin[DataCriacao];
			$LoginCriacao[$i] 			= $lin[LoginCriacao];
			$i++;	
		}

		$sql	=	"select 
						GrupoPermissaoSubOperacao.IdSubOperacao,
						IdSubOperacao.DescricaoSubOperacao,
						GrupoPermissaoSubOperacao.DataCriacao,
						GrupoPermissaoSubOperacao.LoginCriacao
					from 
						UsuarioGrupoPermissao, 
						GrupoPermissaoSubOperacao,
						IdSubOperacao
					where 
						UsuarioGrupoPermissao.Login = '$Login' and 
						GrupoPermissaoSubOperacao.IdGrupoPermissao = UsuarioGrupoPermissao.IdGrupoPermissao and 
						GrupoPermissaoSubOperacao.IdLoja = $IdLoja and 
						GrupoPermissaoSubOperacao.IdModulo= $IdModulo and 
						GrupoPermissaoSubOperacao.IdOperacao= $IdOperacao and
						GrupoPermissaoSubOperacao.IdSubOperacao = IdSubOperacao.IdSubOperacao $sql2";
		$res	= @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			if(@!in_array($lin[IdSubOperacao], $IdSubOperacao)){
				$IdSubOperacao[$i] 			= $lin[IdSubOperacao];
				$DescricaoSubOperacao[$i] 	= $lin[DescricaoSubOperacao];
				$DataCriacao[$i] 			= $lin[DataCriacao];
				$LoginCriacao[$i] 			= $lin[LoginCriacao];
				$i++;
			}
		}

		if($i>0){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		
		for($ii=0;$ii<$i;$ii++){				
			$dados	.=	"\n<IdSubOperacao>$IdSubOperacao[$ii]</IdSubOperacao>";
			$dados	.=	"\n<DescricaoSubOperacao><![CDATA[$DescricaoSubOperacao[$ii]]]></DescricaoSubOperacao>";
			$dados	.=	"\n<DataCriacao><![CDATA[$DataCriacao[$ii]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$LoginCriacao[$ii]]]></LoginCriacao>";
		}
		
		if($i>0){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_usuario_sub_operacao();
?>
