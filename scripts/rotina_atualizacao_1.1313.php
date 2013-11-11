<?
	include("../files/conecta.php");

	$sql = "select
				IdLoja,
				IdServico,
				CFOP
			from
				Servico
			where
				CFOP > 0";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$sql = "insert into ServicoCFOP (IdLoja, IdServico, CFOP) values ($lin[IdLoja],$lin[IdServico],'$lin[CFOP]')";
		mysql_query($sql,$con);

		$sql = "update Contrato set CFOP = '$lin[CFOP]' where IdLoja = $lin[IdLoja] and IdServico = $lin[IdServico]";
		mysql_query($sql,$con);
	}
	
	$sql = "alter table Servico drop column `CFOP`;";
	mysql_query($sql,$con);
?>