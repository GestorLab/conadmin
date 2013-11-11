<?
	session_start("ConAdmin_session");

	$_SESSION["Login"]		=	'automatico';

	if($Path == ''){
		$EndFile 	= "rotinas/cron_minuto.php";
		$Vars 		= $_SERVER['argv'];
		$Path		=  substr($Vars[0],0,strlen($Vars[0])-strlen($EndFile));
	}

	include($Path.'files/conecta.php');
	include($Path.'files/funcoes.php');
	include($Path.'classes/envia_mensagem/envia_mensagem.php');
	include($Path.'classes/zip.lib/zip.lib.php');
	
	ini_set("memory_limit",getParametroSistema(138,1));

	$PathPHP = getParametroSistema(6,4);
	$Minuto	 = date("i"); # Usado para o Dedalos
	
	$local_Login		= 'automatico';

	set_time_limit(0);

	// Atualizador Automбtico
	include($Path."rotinas/atualizador_automatico.php");

	// Verifica se o cron-minuto estб ativo PS 275,2 = 1
	$sql = "SELECT
				ValorParametroSistema
			FROM
				ParametroSistema
			WHERE
				IdGrupoParametroSistema = 275 AND
				IdParametroSistema = 2 AND
				ValorParametroSistema = 1";
	$res = mysql_query($sql,$con);
	if($lin = mysql_fetch_array($res)){

		// Salva o momento da ъltima execuзгo
		$sql = "UPDATE ParametroSistema SET ValorParametroSistema=now() WHERE IdGrupoParametroSistema='275' AND IdParametroSistema=4";
		mysql_query($sql,$con);

		// Reboot Radius
		include($Path."rotinas/reboot_free_radius.php");

		// Check Status
		include($Path."rotinas/check_status.php");

		mysql_close($con);
		include($Path.'files/conecta.php');

		// Radius Derruba Conexao
		include($Path."rotinas/radius_derruba_conexao.php");

		mysql_close($con);
		include($Path.'files/conecta.php');

		// Monitor Porta
		include($Path."rotinas/cron_monitor.php");

		mysql_close($con);
		include($Path.'files/conecta.php');

		// Verifica mensagens sem envio
		include($Path."rotinas/cron_mensagens.php");

		mysql_close($con);
		include($Path.'files/conecta.php');

		// Localizador
		include($Path."rotinas/cron_localizacao.php");

		mysql_close($con);
		include($Path.'files/conecta.php');

		// Radius - Processamento de log
		include($Path."rotinas/radius_processa_radacct.php");

		mysql_close($con);
		include($Path.'files/conecta.php');

		// Cron Minuto Personalizado
		if(file_exists($Path."rotinas/cron_minuto_personalizado.php")){
			include($Path."rotinas/cron_minuto_personalizado.php");

			mysql_close($con);
			include($Path.'files/conecta.php');
		}

		if($Minuto == 0){
			mysql_close($con);
			include($Path.'files/conecta.php');

			// Exportaзгo para o Dedalos
			@include($Path."rotinas/cron_dedalos.php");
		}

		if(getParametroSistema(252,1) == 0){
			include($Path.'files/conecta_cntsistemas.php');
			if($conCNT){			
				// Ativa novamente a conexгo ao nosso servidor
				$sql = "UPDATE ParametroSistema SET ValorParametroSistema='1' WHERE IdGrupoParametroSistema='252' AND IdParametroSistema='1'";
				mysql_query($sql,$con);
			}
			mysql_close($conCNT);
		}
	}else{
		echo date("Y-m-d H:i:s")." -> cron_minuto desativado!";
	}
?>