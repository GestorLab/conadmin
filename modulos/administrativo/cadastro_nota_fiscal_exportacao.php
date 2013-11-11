<?
	$localModulo			= 1;
	$localOperacao			= 186;
	$localSuboperacao		= "V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login						= $_SESSION["Login"];
	$local_Acao 						= $_POST['Acao'];
	$local_Erro							= $_GET['Erro'];
	$local_IdLayoutNotaFiscal			= $_GET['IdLayoutNotaFiscal'];
	$local_Periodo						= $_GET['Periodo'];
	$FormatoExportacao					= $_GET['FormatoExportacao'];

	$local_IdStatusContrato				= $_POST['Filtro_IdStatusContrato'];	

	switch ($local_Acao){
		case 'exportar':
			header("Location: rotinas/");
			break;
		default:
			$local_Acao = 'exportar';
			break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/nota_fiscal.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Exportação Nota Fiscal')">
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='Mikrotik'>
				<input type='hidden' name='Filtro_IdStatusContrato' value=''>
				<div>
					<div id='cp_tit' style='margin-top:0'>Filtros</div>					
					<div id='cp_filtro_status'>
						<table id='titTabelaStatus'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Modelo</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><b>Período de Apuração</b></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>Tipo de Exportação</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<select name='IdNotaFiscalLayout' style='width:490px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
										<option value=''>Todos</option>
										<?
											$sql = "select
												IdNotaFiscalLayout,
												DescricaoNotaFiscalLayout
											from
												NotaFiscalLayout
											where
												IdNotaFiscalLayout = 1 or IdNotaFiscalLayout = 2
											order by
												DescricaoNotaFiscalLayout";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdNotaFiscalLayout]' ".compara($local_IdLayoutNotaFiscal,$lin[IdNotaFiscalLayout],"selected='selected'","").">$lin[DescricaoNotaFiscalLayout]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'><input type='text' name='MesReferencia' autocomplete="off" style='width:120px' maxlength='7' onkeypress="mascara(this,event,'mes')" value='<?=dataConv($local_Periodo,'Y-m','m/Y')?>'/></td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='FormatoExportacao' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
										<option value='1' <?=compara($FormatoExportacao,"1","selected='selected'","")?>>PDF</option>
										<!--<option value='2' <?=compara($FormatoExportacao,"2","selected='selected'","")?>>XML</option>-->
									</select>
								</td>
							</tr>
						</table>						
					</div>					
				</div>
				<div class='cp_botao'>
					<table style='width:100%; margin-top:9px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td style='text-align:right; padding-right:5px;' class='campo'>
								<input type='button' name='bt_exportar' value='Exportar' class='botao' tabindex='9' onClick='exportar_notas_fiscais(document.formulario.IdNotaFiscalLayout,document.formulario.MesReferencia,document.formulario.FormatoExportacao.value)'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>	
			</form>
		</div>		
	</body>	
</html>
<script type='text/javascript'>
	//inicia();
	enterAsTab(document.forms.formulario);
	<?
		if($local_Erro != ""){
			echo "mensagens($local_Erro)";
		}
	?>
</script>