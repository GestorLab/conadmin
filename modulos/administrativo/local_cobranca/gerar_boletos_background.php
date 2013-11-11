<?
	$localModulo		=	1;
	$localOperacao		=	3;
	$localSuboperacao	=	"V";	
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');

	$local_IdLoja				= $_SESSION["IdLoja"];
	$local_IdProcessoFinanceiro	=	$_GET['IdProcessoFinanceiro'];

	$sql = "select
				LocalCobranca.IdLocalCobrancaLayout
			from
				ProcessoFinanceiro,
				LocalCobranca
			where
				ProcessoFinanceiro.IdLoja = $local_IdLoja and
				ProcessoFinanceiro.IdLoja = LocalCobranca.IdLoja and
				ProcessoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro and
				ProcessoFinanceiro.Filtro_IdLocalCobranca = LocalCobranca.IdLocalCobranca";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	$file="$lin[IdLocalCobrancaLayout]/pdf_all.php";
	if(file_exists($file)){
		$pathSistema	= getParametroSistema(6,1);
		$pathPHP		= getParametroSistema(6,4);

		$fileurl = $pathSistema."/modulos/administrativo/local_cobranca/".$file." $local_IdLoja $local_IdProcessoFinanceiro s";

		system("$pathPHP $fileurl > $pathSistema/modulos/administrativo/local_cobranca/gerar_boletos_background.log &");
		
		$sql	=	"UPDATE ProcessoFinanceiro SET IdStatusBoleto = '1' WHERE IdLoja = '$local_IdLoja' and	IdProcessoFinanceiro = '$local_IdProcessoFinanceiro'";
		mysql_query($sql,$con);

		header("Location: ../cadastro_processo_financeiro.php?IdProcessoFinanceiro=$local_IdProcessoFinanceiro&Erro=51");		
	}else{
		header("Location: ../cadastro_processo_financeiro.php?IdProcessoFinanceiro=$local_IdProcessoFinanceiro&Erro=58");
	}
?>