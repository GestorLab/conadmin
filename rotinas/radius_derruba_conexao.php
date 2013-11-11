<?
	# Derruba as conexões
	$sql = "SELECT
				IdLoja,
				IdCodigoInterno,
				ValorCodigoInterno
			FROM
				CodigoInterno
			WHERE
				IdGrupoCodigoInterno = 65
			ORDER BY
				DataCriacao
			limit 0,3";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		# Derruba
		system($lin[ValorCodigoInterno]);

		# Remove da fila
		$sql2 = "delete from CodigoInterno where
					IdGrupoCodigoInterno = 65 and
					IdLoja = $lin[IdLoja] and
					IdCodigoInterno = $lin[IdCodigoInterno]";
		mysql_query($sql2,$con);
	}
?>