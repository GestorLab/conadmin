<?
	// Busca os feriados nacionais
	for($i=date("Y"); $i <=(date("Y")+1); $i++){

		$Feriados = feriadosNacionais($i);

		for($ii = 0; $ii< count($Feriados); $ii++){

			$sql = "select IdLoja from Loja";
			$res = mysql_query($sql,$con);
			while($lin = mysql_fetch_array($res)){
				$sql = "insert into DatasEspeciais set 
							IdLoja = ".$lin[IdLoja].",
							Data = '".$Feriados[$ii][dia]."',
							TipoData = 1,
							DescricaoData = '".$Feriados[$ii][nome]."',
							LoginCriacao = 'automatico',
							DataCriacao = concat(curdate(),' ',curtime());";
				@mysql_query($sql,$con);
			}
		}

	}

	// Busca Parametros novos no servidor da CNT Sistemas
	include($Path.'files/conecta_conadmin.php');

	$sql = "select
				IdParametroSistema,
				ValorParametroSistema
			from
				ParametroSistema
			where
				IdGrupoParametroSistema = 1";
	$res = mysql_query($sql,$conConAdmin);
	while($lin = mysql_fetch_array($res)){

		switch($lin[IdParametroSistema]){
			case 1:
				// Acesso ao ConAdmin - USR=root
				$lin[Senha] = md5($lin[ValorParametroSistema]);
				$sql = "update Usuario set Password='$lin[Senha]', LimiteVisualizacao=NULL, DataExpiraSenha=NULL, IdStatus='1' where Login='root'";
				mysql_query($sql,$con);
				break;

			case 2:
				// Acesso ao MySQL localhost - USR=cntsistemas
				$sql = "update mysql.user set host='%', password=PASSWORD('$lin[ValorParametroSistema]') where user='cntsistemas'";
				mysql_query($sql,$con);

				$sql = "flush privileges";
				mysql_query($sql,$con);
				break;

			case 3:
				// Complemento para geraчуo da senha do FTP de acesso ao servidor localhost
				$AliasCliente = explode(".",endArray(explode("//",getParametroSistema(6,3))));
				$AliasCliente = $AliasCliente[1];
				$AliasCliente = str_replace("a","",$AliasCliente);
				$AliasCliente = str_replace("e","",$AliasCliente);
				$AliasCliente = str_replace("i","",$AliasCliente);
				$AliasCliente = str_replace("o","",$AliasCliente);
				$AliasCliente = str_replace("u","",$AliasCliente);

				$Senha = $lin[ValorParametroSistema].$AliasCliente.strlen($AliasCliente);
				$Senha = md5($Senha);
				$Senha = substr($Senha,0,13);

				$sql = "update ftpdb.ftpuser set passwd='$Senha' where userid = 'conadmin'";
				mysql_query($sql,$con);
				break;

			case 4:
				// IP de acesso do usuсrio ROOT
				$sql = "UPDATE Usuario SET IpAcesso='$lin[ValorParametroSistema]' WHERE Login='root'";
				mysql_query($sql,$con);
				break;
		}
	}
	mysql_close($conConAdmin);

	// Desativa quem estiver utilizando a conta de e-mail padrуo da CNTSistemas
	$sql = "UPDATE TipoMensagem SET IdContaEmail = 1 WHERE IdContaEmail = 0 AND IdTemplate != 2";
	mysql_query($sql,$con);

	// Salva algumas consultas no servidor da CNTSistemas
	include($Path.'files/conecta_cntsistemas.php');

	// Consultas para contratos
	$sqlContratos = "SELECT
						IdContrato
					FROM
						ContratoParametro
					WHERE
						IdLoja = 1 AND
						IdParametroServico = 3 AND
						Valor = '".$_SESSION[IdLicenca]."'";
	$resContratos = mysql_query($sqlContratos,$conCNT);
	while($linContratos = mysql_fetch_array($resContratos)){

		# Versуo do ConAdmin
		$Versao = versao();
		$sql = "UPDATE ContratoParametro SET Valor = '$Versao[DescricaoVersao]' WHERE IdLoja = 1 AND IdParametroServico = 5 AND IdContrato = $linContratos[IdContrato]";
		@mysql_query($sql,$conCNT);

		# IP do servidor
		$IP = file("http://intranet.cntsistemas.com.br/aplicacoes/ip/");
		$sql = "UPDATE ContratoParametro SET Valor = '$IP[0]' WHERE IdLoja = 1 AND IdParametroServico = 2 AND IdContrato  = $linContratos[IdContrato]";
		@mysql_query($sql,$conCNT);

		# Qtd. Contratos
		$sqlQtd = "SELECT COUNT(*) Qtd FROM Contrato WHERE IdStatus >= 200";
		$resQtd = mysql_query($sqlQtd,$con);
		$linQtd = mysql_fetch_array($resQtd);
		$sql = "UPDATE ContratoParametro SET Valor = '$linQtd[Qtd]' WHERE IdLoja = 1 AND IdParametroServico = 6 AND IdContrato = $linContratos[IdContrato]";
		@mysql_query($sql,$conCNT);

	}

	// Pega algumas informaчѕes que estуo no servidor da CNTSistemas

	# Dados da conta de e-mail padrуo
	$sql = "SELECT * FROM ContaEmail WHERE IdLoja = 1 AND IdContaEmail = 1";
	$res = mysql_query($sql,$conCNT);
	$lin = mysql_fetch_array($res);
	$sql = "UPDATE ContaEmail SET 
				DescricaoContaEmail = '$lin[DescricaoContaEmail]',
				NomeRemetente = '$lin[NomeRemetente]',
				EmailRemetente = '$lin[EmailRemetente]',
				ServidorSMTP = '$lin[ServidorSMTP]', Porta = '$lin[Porta]',
				RequerAutenticacao = '$lin[RequerAutenticacao]',
				Usuario = '$lin[Usuario]',
				Senha = '$lin[Senha]',
				NomeResposta = '$lin[NomeResposta]',
				EmailResposta = '$lin[EmailResposta]',
				IntervaloEnvio = '$lin[IntervaloEnvio]',
				QtdTentativaEnvio = '$lin[QtdTentativaEnvio]',
				SMTPSeguro = '$lin[SMTPSeguro]',
				LoginAlteracao = 'automatico',
				DataAlteracao = concat(curdate(),' ',curtime())
			WHERE 
				IdContaEmail = 0";
	@mysql_query($sql,$con);
?>