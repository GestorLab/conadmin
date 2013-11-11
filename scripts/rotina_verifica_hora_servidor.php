<?php
	include("../files/conecta.php");
	include("../files/conecta_cntsistemas.php");
	include("../files/conecta_conadmin.php");
	include("../files/funcoes.php");
	
	// Variáveis
	$local_IdPessoa	= getParametroSistema(4,7);

	$sql = "select
				count(*) Qtd
			from
				HelpDesk
			where
				IdLojaAbertura = 1 and
				IdTipoHelpDesk = 91 and
				IdSubTipoHelpDesk = 1 and
				IdStatus != 400 and
				IdPessoa = $local_IdPessoa";
	$res = mysql_query($sql,$conCNT);
	$lin = mysql_fetch_array($res);
	
	$local_IdTipoTicket					= 91;
	$local_IdSubTipoTicket				= 1;
	$local_IdGrupoUsuarioAtendimento	= 1;
	$local_LoginAtendimento				= '';
	$local_IdStatus						= 100;
	$local_IdLojaHelpDesk				= 1;
	$local_IdLoja						= 1;
	$local_Login						= 'automatico';
	$bloqueio							= 'disabled';

	$sql = "select curdate() 'Data', curtime() 'Hora'";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	$DataMySQL = $lin[Data]." ".$lin[Hora];
	$DataPHP   = date("Y-m-d H:i:s");

	if($DataMySQL != $DataPHP && $lin[Qtd] == 0){

		$local_Mensagem = "Realizar correção do horário do servidor.";

		$Path = "../";
		include("../classes/envia_mensagem/envia_mensagem.php");
		include("../modulos/administrativo/files/inserir/inserir_help_desk.php");		
	}

	echo $local_IdTicket;
?>