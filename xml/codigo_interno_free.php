<?
	include ('../files/conecta.php');
	include ('../files/funcoes.php');
	
	if($_GET['IdGrupoParametroSistema']!= '' && $_GET['IdParametroSistema'] != ''){
		header ("content-type: text/xml");
		$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		$dados	.=	"\n<reg>";
		$dados	.=	"\n<ValorParametroSistema><![CDATA[".getParametroSistema($_GET['IdGrupoParametroSistema'],$_GET['IdParametroSistema'])."]]></ValorParametroSistema>";
		$dados	.=	"\n</reg>";
		echo $dados;
	}
?>
