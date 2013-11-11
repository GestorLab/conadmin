<?
	$localModulo		= 1;
	$localOperacao		= 151;
	$localSuboperacao	= "V";
	$localCadComum		= true;

	$key = $_GET["key"];

	include('../../files/conecta.php');
	include('../../files/funcoes.php');

	if($key == ''){	
		include('../../rotinas/verifica.php');
		
		$local_Login		= $_SESSION["Login"];
		$local_IdLoja		= $_SESSION["IdLoja"];
	}else{
		$sqlVars = "SELECT
						Loja.IdLoja,
						Usuario.Login
					FROM
						Loja,
						Usuario
					WHERE
						MD5(CONCAT(Loja.IdLoja,
						Usuario.Login,
						Usuario.Password)) = '$key'";
		$resVars = mysql_query($sqlVars,$con);
		$linVars = mysql_fetch_array($resVars);

		$local_Login		= $linVars[Login];
		$local_IdLoja		= $linVars[IdLoja];
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<title><?=getParametroSistema(4,1)?></title>
		<link REL="SHORTCUT ICON" HREF="../../img/estrutura_sistema/favicon.ico">

		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/val_url.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script> 
		<script type='text/javascript' src='js/monitor.js'></script>
		<script type='text/javascript' src='js/monitor_default.js'></script>
		<?=getParametroSistema(242,1)?> 
	</head>
	<style type="text/css">
		.ponto_monitoramento{
			border: 1px red solid;
			width: 162px;
			margin: 2px;
			border: 1px #004492 solid;
			float: left;
			text-align:center;
		}
		.ponto_monitoramento .ponto_monitoramento_descricao{
			margin: 5px 5px 0 5px;
			background-color: #004492;
			padding: 2px;
			color: #FFF;
			font-weight: bold;
		}
		.ponto_monitoramento .ponto_monitoramento_conectado{
			margin-top: 3px;
		}
	</style>
	<body <? if($key == ''){ echo "onLoad=\"ativaNome('Monitor')\""; } ?>>
		<? 
			if($key == ''){	
				include('filtro_monitor.php'); 
			}
		?>
		<div id='carregando'>carregando</div>
			<div id='conteudo'>
				<div>
					<div id='cp_tit'>Pontos em Monitoramento</div>
					<?
						$sql = "SELECT
									IdMonitor,
									DescricaoMonitor,
									HostAddress,
									Latitude,
									Longitude
								FROM
									MonitorPorta
								WHERE
									IdStatus = 1 AND
									IdLoja = $local_IdLoja
								ORDER BY
									DescricaoMonitor";
						$res = mysql_query($sql,$con);
						
						while($lin = mysql_fetch_array($res)){
							if($lin[Latitude] != '' && $lin[Longitude] != '' && $key == ''){
								$GoogleMaps = "<a href='cadastro_monitor_mapeamento.php?IdMonitor=$lin[IdMonitor]' target='_blank'>Google Maps</a>";
							} else{
								$GoogleMaps = "&nbsp;";
							}

							echo "
							<div class='ponto_monitoramento' id='ponto_monitoramento_$lin[IdMonitor]'>
								<div class='ponto_monitoramento_descricao'>$lin[IdMonitor]. $lin[DescricaoMonitor]</div>
								<div class='ponto_monitoramento_conectado' id='ponto_monitoramento_img_$lin[IdMonitor]'><img id='ponto_monitoramento_img_$lin[IdMonitor]' src='../../img/estrutura_sistema/semafaro_0.jpg' alt='OFF-LINE'></div>
								<div class='ponto_monitoramento_txt' id='ponto_monitoramento_latencia_$lin[IdMonitor]'><B>OFF-LINE</B></div>
								<div class='ponto_monitoramento_txt'>$GoogleMaps</div>
							</div>";
							
							if($key != ""){
								echo "
							<script type='text/javascript'>
							<!--
								setTimeout(function () { busca_ponto_monitoramento($lin[IdMonitor], true, '$key'); }, 400);
								-->
							</script>";
							} else{
								echo "
							<script type='text/javascript'>
							<!--
								busca_ponto_monitoramento($lin[IdMonitor], true);
								-->
							</script>";
							}
						}

						if($key == ''){
							$sql = "SELECT
										Password
									FROM
										Usuario
									WHERE
										Login = '$local_Login'";
							$res = mysql_query($sql,$con);
							$lin = mysql_fetch_array($res);

							$key = md5($local_IdLoja.$local_Login.$lin[Password]);
							$url = getParametroSistema(6,3)."/quadro_monitor.php?key=$key";
						
							echo  "<div id='cp_sub_tit' style='margin: 5px 0 0 0; width: 100%; float: left; font-weight: normal'><B>Acesso direto: <a href='$url' target='_blank'>URL</a> / <a href='".getParametroSistema(6,3)."/modulos/administrativo/rotinas/gerar_qrcode.php?Data=$url&Size=3' target='_blank'>QR Code</a></B></div>";
						}
					?>
				</div>
			</div>
		</div>
	</body>
</html>