<?php
	include ("../files/conecta.php");
	
	set_time_limit(0);

	$sql = "select 
				Cep,
				Endereco, 
				Bairro
			from 
				Cep";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$lin[Endereco]	= ucwords(strtolower($lin[Endereco]));
		$lin[Bairro]	= ucwords(strtolower($lin[Bairro]));

		$sql_update = "update Cep set  Endereco='$lin[Endereco]',  Bairro='$lin[Bairro]' where Cep='$lin[Cep]'";
		mysql_query($sql_update,$con);
	}
?>