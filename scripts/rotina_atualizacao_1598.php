<?
	include("../files/conecta.php");

	$sql = "select
				IdPessoa,
				IdGrupoPessoa
			from
				Pessoa";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$sql2 = "insert into PessoaGrupoPessoa set IdPessoa = $lin[IdPessoa], IdLoja = 1, IdGrupoPessoa = $lin[IdGrupoPessoa]";
		mysql_query($sql2,$con);
	}

	echo "COMMIT";
?>