<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_usuario_sub_operacao(){
		global $con;
		global $_GET;
		
		$IdGrupoPermissao	= $_GET['IdGrupoPermissao'];
		$IdLoja				= $_GET['IdLoja'];
		$IdModulo			= $_GET['IdModulo'];
		$IdOperacao			= $_GET['IdOperacao'];
		$IdSubOperacao		= $_GET['IdSubOperacao'];
		
		if($IdSubOperacao != ''){
			$where = " AND GrupoPermissaoSubOperacao.IdSubOperacao = '$IdSubOperacao' ";		
		} else{
			$where = "";
		}

		$sql = "SELECT 
					IdSubOperacao.IdSubOperacao,
					IdSubOperacao.DescricaoSubOperacao,
					GrupoPermissaoSubOperacao.DataCriacao,
					GrupoPermissaoSubOperacao.LoginCriacao 
				FROM
					GrupoPermissaoSubOperacao,
					IdSubOperacao 
				WHERE 
					GrupoPermissaoSubOperacao.IdGrupoPermissao = $IdGrupoPermissao AND 
					GrupoPermissaoSubOperacao.IdLoja = $IdLoja AND 
					GrupoPermissaoSubOperacao.IdModulo = $IdModulo AND 
					GrupoPermissaoSubOperacao.IdOperacao = $IdOperacao AND 
					GrupoPermissaoSubOperacao.IdSubOperacao = IdSubOperacao.IdSubOperacao
					$where
				ORDER BY 
					IdSubOperacao.DescricaoSubOperacao;";
		$res = mysql_query($sql,$con);
		if(@mysql_num_rows($res) >= 1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		} else{
			return "false";
		}
		
		while($lin = @mysql_fetch_array($res)){
			$dados	.=	"\n<IdSubOperacao>$lin[IdSubOperacao]</IdSubOperacao>";
			$dados	.=	"\n<DescricaoSubOperacao><![CDATA[$lin[DescricaoSubOperacao]]]></DescricaoSubOperacao>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
		}
		
		if(mysql_num_rows($res) >= 1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	
	echo get_usuario_sub_operacao();
?>