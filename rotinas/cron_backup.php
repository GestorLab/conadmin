<?
	session_start("ConAdmin_session");

	$_SESSION["Login"]		=	'automatico';

	if($Path == ''){
		$EndFile 	= "rotinas/cron_backup.php";
		$Vars 		= $_SERVER['argv'];
		$Path		=  substr($Vars[0],0,strlen($Vars[0])-strlen($EndFile));
	}


	include($Path.'files/conecta.php');
	include($Path.'files/funcoes.php');
	include($Path.'classes/envia_mensagem/envia_mensagem.php');
	
	ini_set("memory_limit",getParametroSistema(138,1));
	
	$local_Login		= 'automatico';
	$BackupAltenativo	= false;

	// Carrega as variáveis do config
	$Vars = Vars();
	$VarsKeys = array_keys($Vars);
	for($i=0; $i<count($VarsKeys); $i++){
		$_SESSION[$VarsKeys[$i]] = $Vars[$VarsKeys[$i]];
	}
	// Fim - Carrega as variáveis do config

	set_time_limit(0);

	// Verifica se o cron-minuto está ativo PS 275,3 = 1
	$sql = "SELECT
				ValorParametroSistema
			FROM
				ParametroSistema
			WHERE
				IdGrupoParametroSistema = 275 AND
				IdParametroSistema = 3 AND
				ValorParametroSistema = 1";
	$res = mysql_query($sql,$con);
	if($lin = mysql_fetch_array($res)){

		// Salva o momento da última execução
		$sql = "UPDATE ParametroSistema SET ValorParametroSistema=now() WHERE IdGrupoParametroSistema='275' AND IdParametroSistema=6";
		mysql_query($sql,$con);
		
		// Rotina de Backup
		include($Path."rotinas/backup.php");
	}else{
		echo date("Y-m-d H:i:s")." -> cron_backup desativado!";
	}
?>
