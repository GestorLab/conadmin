<?
	include ("../files/conecta.php");

	$sql = "select 
				ContratoVigencia.IdLoja,
				ContratoVigencia.IdContrato,
				Servico.ValorRepasseTerceiro
			from
				ContratoVigencia,
				Contrato,
				Servico
			where
				Contrato.IdLoja = ContratoVigencia.IdLoja and
				Contrato.IdLoja = Servico.IdLoja and
				Contrato.IdContrato = ContratoVigencia.IdContrato and
				Servico.IdServico = Contrato.IdServico";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
			if($lin[ValorRepasseTerceiro] == ''){
					$lin[ValorRepasseTerceiro] = 'NULL';
			}else{
				$lin[ValorRepasseTerceiro] = "'$lin[ValorRepasseTerceiro]'";
			}

		$sql = "update ContratoVigencia set ValorRepasseTerceiro=$lin[ValorRepasseTerceiro] where IdLoja='$lin[IdLoja]' and IdContrato='$lin[IdContrato]'";
		mysql_query($sql,$con);

	}
?>