<?
	$localModulo		=	1;
	$localOperacao		=	188;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	// include the php-excel class
	require ("../../classes/php-excel/class-excel-xml.inc.php");
	 	
	$local_IdLoja					= $_SESSION['IdLoja'];
	$local_IdPessoaLogin			= $_SESSION['IdPessoa'];
	$local_Login					= $_SESSION["Login"];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_ordem2					= $_POST['filtro_ordem2'];
	$filtro_ordem_direcao2			= $_POST['filtro_ordem_direcao2'];
	$filtro_localTipoDado2			= $_POST['filtro_tipoDado2'];
	$filtro_descricao_servico		= $_POST['filtro_descricao_servico'];
	$filtro_data_inicio				= $_POST['filtro_data_inicio'];
	$filtro_data_termino			= $_POST['filtro_data_termino'];
	$filtro_status					= $_POST['filtro_status'];
	$filtro_tipo					= $_POST['filtro_tipo'];
	$filtro_IdStatus				= $_GET['IdStatus'];
	$dados							= array();
	$status							= array();
	$cont							= 0;
	$filtro_limit					= $_POST['filtro_limit'];
	
	if($_GET['filtro_limit'] != ''){
		$filtro_limit = $_GET['filtro_limit'];
	}
	
	if($filtro_status == ''){
		$filtro_status		= $_GET['filtro_status'];
	}
	
	if($filtro_IdContrato == ''){
		$filtro_IdContrato	= $_POST['IdContrato'];
	}
	
	$filtro_cancelado				= $_SESSION["filtro_contrato_cancelado"];	
	$filtro_soma					= $_SESSION["filtro_contrato_soma"];
	$filtro_parametro_aproximidade	= $_SESSION["filtro_parametro_aproximidade"];
	$filtro_QTDCaracterColunaPessoa	= $_SESSION["filtro_QTDCaracterColunaPessoa"];
	
	$filtro_url	 = "";
	$filtro_sql  = "";
	$filtro_from = "";
	$order_by	 = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")				$filtro_url	.= "&Ordem=$filtro_ordem";
	if($filtro_ordem_direcao != "")		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	if($filtro_localTipoDado != "")		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_ordem2 != "")			$filtro_url	.= "&Ordem2=$filtro_ordem2";
	if($filtro_ordem_direcao2 != "")	$filtro_url .= "&OrdemDirecao2=$filtro_ordem_direcao2";
	if($filtro_localTipoDado2 != "")	$filtro_url .= "&TipoDado2=$filtro_localTipoDado2";
	
	if($filtro_data_inicio!=""){
		$filtro_url .= "&DataInicio=".$filtro_data_inicio;
		$filtro_data_inicio = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
		$filtro_sql .= " and SUBSTRING(Contrato.DataCriacao,1,10) >= '$filtro_data_inicio'";
		$filtro_sql2 .= " and SUBSTRING(ContratoStatus.DataAlteracao,1,10) >= '$filtro_data_inicio'";
	}
	
	if($filtro_data_termino!=""){
		$filtro_url .= "&DataTermino=".$filtro_data_termino;
		$filtro_data_termino = dataConv($filtro_data_termino,'d/m/Y','Y-m-d');
		$filtro_sql .= " and SUBSTRING(Contrato.DataCriacao,1,10) <= '$filtro_data_termino'";
		$filtro_sql2 .= " and SUBSTRING(ContratoStatus.DataAlteracao,1,10) <= '$filtro_data_termino'";
	}
	
	$sql = "SELECT
					COUNT(Contrato.IdContrato) Ativo
				FROM
					Contrato
				WHERE
					Contrato.IdLoja = $local_IdLoja
					$filtro_sql";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);
	$dados[$cont] 	= $lin["Ativo"];
	$status[$cont]	= "Ativo";
	$cont++;
	
	$sql1 = "SELECT
					COUNT(ContratoStatus.IdContrato) Cancelado
				FROM
					ContratoStatus
				WHERE
					ContratoStatus.IdLoja   = $local_IdLoja AND
					ContratoStatus.IdStatus = 1
					$filtro_sql2";
	$res1 = mysql_query($sql1,$con);
	$lin1 = mysql_fetch_array($res1);
	$dados[$cont] = $lin1["Cancelado"];
	$status[$cont]	= "Cancelado";
	$cont++;
	
	$sql2 = "SELECT
					COUNT(ContratoStatus.IdContrato) AguardandoCancelamento
				FROM
					ContratoStatus
				WHERE
					ContratoStatus.IdLoja   = $local_IdLoja AND
					ContratoStatus.IdStatus = 101
					$filtro_sql2";
	$res2 = mysql_query($sql2,$con);
	$lin2 = mysql_fetch_array($res2);
	$dados[$cont] = $lin2["AguardandoCancelamento"];
	$status[$cont]	= "Cancelado (Aguardando Cancelamento)";
	$cont++;
	
	$sql3 = "SELECT
					COUNT(ContratoStatus.IdContrato) Migrado
				FROM
					ContratoStatus
				WHERE
					ContratoStatus.IdLoja   = $local_IdLoja AND
					ContratoStatus.IdStatus = 102
					$filtro_sql2";
	$res3 = mysql_query($sql3,$con);
	$lin3 = mysql_fetch_array($res3);
	$dados[$cont] = $lin3["Migrado"];
	$status[$cont]	= "Cancelado (Migrado)";
	$cont++;
	
	$sql4 = "SELECT
					COUNT(ContratoStatus.IdContrato) Ativo_Tempo
				FROM
					ContratoStatus
				WHERE
					ContratoStatus.IdLoja   = $local_IdLoja AND
					ContratoStatus.IdStatus = 201
					$filtro_sql2";
	$res4 = mysql_query($sql4,$con);
	$lin4 = mysql_fetch_array($res4);
	$dados[$cont] = $lin4["Ativo_Tempo"];
	$status[$cont]	= "Ativo (Temporariamente)";
	$cont++;
	
	$sql5 = "SELECT
					COUNT(ContratoStatus.IdContrato) Ativo_Sem_Cobranca
				FROM
					ContratoStatus
				WHERE
					ContratoStatus.IdLoja   = $local_IdLoja AND
					ContratoStatus.IdStatus = 202
					$filtro_sql2";
	$res5 = mysql_query($sql5,$con);
	$lin5 = mysql_fetch_array($res5);
	$dados[$cont] = $lin5["Ativo_Sem_Cobranca"];
	$status[$cont]	= "Ativo (Sem Cobrança)";
	$cont++;
	
	$sql6 = "SELECT
					COUNT(ContratoStatus.IdContrato) Ativo_Pre_Cadastro
				FROM
					ContratoStatus
				WHERE
					ContratoStatus.IdLoja   = $local_IdLoja AND
					ContratoStatus.IdStatus = 203
					$filtro_sql2";
	$res6 = mysql_query($sql6,$con);
	$lin6 = mysql_fetch_array($res6);
	$dados[$cont] = $lin6["Ativo_Pre_Cadastro"];
	$status[$cont]	= "Ativo (Pré-Cadastro)";
	$cont++;
	
	$sql7 = "SELECT
					COUNT(ContratoStatus.IdContrato) Bloqueado_Tecnico
				FROM
					ContratoStatus
				WHERE
					ContratoStatus.IdLoja   = $local_IdLoja AND
					ContratoStatus.IdStatus = 301
					$filtro_sql2";
	$res7 = mysql_query($sql7,$con);
	$lin7 = mysql_fetch_array($res7);
	$dados[$cont] = $lin7["Bloqueado_Tecnico"];
	$status[$cont]	= "Bloqueado (Técnico)";
	$cont++;
	
	$sql8 = "SELECT
					COUNT(ContratoStatus.IdContrato) Bloqueado_Administrativo
				FROM
					ContratoStatus
				WHERE
					ContratoStatus.IdLoja   = $local_IdLoja AND
					ContratoStatus.IdStatus = 302
					$filtro_sql2";
	$res8 = mysql_query($sql8,$con);
	$lin8 = mysql_fetch_array($res8);
	$dados[$cont] = $lin8["Bloqueado_Administrativo"];
	$status[$cont]	= "Bloqueado (Administrativo)";
	$cont++;
	
	$sql9 = "SELECT
					COUNT(ContratoStatus.IdContrato) Bloqueado_Financeiro
				FROM
					ContratoStatus
				WHERE
					ContratoStatus.IdLoja   = $local_IdLoja AND
					ContratoStatus.IdStatus = 303
					$filtro_sql2";
	$res9 = mysql_query($sql9,$con);
	$lin9 = mysql_fetch_array($res9);
	$dados[$cont] = $lin9["Bloqueado_Financeiro"];
	$status[$cont]	= "Bloqueado (Financeiro)";
	$cont++;
	
	$sql10 = "SELECT
					COUNT(ContratoStatus.IdContrato) Bloqueado_Aguar_Ativacao
				FROM
					ContratoStatus
				WHERE
					ContratoStatus.IdLoja   = $local_IdLoja AND
					ContratoStatus.IdStatus = 304
					$filtro_sql2";
	$res10 = mysql_query($sql10,$con);
	$lin10 = mysql_fetch_array($res10);
	$dados[$cont] = $lin10["Bloqueado_Aguar_Ativacao"];
	$status[$cont]	= "Bloqueado (Aguar. Ativação)";
	$cont++;
	
	$sql11 = "SELECT
					COUNT(ContratoStatus.IdContrato) Bloqueado_Em_Andamento
				FROM
					ContratoStatus
				WHERE
					ContratoStatus.IdLoja   = $local_IdLoja AND
					ContratoStatus.IdStatus = 305
					$filtro_sql2";
	$res11 = mysql_query($sql11,$con);
	$lin11 = mysql_fetch_array($res11);
	$dados[$cont] = $lin11["Bloqueado_Em_Andamento"];
	$status[$cont]	= "Bloqueado (Em Andamento)";
	$cont++;
	
	$sql12 = "SELECT
					COUNT(ContratoStatus.IdContrato) Bloqueado_Agendado
				FROM
					ContratoStatus
				WHERE
					ContratoStatus.IdLoja   = $local_IdLoja AND
					ContratoStatus.IdStatus = 306
					$filtro_sql2";
	$res12 = mysql_query($sql12,$con);
	$lin12 = mysql_fetch_array($res12);
	$dados[$cont] = $lin12["Bloqueado_Agendado"];
	$status[$cont]	= "Bloqueado (Agendado)";
	$cont++;
	
	$sql13 = "SELECT
					COUNT(ContratoStatus.IdContrato) Reativado
				FROM
					ContratoStatus
				WHERE
					ContratoStatus.IdLoja 	= $local_IdLoja AND
					ContratoStatus.IdStatus = 200 AND
					ContratoStatus.IdStatusAntigo IN(301,302,303,304,305,306,1)
					$filtro_sql2";
	$res13 = mysql_query($sql13,$con);
	$lin13 = mysql_fetch_array($res13);
	$dados[$cont] = $lin13["Reativado"];
	$status[$cont]	= "Ativo/Reativado";
	$cont++;

	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}
	
	header ("content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_contrato_periodo_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	for($i=0;$i<$cont;$i++){
		echo "<reg>";	
		echo 	"<Status>$status[$i]</Status>";	
		echo 	"<Quantidade>$dados[$i]</Quantidade>";
		echo "</reg>";	
	}
	echo "</db>";
?>