<?
	$Path = "../../../";

	include("../../../files/conecta.php");
	include("../../../files/conecta_conadmin.php");
	include("../../../files/conecta_cntsistemas.php");
	include("../../../files/funcoes.php");
	include("../../../classes/envia_mensagem/envia_mensagem.php");

	set_time_limit(0);
	ini_set("memory_limit",getParametroSistema(138,1));

	$Etapa				= $_GET['Etapa'];
	$IdAtualizacao		= $_GET['IdAtualizacao'];
	$IdVersao			= $_GET['IdVersao'];
	$DescricaoVersao	= $_GET['DescricaoVersao'];

	$Path			= "../../../";

	// Vars
	$PatchSistema = getParametroSistema(6,1);

	if($Etapa > 0){
		$sql = "select
					DescricaoVersao,
					DataInicio,
					DataBackupMySQL,
					DataBackupFiles,
					DataDownloadFiles,
					DataUpdateFiles,
					DataUpdateMySQL,
					DataTermino
				from
					Atualizacao
				where
					IdAtualizacao = $IdAtualizacao";
		$res = mysql_query($sql,$con);
		$linEtapas = mysql_fetch_array($res);

		$IdVersao			= $linEtapas[IdVersao];
		$DescricaoVersao	= $linEtapas[DescricaoVersao];
	}

	$Erro = false;

	switch($Etapa){
		default:
			# Inicia - Charme
			include("rotina_atualizacao_etapa0.php");
			break;

		case 1:
			if($linEtapas[DataInicio] != ''){
				# Colocar o sistema em manutenчуo
				include("rotina_atualizacao_etapa1.php");
			}else{
				$Erro = true;
			}
			break;

		case 2:
			if($linEtapas[DataInicio] != ''){
				# Gerar Backup do Sistema
				include("rotina_atualizacao_etapa2.php");
			}else{
				$Erro = true;
			}
			break;

		case 3:			
			if($linEtapas[DataBackupMySQL] != '' && $linEtapas[DataBackupFiles] != ''){
				# Download arquivos da versуo selecionada pelo cliente
				include("rotina_atualizacao_etapa3.php");
			}else{
				$Erro = true;
			}
			break;

		case 4:
			if($linEtapas[DataDownloadFiles] != ''){
				# Instala a versуo mais atualizada dos arquivos
				include("rotina_atualizacao_etapa4.php");
			}else{
				$Erro = true;
			}
			break;

		case 5:
			if($linEtapas[DataUpdateFiles] != ''){
				# Instala a versуo mais atualizada do banco de dados
				include("rotina_atualizacao_etapa5.php");
			}else{
				$Erro = true;
			}
			break;

		case 6:
			if($linEtapas[DataUpdateMySQL] != ''){
				# Compacta o backup e apaga os arquivos temporсrios
				include("rotina_atualizacao_etapa6.php");
			}else{
				$Erro = true;
			}
			break;

		case 7:
			# Direciona
			include("rotina_atualizacao_etapa7.php");
			break;
	}
?>