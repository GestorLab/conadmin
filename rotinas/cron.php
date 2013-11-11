<?
	session_start("ConAdmin_session");

	$_SESSION["Login"]		=	'automatico';

	if($Path == ''){
		$EndFile 	= "rotinas/cron.php";
		$Vars 		= $_SERVER['argv'];
		$Path		=  substr($Vars[0],0,strlen($Vars[0])-strlen($EndFile));
	}

	$local_RealizarBackup			= $Vars[1];
	$local_ProcessarRetorno			= $Vars[2];
	$local_StatusContrato			= $Vars[3];
	$local_EnviarEmail				= $Vars[4];
	$local_TratamentoLogFreeRadius	= $Vars[5];
	$local_ApagarArquivoTemporario	= $Vars[6];
	$local_RotinaPersonalizada		= $Vars[7];
	$local_NotaFiscalEmitida		= $Vars[8];

	include($Path.'files/conecta.php');
	include($Path.'files/funcoes.php');
	@include($Path.'files/funcoes_personalizadas.php');
	include($Path.'classes/envia_mensagem/envia_mensagem.php');
	
	ini_set("memory_limit",getParametroSistema(138,1));
	
	$local_Login		= 'automatico';
	$BackupAltenativo	= true;

	// Carrega as variáveis do config
	$Vars = Vars();
	$VarsKeys = array_keys($Vars);
	for($i=0; $i<count($VarsKeys); $i++){
		$_SESSION[$VarsKeys[$i]] = $Vars[$VarsKeys[$i]];
	}
	// Fim - Carrega as variáveis do config

	set_time_limit(0);

	// Verifica se o cron-minuto está ativo PS 275,1 = 1
	$sql = "SELECT
				ValorParametroSistema
			FROM
				ParametroSistema
			WHERE
				IdGrupoParametroSistema = 275 AND
				IdParametroSistema = 1 AND
				ValorParametroSistema = 1";
	$res = mysql_query($sql,$con);
	if($lin = mysql_fetch_array($res)){

		// Salva o momento da última execução
		$sql = "UPDATE ParametroSistema SET ValorParametroSistema=now() WHERE IdGrupoParametroSistema='275' AND IdParametroSistema=5";
		mysql_query($sql,$con);

		// Rotina de Busca de Parâmetros Novos
		include($Path."rotinas/parametros_novos.php");
		
		if($local_RotinaPersonalizada == 'on' || $local_RotinaPersonalizada != 'off'){
			mysql_close($con);
			include($Path.'files/conecta.php');

			// Rotina Personalizada
			if(file_exists($Path."rotinas/cron_personalizado.php")){
				include($Path."rotinas/cron_personalizado.php");
			}
		}

		if($local_ProcessarRetorno == 'on' || $local_ProcessarRetorno != 'off'){
			mysql_close($con);
			include($Path.'files/conecta.php');

			// Busca retorno automático na F2B
			include($Path."modulos/administrativo/local_cobranca/21/processa_retorno.php");
		}

		if($local_StatusContrato == 'on' || $local_StatusContrato != 'off'){
			mysql_close($con);
			include($Path.'files/conecta.php');

			// Rotina de Cancelamento Agendado
			include($Path."modulos/administrativo/rotinas/cancelamento_automatico.php");
		}

		if($local_StatusContrato == 'on' || $local_StatusContrato != 'off'){
			mysql_close($con);
			include($Path.'files/conecta.php');

			// Rotina de calibragem de status
			include($Path."modulos/administrativo/rotinas/contrato_atualiza_status.php");

			mysql_close($con);
			include($Path.'files/conecta.php');

			// Rotina de bloqueio automático
			include($Path."modulos/administrativo/rotinas/bloqueio_automatico.php");
		}

		if($local_TratamentoLogFreeRadius == 'on' || $local_TratamentoLogFreeRadius != 'off'){
			mysql_close($con);
			include($Path.'files/conecta.php');

			// Rotina relacionadas ao Radius
			include($Path."rotinas/radius.php");
		}

		if($local_ApagarArquivoTemporario == 'on' || $local_ApagarArquivoTemporario != 'off'){
			mysql_close($con);
			include($Path.'files/conecta.php');

			// Rotina de limpesa de temp
			include($Path."rotinas/limpa_temp.php");
		}

		if($local_RealizarBackup == 'on' || $local_RealizarBackup != 'off'){
			mysql_close($con);
			include($Path.'files/conecta.php');

			// Rotina de Backup
			include($Path."rotinas/backup.php");
		}
		
		if($local_NotaFiscalEmitida == 'on' || $local_NotaFiscalEmitida != 'off'){
			mysql_close($con);
			include($Path.'files/conecta.php');
			
			// Rotina de Envio de E-mails automático
			include($Path."modulos/administrativo/rotinas/nota_fiscal_emissao_diaria.php");
		}
			
		if($local_EnviarEmail == 'on' || $local_EnviarEmail != 'off'){
			mysql_close($con);
			include($Path.'files/conecta.php');

			// Teste do Sistema de E-mail
			include($Path."rotinas/rotina_teste_diario_conta_email.php");

			// Rotina de Envio de E-mails automático
			include($Path."modulos/administrativo/rotinas/aviso_corte_automatico.php");

			if(date("m") >= 5){
				// Rotina de Envio de E-mails do Termo de Quitacao Anual
				include($Path."modulos/administrativo/rotinas/rotina_enviar_termo_quitacao_anual.php");
			}
		}
		
		if($local_EnviarEmail == 'on' || $local_EnviarEmail != 'off'){
			mysql_close($con);
			include($Path.'files/conecta.php');

			// Rotina de Envio de E-mails automático
			include($Path."modulos/administrativo/rotinas/aniversario_automatico.php");
		}
	}else{
		echo date("Y-m-d H:i:s")." -> cron desativado!";
	}

	mysql_close($con);
	include($Path.'files/conecta.php');

	############ Verifica Licença
	$Vars = Vars();
	$Vars[DataLicenca] = dataConv($Vars[DataLicenca], 'Ymd', 'Y-m-d');
	$Vars[DataHoje] = date("Y-m-d");

	if($Vars[DataLicenca] != $Vars[DataHoje]){
		$nDiasIntervalo = nDiasIntervalo($Vars[DataLicenca],$Vars[DataHoje]);
		$nDiasIntervalo--;
		if($nDiasIntervalo < 0){
			$nDiasIntervalo = $nDiasIntervalo * (-1);
		}
		$Vars[DiasLicenca] -= $nDiasIntervalo;
		AtualizaConfig($Vars[IdLicenca], $Vars[TipoLicenca], $Vars[DiasLicenca]);

		if($Vars[DiasLicenca] <= 7){
			$KeyCode	= KeyCode($Vars[IdLicenca],1);

			$File		= @file("http://intranet.cntsistemas.com.br/licenca/licenca.php?KeyCode=$KeyCode");
			$KeyLicenca = end($File);
			KeyProcess($KeyCode, $KeyLicenca);
			$Vars = Vars();
		}
	}
	############ Verifica Licença
?>