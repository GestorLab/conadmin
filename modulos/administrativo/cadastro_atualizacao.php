<?
	$localModulo	=	1;
	$localOperacao		= 183;
	$localSuboperacao	= "V";
	$localMenu		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Login		= $_SESSION["Login"];

	$IdVersao			= $_GET['IdVersao'];
	$DescricaoVersao	= $_GET['DescricaoVersao'];

	$AtualizacaoAtiva = false;

	// Segunda a quinta das 06:00 ás 15:00
	if((date("w") >= 1 && date("w") <= 4 && date("Hi") >= 600 && date("Hi") <= 1500) || $local_Login == 'root'){
		$AtualizacaoAtiva = true;
	}

	$sqlFeriado = "SELECT 
					COUNT(*) Qtd
				FROM 
					DatasEspeciais 
				WHERE 
					IdLoja = $local_IdLoja AND 
					Data = curdate();";
	$resFeriado = mysql_query($sqlFeriado, $con);
	$linFeriado = mysql_fetch_array($resFeriado);

	if($linFeriado[Qtd] >= 1){
		$AtualizacaoAtiva = false;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/data.js'></script>
		<script type = 'text/javascript' src = 'js/conteudo.js'></script>
	</head>
	<body  onLoad="ativaNome('Atualização Automática')">
		<? include('filtro_operacao.php'); ?>
		<div id='sem_permissao'>
			<?
				if($AtualizacaoAtiva == true){
					echo "<p id='p1'>Você está prestes a iniciar o processo de atualização automática de seu<BR>sistema para a versão '$DescricaoVersao'.</p>
							<p id='p2'>Este processo pode demorar.<BR>Para continuar, clique em avançar.</p>";
				}else{
					echo "<p id='p1'>Para segurança de suas informações e qualidade em nosso atendimento,<BR>processo de atualização automática de seu sistema somente pode ser iniciado<br>de segunda a quinta-feira das 06:00 ás 15:00.</p>
							<p id='p2'>Não é permitido atualizações em feriados pre-configurados no sistema.</p>
							<p id='p2'>Tente posteriormente.</p>";
				}
			?>
		</div>
		<table  style='width:100%; margin-top:30px; text-align:center'>
			<tr>
				<td class='campo'>
					<input type='button' name='bt_voltar' value='Voltar' style='cursor:pointer; width: 55px; height: 25px' onClick="voltar('news.php')">
					<?
						if($AtualizacaoAtiva == true){
							echo "<input type='button' name='bt_confirmar' value='Avançar' style='cursor:pointer; width: 80px; height: 25px' onClick=\"confirmar('../../rotinas/rotina_atualizacao_etapa0.php?IdVersao=$IdVersao&DescricaoVersao=$DescricaoVersao')\">";
						}
					?>
				</td>
			</tr>
		</table>
	</body>
</html>
<script>
	function voltar(local){
		if(local == '' || local == undefined){
			local = 'conteudo.php';
		}
		window.location.replace(local);
	}
	function confirmar(local){
		if(local == '' || local == undefined){
			local = 'conteudo.php';
		}
		parent.location.replace(local);
	}
</script>