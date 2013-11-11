<?
	include("../files/conecta.php");
	include("../files/conecta_cntsistemas.php");
	include("../files/funcoes.php");

	$Vars = Vars();

	$sql = "update Atualizacao set IdLicenca = '$Vars[IdLicenca]' where IdLicenca = ''";
	mysql_query($sql,$con);

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
				Atualizacao";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

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
	}
?>