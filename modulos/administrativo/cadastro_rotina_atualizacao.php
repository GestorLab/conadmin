<?
	$localModulo	=	1;
	$localMenu		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$IdVersao = $_GET['IdVersao'];
	$DescricaoVersao = $_GET['DescricaoVersao'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<title>Sistema ConAdmin - Atualização Automática - Aguarde!</title>
		<link REL="SHORTCUT ICON" HREF="../../img/estrutura_sistema/favicon.ico">
	</head>
	<frameset rows="*,100pixes" border=0>
		<frame src="cadastro_rotina_atualizacao_etapas.php?Etapa=0" name="superior" noresize>
		<frame src="rotinas/rotina_atualizacao.php?IdVersao=<?=$IdVersao?>&DescricaoVersao=<?=$DescricaoVersao?>" name="inferior" noresize>
	</frameset>
</html>