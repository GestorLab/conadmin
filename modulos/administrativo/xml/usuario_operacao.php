<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_usuario_operacao(){
		
		global $con;
		global $_GET;
		
		$Login		= $_GET['Login'];
		$IdLoja		= $_GET['IdLoja'];
		$IdModulo	= $_GET['IdModulo'];
		
		$i=0;

		$sql	=	"select
					    UsuarioSubOperacao.IdOperacao,
					    Operacao.NomeOperacao
					from
					    UsuarioSubOperacao,
					    Operacao
					where
					    UsuarioSubOperacao.Login='$Login' and
					    UsuarioSubOperacao.IdLoja=$IdLoja and
					    UsuarioSubOperacao.IdModulo=$IdModulo and
					    UsuarioSubOperacao.IdModulo = Operacao.IdModulo and
					    UsuarioSubOperacao.IdOperacao = Operacao.IdOperacao
					group by
					    IdOperacao
					order by
						Operacao.NomeOperacao";
		$res	= @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			$IdOperacao[$i] 	= $lin[IdOperacao];
			$NomeOperacao[$i] 	= $lin[NomeOperacao];
			$i++;	
		}						
						
	/*	$sql	=	"select
					    GrupoPermissaoSubOperacao.IdOperacao,
					    Operacao.NomeOperacao
					from
					    UsuarioGrupoPermissao,
					    GrupoPermissaoSubOperacao,
					    Operacao
					where
					    UsuarioGrupoPermissao.Login = '$Login' and
					    UsuarioGrupoPermissao.IdGrupoPermissao = GrupoPermissaoSubOperacao.IdGrupoPermissao and
					    GrupoPermissaoSubOperacao.IdLoja = $IdLoja and
					    GrupoPermissaoSubOperacao.IdModulo = $IdModulo and
					    GrupoPermissaoSubOperacao.IdModulo = Operacao.IdModulo and
					    GrupoPermissaoSubOperacao.IdOperacao = Operacao.IdOperacao
					group by 
						IdOperacao
					order by
						Operacao.NomeOperacao";		
		$res	= @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			if(@!in_array($lin[IdOperacao], $IdOperacao)){
				$IdOperacao[$i] 	= $lin[IdOperacao];
				$NomeOperacao[$i] 	= $lin[NomeOperacao];
				$i++;
			}
		}*/
		
		
		if($i>0){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		
		for($ii=0;$ii<$i;$ii++){	
			$dados	.=	"\n<IdOperacao>$IdOperacao[$ii]</IdOperacao>";
			$dados	.=	"\n<NomeOperacao><![CDATA[$NomeOperacao[$ii]]]></NomeOperacao>";
		}
		
		if($i>0){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_usuario_operacao();
?>
