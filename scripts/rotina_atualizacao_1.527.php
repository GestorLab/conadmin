<?
	include ("../files/conecta.php");
	include ("../files/funcoes.php");

	$sql = "select
				distinct
				Navegador
			from
				LogAcesso
			where
				Navegador != ''";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$DescricaoNavegador = NomeNavegador($lin[Navegador]);

		$sqlVerifica = "select
							max(IdParametroSistema) IdParametroSistema
						from
							ParametroSistema
						where
							IdGrupoParametroSistema = 89";
		$resVerifica = mysql_query($sqlVerifica,$con);
		$linVerifica = mysql_fetch_array($resVerifica);

		$i = $linVerifica[IdParametroSistema] + 1;

		$sql = "insert into ParametroSistema (IdGrupoParametroSistema, IdParametroSistema, DescricaoParametroSistema, ValorParametroSistema, LoginCriacao, DataCriacao) values ('89',  '$i',  'Navegador ($DescricaoNavegador)',  '$lin[Navegador]',  '$login',  concat(curdate(),' ',curtime()))";
		mysql_query($sql,$con);

		$sql = "update LogAcesso set IdNavegador = '$i' where Navegador='$lin[Navegador]'";
		mysql_query($sql,$con);
	}
?>