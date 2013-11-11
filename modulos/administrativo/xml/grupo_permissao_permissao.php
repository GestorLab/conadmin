<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_permissao_permissao(){
		
		global $con;
		global $_GET;
		
		$Login				= $_GET['Login'];
		$IdGrupoPermissao	= $_GET['IdGrupoPermissao'];
		$IdLoja				= $_GET['IdLoja'];
		$IdModulo			= $_GET['IdModulo'];
		$IdOperacao			= $_GET['IdOperacao'];
		$IdSubOperacao		= $_GET['IdSubOperacao'];
		
		if($IdGrupoPermissao != ''){
			$sql = "and GrupoPermissaoSubOperacao.IdGrupoPermissao='$IdGrupoPermissao'";		
		}

		$sql	=	"select
						GrupoPermissaoSubOperacao.IdLoja,
						Loja.DescricaoLoja,
						GrupoPermissaoSubOperacao.IdGrupoPermissao,
						GrupoPermissaoSubOperacao.IdModulo,
						Modulo.DescricaoModulo,
						GrupoPermissaoSubOperacao.IdOperacao,
						Operacao.NomeOperacao,
						GrupoPermissaoSubOperacao.IdSubOperacao,
						IdSubOperacao.DescricaoSubOperacao
					from
						GrupoPermissaoSubOperacao,
						Loja,
						Modulo,
						Operacao,
						SubOperacao,
						IdSubOperacao
					where
						GrupoPermissaoSubOperacao.IdLoja = Loja.IdLoja and
						GrupoPermissaoSubOperacao.IdModulo = Modulo.IdModulo and
						GrupoPermissaoSubOperacao.IdOperacao = Operacao.IdOperacao and
						GrupoPermissaoSubOperacao.IdSubOperacao = SubOperacao.IdSubOperacao and
						Operacao.IdModulo = Modulo.IdModulo and
						SubOperacao.IdModulo = Modulo.IdModulo and
						SubOperacao.IdOperacao = Operacao.IdOperacao and
						SubOperacao.IdSubOperacao = IdSubOperacao.IdSubOperacao	and
						GrupoPermissaoSubOperacao.IdLoja = $IdLoja $sql	
					order by
						Loja.DescricaoLoja ASC,Modulo.DescricaoModulo ASC, Operacao.NomeOperacao ASC, DescricaoSubOperacao ASC";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdLoja><![CDATA[$lin[IdLoja]]]></IdLoja>";
			$dados	.=	"\n<IdGrupoPermissao><![CDATA[$lin[IdGrupoPermissao]]]></IdGrupoPermissao>";
			$dados	.=	"\n<IdModulo><![CDATA[$lin[IdModulo]]]></IdModulo>";
			$dados	.=	"\n<IdOperacao><![CDATA[$lin[IdOperacao]]]></IdOperacao>";
			$dados	.=	"\n<IdSubOperacao><![CDATA[$lin[IdSubOperacao]]]></IdSubOperacao>";
			$dados	.=	"\n<DescricaoLoja><![CDATA[$lin[DescricaoLoja]]]></DescricaoLoja>";
			$dados	.=	"\n<DescricaoModulo><![CDATA[$lin[DescricaoModulo]]]></DescricaoModulo>";
			$dados	.=	"\n<NomeOperacao><![CDATA[$lin[NomeOperacao]]]></NomeOperacao>";
			$dados	.=	"\n<DescricaoSubOperacao><![CDATA[$lin[DescricaoSubOperacao]]]></DescricaoSubOperacao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_permissao_permissao();
?>
