<?
	@system("rm temp/www.backup.$IdAtualizacao.tar.bz2");
	system("tar -cvjpf temp/www.backup.$IdAtualizacao.tar.bz2 ../www.backup.$IdAtualizacao");
	system("rm -r ../www.backup.$IdAtualizacao");
	system("rm -r ../www/atualizacao");

	@mysql_close($con);
	include("files/conecta.php");
	
	// Apaga os erros de parвmetros do sistema nгo encontrados
	$sql = "UPDATE ParametroSistema SET ValorParametroSistema = '' WHERE IdGrupoParametroSistema = 135 AND IdParametroSistema = 1";
	mysql_query($sql,$con);
	
	// Apaga os erros de cуdigos internos nгo encontrados
	$sql = "UPDATE CodigoInterno SET ValorCodigoInterno = '' WHERE IdGrupoCodigoInterno = 31 AND IdCodigoInterno = 1";
	mysql_query($sql,$con);
	
	// Conclui a instalaзгo
	$sql = "update Atualizacao set DataTermino = NOW() where IdAtualizacao = $IdAtualizacao";
	mysql_query($sql,$con);

	// Ativa os crons (Backup e Diбrio)
	$sql = "UPDATE `ParametroSistema` SET 
				`ValorParametroSistema`='1' 
			WHERE 
				`IdGrupoParametroSistema`='275' AND 
				(
					`IdParametroSistema`='3' or 
					`IdParametroSistema`='1'
				)";
	mysql_query($sql,$con);

	$IdPessoa = getParametroSistema(4,7);

	@mysql_close($con);

	include("files/conecta_cntsistemas.php");
	
	$sql = "update HelpDesk set IdStatus = 100, DataAlteracao = NOW() where IdPessoa = $IdPessoa and IdStatus = 300";
	mysql_query($sql,$conCNT);

	$sql = "SELECT
				IdLicenca,
				IdAtualizacao,
				IdVersao,
				IdVersaoOld,
				DescricaoVersao,
				Login,
				DataEtapa0,
				DataEtapa1,
				DataEtapa2,
				DataEtapa3,
				LogUpdateMySQL,
				DataTermino
			FROM
				Atualizacao
			WHERE
				IdAtualizacao = $IdAtualizacao";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	$lin[LogUpdateMySQL] = str_replace("'","\'",$lin[LogUpdateMySQL]);

	$sql = "insert into Atualizacao set 
				IdLicenca = '$lin[IdLicenca]',
				IdAtualizacao = $lin[IdAtualizacao],
				IdVersao = '$lin[IdVersao]',
				IdVersaoOld = '$lin[IdVersaoOld]',
				DescricaoVersao = '$lin[DescricaoVersao]',
				Login  = '$lin[Login]',
				DataEtapa0 = '$lin[DataEtapa0]',
				DataEtapa1 = '$lin[DataEtapa1]',
				DataEtapa2 = '$lin[DataEtapa2]',
				DataEtapa3 = '$lin[DataEtapa3]',
				LogUpdateMySQL = '$lin[LogUpdateMySQL]',
				DataTermino = '$lin[DataTermino]'";
	mysql_query($sql,$conCNT);
	
	@mysql_close($conCNT);
?>