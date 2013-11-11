<?
	include ('../../files/conecta.php');

	$IdTipoServico		= $_GET[IdTipoServico];
	$ContratoViaCDA		= $_GET[ContratoViaCDA];
	$Cidade				= $_GET[Cidade];
	$TipoPessoa			= trim($_GET[TipoPessoa]);
	$CDATA				= trim($_GET[CDATA]);


	// Filtro tipo serviço
	if($IdTipoServico != ''){
		$where .= " AND Servico.IdTipoServico = $IdTipoServico";
	}
	// Filtro Contrato via CDA
	if($ContratoViaCDA != ''){
		$where .= " AND Servico.ContratoViaCDA = $ContratoViaCDA";
	}
	// Filtro IdPais/IdEstado/IdCidade
	if($Cidade != ''){
		$where .= " AND Filtro_IdPaisEstadoCidade LIKE '%$Cidade%'";
	}
	// Filtro TipoPessoa
	if($TipoPessoa != ''){
		if($TipoPessoa == 'f' or $TipoPessoa == 'F'){
			$TipoPessoa = 2;
		}else{
			$TipoPessoa = 1;
		}
		$where .= " AND Filtro_IdTipoPessoa = $TipoPessoa";
	}
	
	$sql = "SELECT
				Servico.IdLoja,
				Servico.IdServico,
				Servico.DescricaoServico,
				ServicoValor.Valor
			FROM
				Servico,
				ServicoValor
			WHERE
				Servico.IdStatus = 1 AND
				Servico.IdLoja = ServicoValor.IdLoja AND
				Servico.IdServico = ServicoValor.IdServico
				$where
			ORDER BY
				IdLoja,
				IdServico";
	$res = mysql_query($sql,$con);
	if(@mysql_num_rows($res) > 0){
		header ("content-type: text/xml");
		$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		$dados	.=	"\n<reg>";
			
		while($lin = @mysql_fetch_array($res)){
			$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
			$dados	.=	"\n<IdServico>$lin[IdServico]</IdServico>";
			if($CDATA == 'f'){
				$dados	.=	"\n<DescricaoServico>$lin[DescricaoServico]</DescricaoServico>";
			}else{
				$dados	.=	"\n<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
			}
			$dados	.=	"\n<Valor>$lin[Valor]</Valor>";
		}
			
		$dados	.=	"\n</reg>";
		echo $dados;
	}else{
		return "false";
	}
?>