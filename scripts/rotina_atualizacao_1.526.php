<?
	include ("../files/conecta.php");

	$sql = "select
				IdLoja,
				IdServico,
				max(DiasLimiteBloqueio) DiasLimiteBloqueio
			from
				ServicoPeriodicidade
			group by
				IdLoja, IdServico";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$sql = "update Servico set DiasLimiteBloqueio = '$lin[DiasLimiteBloqueio]' where IdLoja='$lin[IdLoja]' and IdServico='$lin[IdServico]'";
		mysql_query($sql,$con);
	}
?>