<?
	$localModulo		=	1;
	
	if($_GET['IdContaEventual']!=''){		
		$localOperacao	=	31;
	}	
	if($_GET['IdLancamentoFinanceiro']!=''){		
		$localOperacao	=	18;
	}	
	if($_GET['IdOrdemServico']!=''){
		$localOperacao  = 	26;
	}		
	$localSuboperacao	=	"C";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login					= $_SESSION["Login"];
	$local_IdLoja					= $_SESSION["IdLoja"];
	$local_Acao 					= $_POST['Acao'];
	$local_Erro						= $_GET['Erro'];
	$local_ObsCancelamento			= formatText($_POST['ObsCancelamento'],NULL);
	$local_IdOrdemServico			= $_GET['IdOrdemServico'];
	$local_CancelarContaReceber		= $_POST['CancelarContaReceber'];
	$local_CancelarOrdemServico		= $_POST['CancelarOrdemServico'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javaScript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javaScript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javaScript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javaScript' src = '../../js/event.js'></script>
		<script type = 'text/javaScript' src = 'js/cancelar_multiplas_contas_receber.js'></script>
	
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Cancelar Conta Receber')">
		<? include('filtro_conta_receber.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='files/editar/editar_cancelar_conta_receber.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='CancelarMultiplasContasReceber'>
				<input type='hidden' name='Voltar' value=''>
				<input type='hidden' name='IdStatusParcela' value='<?=$local_IdStatusParcela?>'>
				<input type='hidden' name='CancelarContaReceber' value=''>
				<input type='hidden' name='LancamentoFinanceiroTipoContrato' value=''>
				<input type='hidden' name='ContExecucao' value='0'>
				<input type='hidden' name='TabIndex' value='2'>
				<input type='hidden' name='CancelarOrdemServico' value='<?=getCodigoInterno(60,1)?>'>
				<input type='hidden' name='IdOrdemServico'	value='<?=$local_IdOrdemServico?>'>
				
				<div id='cp_conta_receber'>
					<div id='cp_tit' style='margin:0'>Conta(s) a Receber Para o Cancelamento</div>
					<table id='tabelaContaReceber' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width:10px;'><input style='border:0' type='checkbox' name='todos_cr' onClick='selecionar(this)' tabindex='2'></td>
							<td style='width:40px; padding-left:5px;'>Id</td>
							<td>Nº Doc.</td>
							<td>Nº NF.</td>
							<td>Local Cob.</td>
							<td>Data Lanç.</td>
							<td class='valor'>Valor (<?=getParametroSistema(5,1)?>)</td>
							<td>Vencimento</td>
							<td class='valor'>Receb. (<?=getParametroSistema(5,1)?>)</td>
							<td>Pagamento</td>
							<td>Local Receb.</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='3' id='tabelaTotal'></td>
							<td colspan='3'>&nbsp;</td>
							<td id='tabelaTotalValor' class='valor'>0,00</td>
							<td>&nbsp;</td>
							<td id='tabelaTotalReceb' class='valor'>0,00</td>
							<td colspan='2'>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div id='cp_log' style='margin-bottom:3'>
					<div id='cp_tit' >Observações do Cancelamento</div>
					<div id='cpVoltarDataBase'></div>
					<table id='ObsCancelamento'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Observações do Cancelamento</B></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='ObsCancelamento' style='width:816px;' rows='5' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='106'></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_cancelar' value='Confirmar Cancelamento' class='botao' tabindex='107' onClick="cadastrar('cancelar')">
							</td>
						</tr>
					</table>
				</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td><h1 id='helpText' name='helpText' style='margin:0'>&nbsp;</h1></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>
<script type='text/javaScript'>
<?
	if($local_IdOrdemServico!=''){
		echo "listar_conta_receber($local_IdOrdemServico);";		
		echo "scrollWindow('bottom');";	
	}
?>
	function status_inicial(){ 
	//	if(document.formulario.VoltarDataBase.value == '0'){
	//		document.formulario.VoltarDataBase.value	=	'<?=getCodigoInterno(3,21)?>';
	//	}
	}
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>