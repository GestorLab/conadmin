<?
	include("../../../../files/conecta.php");
	include("../../../../files/funcoes.php");

	$Valor		= $_GET[Valor];

	$UrlRedirecionamento	= getParametroSistema(130,0);

	$sql = "select
				Contrato.IdPessoa
			from
				Contrato,
				ContratoParametro
			where
				ContratoParametro.Valor = '$Valor' and
				ContratoParametro.IdLoja = Contrato.IdLoja and
				ContratoParametro.IdContrato = Contrato.IdContrato and
				Contrato.IdStatus >= 200";
	$res = @mysql_query($sql,$con);
	if($lin = @mysql_fetch_array($res)){
		header("Location: ../../?IdPessoa=$lin[IdPessoa]&AvisoAll=2&Aviso1=1");
	}else{
		header("Location: $UrlRedirecionamento");
	}
?>