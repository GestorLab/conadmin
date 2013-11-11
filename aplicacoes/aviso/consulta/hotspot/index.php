<?
	include("../../../../files/conecta.php");
	include("../../../../files/funcoes.php");

	$mac		= $_GET[mac];
	$username	= $_GET[UserName];
	$link_orig	= $_GET[link_orig];

	$UrlRedirecionamento	= getParametroSistema(130,0);

	if($mac != ''){			$Valor = $mac;						}
	if($username != ''){	$Valor = $username;					}

	if($link_orig != ''){	$UrlRedirecionamento = $link_orig;	}

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
		header("Location: ../../?IdPessoa=$lin[IdPessoa]&UrlRedirecionamento=$UrlRedirecionamento&Aviso4=1");
	}else{
		header("Location: $UrlRedirecionamento");
	}
?>