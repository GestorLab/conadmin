<?
	include ('../files/conecta.php');
	include ('../files/funcoes.php');
	
	$local_CDA	= $_GET['cda'];

	// Acesso via código de barra boleto
	if(strlen($local_CDA) == 32 && getParametroSistema(95,35) == 1){

		// Verificar como é a entrada dos dados via MD5 no CDA e montar uma
		$sql = "SELECT
					md5(concat(Pessoa.IdPessoa, Pessoa.CPF_CNPJ, IF(Pessoa.Senha IS NULL,'',Pessoa.Senha))) MD5
				FROM
					ContaReceber,
					Pessoa
				WHERE
					ContaReceber.MD5 = '$local_CDA' AND
					ContaReceber.IdPessoa = Pessoa.IdPessoa
				limit 0,1";
		$res = mysql_query($sql,$con);
		if($lin = mysql_fetch_array($res)){
			header("Location: ".getParametroSistema(6,3)."/modulos/cda/rotinas/autentica.php?Pessoa=$lin[MD5]");
		}
	}else{
		$Url = getParametroSistema(6,3)."/modulos/cda";
		header("Location: $Url");
	}
?>