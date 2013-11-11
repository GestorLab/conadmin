<?
	$localModulo	=	0;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Pessoa_Atualiza_CDA(){
		global $con;
		global $_GET;
		
		
		$CPF_CNPJ	= $_GET['CPF_CNPJ'];
		
		$sql = "select
					Pessoa.IdPessoa,
					Pessoa.ForcarAtualizarDadosCDA
				from 
					Pessoa
				where
					Pessoa.CPF_CNPJ = '$CPF_CNPJ'";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		header ("content-type: text/xml");

		$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		$dados	.=	"\n<reg>";
		$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
		$dados	.=	"\n<ForcarAtualizarDadosCDA><![CDATA[$lin[ForcarAtualizarDadosCDA]]]></ForcarAtualizarDadosCDA>";
		$dados	.=	"\n</reg>";
	
		return $dados;
	}
	echo get_Pessoa_Atualiza_CDA();
?>