<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_usuario_sub_operacao_opcoes(){
		
		global $con;
		global $_GET;
		
		$Login				= $_GET['Login'];
		$LoginEditor		= $_GET['LoginEditor'];
		$IdLoja				= $_GET['IdLoja'];
		$IdModulo			= $_GET['IdModulo'];
		$IdOperacao			= $_GET['IdOperacao'];
		
		$i=0;
		$permissao	=	true;
		$IdSubOperacao[$i] = NULL;
		
		$sql	=	"select
						distinct
					    IdSubOperacao.IdSubOperacao,
					    IdSubOperacao.DescricaoSubOperacao
					from
					    Operacao,
					    IdSubOperacao,
						SubOperacao
					where
					    Operacao.IdModulo=$IdModulo and
					    Operacao.IdOperacao=$IdOperacao and
					    Operacao.IdOperacao=SubOperacao.IdOperacao and
					    SubOperacao.IdSubOperacao = IdSubOperacao.IdSubOperacao and
					    SubOperacao.IdSubOperacao 
							not in 
								(select 
									GrupoPermissaoSubOperacao.IdSubOperacao 
								from 
									UsuarioGrupoPermissao, 
									GrupoPermissaoSubOperacao 
								where 
									UsuarioGrupoPermissao.Login='$Login' and 
									GrupoPermissaoSubOperacao.IdGrupoPermissao = UsuarioGrupoPermissao.IdGrupoPermissao and 
									GrupoPermissaoSubOperacao.IdLoja=$IdLoja and 
									GrupoPermissaoSubOperacao.IdModulo=$IdModulo and 
									GrupoPermissaoSubOperacao.IdOperacao=$IdOperacao) and
						SubOperacao.IdSubOperacao 
							not in 
								(select 
									UsuarioSubOperacao.IdSubOperacao 
								from 
									UsuarioSubOperacao, 
									Operacao 
								where 
									UsuarioSubOperacao.Login='$Login' and 
									UsuarioSubOperacao.IdLoja=$IdLoja and 
									UsuarioSubOperacao.IdModulo=$IdModulo and 
									UsuarioSubOperacao.IdOperacao=$IdOperacao and 
									UsuarioSubOperacao.IdModulo = Operacao.IdModulo and 
									UsuarioSubOperacao.IdOperacao = Operacao.IdOperacao);";
		$res	= @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			$IdSubOperacao[$i] 			= $lin[IdSubOperacao];
			$DescricaoSubOperacao[$i] 	= $lin[DescricaoSubOperacao];
			$i++;	
		}

		$sql	=	"select
					    GrupoPermissaoSubOperacao.IdSubOperacao,
					    IdSubOperacao.DescricaoSubOperacao
					from
					    UsuarioGrupoPermissao,
					    GrupoPermissaoSubOperacao,
					    IdSubOperacao
					where
					    GrupoPermissaoSubOperacao.IdGrupoPermissao = UsuarioGrupoPermissao.IdGrupoPermissao and
					    GrupoPermissaoSubOperacao.IdLoja = $IdLoja and
					    GrupoPermissaoSubOperacao.IdModulo= $IdModulo and
					    GrupoPermissaoSubOperacao.IdOperacao= $IdOperacao and
					    GrupoPermissaoSubOperacao.IdSubOperacao = IdSubOperacao.IdSubOperacao and
					    GrupoPermissaoSubOperacao.IdSubOperacao 
							not in 
								(select 
									GrupoPermissaoSubOperacao.IdSubOperacao 
								from 
									UsuarioGrupoPermissao, 
									GrupoPermissaoSubOperacao 
								where 
									UsuarioGrupoPermissao.Login = '$Login' and 
									GrupoPermissaoSubOperacao.IdGrupoPermissao = UsuarioGrupoPermissao.IdGrupoPermissao and 
									GrupoPermissaoSubOperacao.IdLoja = $IdLoja and 
									GrupoPermissaoSubOperacao.IdModulo= $IdModulo and 
									GrupoPermissaoSubOperacao.IdOperacao= $IdOperacao) and
						GrupoPermissaoSubOperacao.IdSubOperacao 
							not in 
								(select 
									UsuarioSubOperacao.IdSubOperacao 
								from 
									UsuarioSubOperacao, 
									Operacao 
								where 
									UsuarioSubOperacao.Login='$Login' and 
									UsuarioSubOperacao.IdLoja=$IdLoja and 
									UsuarioSubOperacao.IdModulo=$IdModulo and 
									UsuarioSubOperacao.IdOperacao=$IdOperacao and 
									UsuarioSubOperacao.IdModulo = Operacao.IdModulo and 
									UsuarioSubOperacao.IdOperacao = Operacao.IdOperacao);";
		$res	= @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			if(@!in_array($lin[IdSubOperacao], $IdSubOperacao)){
				$IdSubOperacao[$i] 			= $lin[IdSubOperacao];
				$DescricaoSubOperacao[$i] 	= $lin[DescricaoSubOperacao];
				$permissao	=	false;
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
		}
		
		if($i>0){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_usuario_sub_operacao_opcoes();
?>
