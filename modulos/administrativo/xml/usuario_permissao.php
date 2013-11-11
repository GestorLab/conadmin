<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_usuario_permissao(){
		
		global $con;
		global $_GET;
		
		$Login				= $_GET['Login'];
		$IdLoja				= $_GET['IdLoja'];
		
		if($Login != ''){
			$sql = "and UsuarioSubOperacao.Login like '$Login'";		
		}

		$sql	=	"select
						UsuarioSubOperacao.IdLoja,
						Loja.DescricaoLoja,
						UsuarioSubOperacao.Login,
						UsuarioSubOperacao.IdModulo,
						Modulo.DescricaoModulo,
						UsuarioSubOperacao.IdOperacao,
						Operacao.NomeOperacao,
						UsuarioSubOperacao.IdSubOperacao,
						IdSubOperacao.DescricaoSubOperacao
					from
						UsuarioSubOperacao,
						Loja,
						Modulo,
						Operacao,
						SubOperacao,
						IdSubOperacao
					where
						UsuarioSubOperacao.IdLoja = Loja.IdLoja and
						UsuarioSubOperacao.IdModulo = Modulo.IdModulo and
						UsuarioSubOperacao.IdOperacao = Operacao.IdOperacao and
						UsuarioSubOperacao.IdSubOperacao = SubOperacao.IdSubOperacao and
						Operacao.IdModulo = Modulo.IdModulo and
						SubOperacao.IdModulo = Modulo.IdModulo and
						SubOperacao.IdOperacao = Operacao.IdOperacao and
						SubOperacao.IdSubOperacao = IdSubOperacao.IdSubOperacao	and
						UsuarioSubOperacao.IdLoja = $IdLoja $sql	
					order by
						Loja.DescricaoLoja ASC,Modulo.DescricaoModulo ASC, Operacao.NomeOperacao ASC, DescricaoSubOperacao ASC;";
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
			$dados	.=	"\n<Login><![CDATA[$lin[Login]]]></Login>";
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
	echo get_usuario_permissao();
?>
