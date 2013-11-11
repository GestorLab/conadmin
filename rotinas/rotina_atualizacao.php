<?
	include("files/conecta.php");
	include("files/funcoes.php");
	include("classes/envia_mensagem/envia_mensagem.php");

	set_time_limit(0);
	ini_set("memory_limit",getParametroSistema(138,1));

	session_start("ConAdmin_session");

	$_SESSION["Login"]		=	'automatico';
	
	$local_Login		= 'automatico';
	$BackupAltenativo	= false;

	// Carrega as variáveis do config
	$Vars = Vars();
	$VarsKeys = array_keys($Vars);
	for($i=0; $i<count($VarsKeys); $i++){
		$_SESSION[$VarsKeys[$i]] = $Vars[$VarsKeys[$i]];
	}
	// Fim - Carrega as variáveis do config

	$sql = "SELECT
				IdVersao,
				IdAtualizacao,
				DataEtapa1,
				DataEtapa2,
				DataEtapa3,
				DataTermino
			FROM
				Atualizacao
			ORDER BY
				IdAtualizacao DESC
			LIMIT 0,1";
	$res = mysql_query($sql,$con);
	if($lin = mysql_fetch_array($res)){

		$IdAtualizacao	= $lin[IdAtualizacao];
		$IdVersao		= $lin[IdVersao];

		if($lin[DataTermino] == null){	$Etapa = 4;	}
		if($lin[DataEtapa3] == null){	$Etapa = 3;	}
		if($lin[DataEtapa2] == null){	$Etapa = 2;	}
		if($lin[DataEtapa1] == null){	$Etapa = 1;	}

		$sql = "delete from Atualizacao where IdAtualizacao < $IdAtualizacao and DataTermino IS NULL";
		mysql_query($sql,$con);
	}

	switch($Etapa){
		case 1:
			# Gerar Backup do Sistema
			include("rotina_atualizacao_etapa1.php");
			break;

		case 2:
			# Instala a versão mais atualizada dos arquivos
			include("rotina_atualizacao_etapa2.php");
			break;

		case 3:
			# Instala a versão mais atualizada do banco de dados
			include("rotina_atualizacao_etapa3.php");
			break;

		case 4:
			# Compacta o backup e apaga os arquivos temporários
			include("rotina_atualizacao_etapa4.php");
			break;
	}
?>