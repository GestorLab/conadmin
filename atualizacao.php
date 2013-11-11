 <?
	include("files/conecta.php");

	$QtdEtapa = 5;

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

		if($lin[DataTermino] == null){	$Etapa = 4;	}
		if($lin[DataEtapa3] == null){	$Etapa = 3;	}
		if($lin[DataEtapa2] == null){	$Etapa = 2;	}
		if($lin[DataEtapa1] == null){	$Etapa = 1;	}

	}

	switch($Etapa){
		case 1:
			$DescricaoEtapa = "Realizando Backup do Sistema.";
			$LegthEtapa	= (int)(100/$QtdEtapa*1);
			break;

		case 2:
			$DescricaoEtapa = "Fazendo Download da Versão Selecionada.";
			$LegthEtapa	= (int)(100/$QtdEtapa*2);
			break;

		case 3:
			$DescricaoEtapa = "Instalando Versão Selecionada";
			$LegthEtapa	= (int)(100/$QtdEtapa*3);
			break;

		case 4:
			$DescricaoEtapa = "Concluindo Instalação.";
			$LegthEtapa	= (int)(100/$QtdEtapa*4);
			break;
	}

	$Legth = ($LegthEtapa * 3)-6;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<title>ConAdmin - Update</title>
		<link rel = 'stylesheet' type = 'text/css' href = 'css/index.css' />
		<link rel = 'stylesheet' type = 'text/css' href = 'css/default.css' />
		<meta http-equiv="Refresh" content="5; url=index.php">
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
				<a href='index.php'><img src='img/personalizacao/logo_princ.gif' alt='' /></a>
			</div>
			<img id='l1_img2' src='img/estrutura_sistema/logo_sistema.gif' alt='ConAdmin - Sistema Administrativo de Qualidade' />
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
		</div>
	</body>
</html>