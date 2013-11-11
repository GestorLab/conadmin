<?
	include("../../files/conecta.php");

	$Etapa = $_GET['Etapa'];

	$QtdEtapa = 8;

	switch($Etapa){
		case 0:
			$DescricaoEtapa = "Iniciando Atualização. Tempo estimado: 00:00:03";
			$LegthEtapa	= 0;
			break;
		case 1:
			$DescricaoEtapa = "Ativando modo de Atualização. Tempo estimado: 00:00:05";
			$LegthEtapa	= (int)(100/$QtdEtapa);
			break;
		case 2:
			$sql = "SELECT
						SEC_TO_TIME((TIME_TO_SEC(TIMEDIFF(DataHoraConclusao,DataHoraInicio))*1.5)) TempoEstimado
					FROM
						Backup
					WHERE
						Erro = 0 and
						DataHoraConclusao is not null and
						DataHoraConclusao != '0000-00-00 00:00:00'
					ORDER BY
						DataHoraInicio DESC
					LIMIT 0,1";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);

			$DescricaoEtapa = "Realizando Backup do Sistema. Tempo estimado: $lin[TempoEstimado]";
			$LegthEtapa	= (int)(100/$QtdEtapa*2);
			break;
		case 3:
			$sql = "SELECT
						SEC_TO_TIME((TIME_TO_SEC(TIMEDIFF(DataHoraConclusao,DataHoraInicio))*0.5)) TempoEstimado
					FROM
						Backup
					WHERE
						Erro = 0 and
						DataHoraConclusao is not null and
						DataHoraConclusao != '0000-00-00 00:00:00'
					ORDER BY
						DataHoraInicio DESC
					LIMIT 0,1";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);

			$DescricaoEtapa = "Fazendo Download da Versão Selecionada. Tempo estimado: $lin[TempoEstimado]";
			$LegthEtapa	= (int)(100/$QtdEtapa*3);
			break;
		case 4:
			$sql = "SELECT
						SEC_TO_TIME((TIME_TO_SEC(TIMEDIFF(DataHoraConclusao,DataHoraInicio))*0.8)) TempoEstimado
					FROM
						Backup
					WHERE
						Erro = 0 and
						DataHoraConclusao is not null and
						DataHoraConclusao != '0000-00-00 00:00:00'
					ORDER BY
						DataHoraInicio DESC
					LIMIT 0,1";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);

			$DescricaoEtapa = "Instalando Versão Atualizada dos Arquivos. Tempo estimado: $lin[TempoEstimado]";
			$LegthEtapa	= (int)(100/$QtdEtapa*4);
			break;
		case 5:
			$sql = "SELECT
						TIMEDIFF(DataHoraConclusao,DataHoraInicio) TempoEstimado
					FROM
						Backup
					WHERE
						Erro = 0 and
						DataHoraConclusao is not null and
						DataHoraConclusao != '0000-00-00 00:00:00'
					ORDER BY
						DataHoraInicio DESC
					LIMIT 0,1";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);

			$DescricaoEtapa = "Instalando Versão Atualizada do Banco de Dados. Tempo estimado: $lin[TempoEstimado]";
			$LegthEtapa	= (int)(100/$QtdEtapa*5);
			break;
		case 6:
			$sql = "SELECT
						SEC_TO_TIME((TIME_TO_SEC(TIMEDIFF(DataHoraConclusao,DataHoraInicio))*0.7)) TempoEstimado
					FROM
						Backup
					WHERE
						Erro = 0 and
						DataHoraConclusao is not null and
						DataHoraConclusao != '0000-00-00 00:00:00'
					ORDER BY
						DataHoraInicio DESC
					LIMIT 0,1";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);

			$DescricaoEtapa = "Concluindo Instalação. Tempo estimado: $lin[TempoEstimado]";
			$LegthEtapa	= (int)(100/$QtdEtapa*6);
			break;
		case 7:
			$DescricaoEtapa = "Instalação Concluída. Aguarde redirecionamento!";
			$LegthEtapa	= 100;
			break;
	}

	$Legth = ($LegthEtapa * 3)-6;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<title>ConAdmin - Update</title>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/index.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<style>
			#graficoBck, #grafico{
				margin: auto;
			}
			#graficoBck{
				margin-top: 10px;
				background-color: green;
				width: 300px;
				border: 1px black solid;
				height: 18px;
			}
			#grafico{
				background-color: red;
				width: <?=$Legth?>px;
				float: left;
				border-right: 1px black solid;
				text-align: right;
				font-weight: bold;
				color: #FFF;
				padding: 3px;
			}
			#cronometro{
				font-weight: bold;
				font-size: 11px;
				margin-top: 5px;
			}
		</style>
	</head>
	<body>
		<div id='l1'>
			<div style='width: 166px; float: left; margin-left: 5px; margin-top: 12px;'>
				<a href='index.php'><img src='../../img/personalizacao/logo_princ.gif' alt='' /></a>
			</div>
			<img id='l1_img2' src='../../img/estrutura_sistema/logo_sistema.gif' alt='ConAdmin - Sistema Administrativo de Qualidade' />
		</div>
		<div id='l2'>ConAdmin - Sistema Administrativo de Qualidade - AGUARDE!</div>
		<div id='sem_permissao'>
			<p id='p1'>Sistema em Atualização!</p>
			<p id='p2'>Por favor, aguarde! Este processo pode demorar.<BR>Dúvidas entre em contato com o suporte.<br><a href='http://www.cntsistemas.com.br' target='_blank'>www.cntsistemas.com.br</a></p>
			<div id='graficoBck'>
				<?
					if($LegthEtapa > 0){
						echo "<div id='grafico'>$LegthEtapa%</div>";
					}
				?>
			</div>
			<br><?=$DescricaoEtapa?>
			<div id='cronometro'>&nbsp;</div>
		</div>
	</body>
</html>
<script language="Javascript">
	
	var timeCrono; 
	var hor = 0;
	var min = 0;
	var seg = 0;
	var startTime = new Date(); 
	var start = startTime.getSeconds();

	cronometro();

	function cronometro(){
		if (seg + 1 > 59){ 
			min+= 1 ;
		}
		if (min > 59){
			min = 0;
			hor+= 1;
		}

		var time = new Date(); 
		if (time.getSeconds() >= start){
			seg = time.getSeconds() - start;
		}else{
			seg = 60 + (time.getSeconds() - start);
		}

		timeCrono= (hor < 10) ? "0" + hor : hor;
		timeCrono+= ((min < 10) ? ":0" : ":") + min;
		timeCrono+= ((seg < 10) ? ":0" : ":") + seg;
		
		document.getElementById('cronometro').innerHTML = timeCrono;
		setTimeout("cronometro()",1000);
	}
</script>