<?
	$localModulo		=	1;
	$localOperacao		=	112;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	$array_operacao 	= array("112") ;
	$Path = "../../";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica_menu.php');
	include ('../../classes/envia_mensagem/envia_mensagem.php');

	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Login			= $_SESSION["Login"];
	$local_IdContaReceber	= $_GET["IdContaReceber"];
	$local_GeraNF			= $_GET["GeraNF"];
	$Disabled				= "";
	$Cancelada				= false;
	$lin					= "";

	if($local_IdContaReceber == ''){
		header("Location: conteudo.php");
	}

	// Gera NF quando
	if($local_GeraNF == '1'){
		if(gera_nf($local_IdLoja, $local_IdContaReceber)){
			enviaNotasFiscais($local_IdLoja,$local_IdContaReceber);
			
		}
	}

	// Verifica se existe nota fiscal
	$sql = "select
				IdNotaFiscalLayout,
				IdStatus
			from
				NotaFiscal
			where
				IdLoja = $local_IdLoja and
				IdContaReceber = $local_IdContaReceber
			order by
				IdStatus DESC";
	$res = mysql_query($sql,$con);
	if($linNotaFiscal = @mysql_fetch_array($res)){
		switch($linNotaFiscal[IdStatus]){
			case 1:
				header("Location: nota_fiscal/$linNotaFiscal[IdNotaFiscalLayout]/nota_fiscal_pdf.php?IdLoja=$local_IdLoja&IdContaReceber=$local_IdContaReceber");
				$Cancelada = false;
				break;
			case 0:
				$Cancelada = true;
				break;
		}
	}

	// Localiza o tipo da nota fiscal
	$sql = "select
				distinct
				NotaFiscalLayout.DescricaoNotaFiscalLayout
			from
				LancamentoFinanceiroDados,
				Servico,
				NotaFiscalTipo,
				NotaFiscalLayout
			where
				LancamentoFinanceiroDados.IdLoja = $local_IdLoja and
				LancamentoFinanceiroDados.IdLoja = Servico.IdLoja and
				LancamentoFinanceiroDados.IdLoja = NotaFiscalTipo.IdLoja and
				LancamentoFinanceiroDados.IdContaReceber = $local_IdContaReceber and
				LancamentoFinanceiroDados.IdServico = Servico.IdServico and
				Servico.IdNotaFiscalTipo = NotaFiscalTipo.IdNotaFiscalTipo and
				NotaFiscalTipo.IdNotaFiscalLayout = NotaFiscalLayout.IdNotaFiscalLayout";
	$res = mysql_query($sql,$con);
	$lin = @mysql_fetch_array($res);

	cehck_gera_nf($local_IdLoja, $local_IdContaReceber);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<? include('../../files/head.php'); ?>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
	</head>
	<body onLoad="ativaNome('Conta Receber / Nota Fiscal')">
		
		<?
			if($Cancelada){
				echo "<div id='sem_permissao'>
						<p id='p1'>Este Conta a Receber já possui uma Nota Fiscal cancelada.<br/>$lin[DescricaoNotaFiscalLayout]</p>
						<p id='p2'>Para reativala basta clica em avançar.</p>
					</div>";
			}else{	
				echo "<div id='sem_permissao'>
						<p id='p1'>Você está prestes gerar uma nota fiscal para este conta a receber.<br/><?=$lin[DescricaoNotaFiscalLayout]?></p>
						<p id='p2'>Para continuar, clique em avançar.</p>
					</div>";
			}
		?>
		<table  style='width:100%; margin-top:30px; text-align:center'>
			<tr>
				<td class='campo'>
					<input type='button' name='bt_voltar' value='Voltar' style='cursor:pointer; width: 55px; height: 25px' onClick="confirmar('cadastro_conta_receber.php?IdContaReceber=<?=$local_IdContaReceber?>')">
					<input type='button' name='bt_confirmar' value='Avançar' style='cursor:pointer; width: 80px; height: 25px' onClick="confirmar('menu_conta_receber_nota_fiscal.php?IdContaReceber=<?=$local_IdContaReceber?>&GeraNF=1')">
				</td>
			</tr>
		</table>

	</body>
</html>
<script>
	function ativaNome(nome){
		if(window.parent.cabecalho != undefined){	
			window.parent.menu.document.formulario.codigo_barra.value = '';
			window.parent.cabecalho.document.getElementById('cp_modulo_atual').innerHTML = nome;
		}
	}
	function confirmar(local){
		if(local == '' || local == undefined){
			local = 'conteudo.php';
		}
		window.location.replace(local);
	}
</script>
