<?
	$localModulo		=	1;
	$localOperacao		=	26;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_MD5 = $_GET["Anexo"];

	$sql =  "
		SELECT
			IdLoja,
			IdOrdemServico,
			IdAnexo,
			NomeOriginal
		FROM
			OrdemServicoAnexo
		WHERE
			MD5 = '$local_MD5';";
	$res = mysql_query($sql,$con);
	if($lin = mysql_fetch_array($res)){
		$Ext = endArray(explode(".",$lin[NomeOriginal]));
		$File = "anexos/ordem_servico/$lin[IdOrdemServico]/$lin[IdAnexo].$Ext";
		$lin[NomeOriginal] = str_replace(" ","_",$lin[NomeOriginal]);
		
		header("Content-type: application/$Ext");
		header("Content-Disposition: attachment; filename=$lin[NomeOriginal]");
		die(file_get_contents($File));
	}
?>
