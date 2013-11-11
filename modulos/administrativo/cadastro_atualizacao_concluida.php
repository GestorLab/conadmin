<?
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');

	$sql = "select
				IdAtualizacao,
				IdVersao,
				IdVersaoOld,
				DescricaoVersao
			from
				Atualizacao
			where
				DataTermino is not null
			order by
				DataTermino DESC
			limit 0,1";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	$IdVersao		= $lin[IdVersao];
	$IdVersaoOld	= $lin[IdVersaoOld];
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
	<body  onLoad="ativaNome('Atualização Concluída!')">
		<? include('filtro_operacao.php'); ?>
		<div id='conteudo'>
			<div id='sem_permissao'>
				<p id='p1'>ATUALIZAÇÃO CONCLUÍDA!</p>
				<p id='p2'>Parabéns! A atualização do ConAdmin concluída!<BR><b>Versão <?=$lin[DescricaoVersao]?> (<?=$lin[IdVersao]?>)</b></p>
				<p id='p2'>É recomendável que você limpe todo o histórico de seu navegador antes de iniciar a<br>utilização do sistema.</p>
				<p id='p2'>Segue abaixo o relatório da atualização e as novidades da versão instalada.<BR>Dúvidas, entre em contato com o suporte.</p>

				<p id='p2'><U><B>Caso tenha apresentado algum erro/irregularidade,<BR>favor reportar a CNTSistemas via telefone URGENTE!</B></U><BR><BR>
				<a href='http://www.cntsistemas.com.br' target='_blank'>www.cntsistemas.com.br</a></p>
			</div>
			<br>
			<div class='tit'>Change Log - Última Atualização</div>
			<?		
				include("conteudo_quadro_change_log_ultima.php"); // Visualiza os quadro atualizações
			?>
			<div Id='tableListar'>&nbsp;</div>
		</div>
	</body>
</html>
<script>
	function confirmar(local){
		local = 'conteudo.php';
		window.location.replace(local);
	}
</script>