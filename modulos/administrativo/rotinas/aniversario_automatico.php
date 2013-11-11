<?
	$sql = "SELECT
				DISTINCT
				Pessoa.IdLoja,
				Pessoa.IdPessoa
			FROM
				Pessoa
					LEFT JOIN Contrato ON (Pessoa.IdPessoa = Contrato.IdPessoa)
					LEFT JOIN Usuario ON (Pessoa.IdPessoa = Usuario.IdPessoa)
			WHERE
				Pessoa.TipoPessoa = 2 AND
				substring(Pessoa.DataNascimento,6,5) = substring(curdate(),6,5) and
				Pessoa.Email != '' AND
				(
					Contrato.IdStatus >= 200 OR
					Usuario.IdStatus = 1
				)";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		if($lin[IdLoja] == ''){		
			# Somente é em branco quando a pessoa foi cadastrada na loja 1
			$lin[IdLoja] = 1;
		}
		enviarEmailAniversario($lin[IdLoja], $lin[IdPessoa]);
	}
?>