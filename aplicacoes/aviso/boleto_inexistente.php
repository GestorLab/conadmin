<?
	include("../../files/conecta.php");
	include("../../files/funcoes.php");

	$MD5 = $_GET['ContaReceber'];

	$sql = "select
				ContaReceber.IdPessoa
			from
				ContaReceber
			where
				ContaReceber.Md5 = '$MD5'";
	$res = @mysql_query($sql,$con);
	$lin = @mysql_fetch_array($res);

	$IdPessoa = $lin[IdPessoa];

	if($IdPessoa == ''){
		header("Location: bloqueio/index.php");
	}

	$UrlRedirecionamento	= $_GET[UrlRedirecionamento];

	$UrlSistema				= getParametroSistema(6,3);
	$Moeda					= getParametroSistema(5,1);
	$LayoutAvisos			= getParametroSistema(130,1);
	$Titulo					= getParametroSistema(130,2);
	$Aviso0					= getParametroSistema(131,0);

	$Aviso[geral] = false;

	include("avisos/cabecalho.php");		#0
	include("avisos/boleto_inexistente.php");				#5
	include("avisos/cda.php");				#5

	include("mostra_avisos.php");
?>