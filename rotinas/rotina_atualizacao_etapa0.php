<?
	include("../files/conecta.php");
	include("../files/funcoes.php");
	include("verifica.php");
	
	$Vars = Vars();

	$Login = $_SESSION["Login"];
	
	$IdVersao			= $_GET['IdVersao'];
	$DescricaoVersao	= $_GET['DescricaoVersao'];

	// Inicia Log de Atualizaчуo
	$sql = "select
				max(IdAtualizacao) IdAtualizacao
			from
				Atualizacao";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	$IdAtualizacao = $lin[IdAtualizacao]+1;

	$VersaoOld = versao();

	// Derruba todas as conexѕes
	$sql = "update LogAcesso set Fechada='1'";
	mysql_query($sql,$con);

	// Para os crons (Backup e Diсrio)
	$sql = "UPDATE `ParametroSistema` SET 
				`ValorParametroSistema`='2' 
			WHERE 
				`IdGrupoParametroSistema`='275' AND 
				(
					`IdParametroSistema`='3' or 
					`IdParametroSistema`='1'
				)";
	mysql_query($sql,$con);
	
	// Inicia a atualizaчуo
	$sql = "insert into Atualizacao set 
				IdLicenca = '$Vars[IdLicenca]', 
				IdAtualizacao = $IdAtualizacao, 
				IdVersao = $IdVersao,  
				DescricaoVersao = '$DescricaoVersao',
				IdVersaoOld = $VersaoOld[IdVersao], 
				Login='$Login', 
				DataEtapa0 = concat(curdate(),' ',curtime())";
	mysql_query($sql,$con);

	@mkdir("../atualizacao");

	header("Location: sair.php");
?>