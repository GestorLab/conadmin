<?php
	include("../vars.php");
	include("../vetor.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../../../css/default.css' />
		<script type = 'text/javascript' src = '../../../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = 'etiqueta.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Etiqueta')">
	<div id='carregando'>carregando</div>
	<div id='conteudo'>
		<form name='formulario' method='post' action='etiqueta_pdf.php' onSubmit='return validar()'>
			<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
			<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
			<input type='hidden' name='Local' value='Etiqueta'>
			<input type='hidden' name='Filtro_IdPessoa' value='<?=$local_Filtro_IdPessoa?>'>
			<input type='hidden' name='Filtro_IdContaReceber' value='<?=$local_Filtro_IdContaReceber?>'>
			<input type='hidden' name='IdProcessoFinanceiro' value='<?=$local_IdProcessoFinanceiro?>'>
			<input type='hidden' name='IdLocalCobranca' value='<?=$local_IdLocalCobranca?>'>
			<input type='hidden' name='EnderecoCobranca' value='<?=$local_EnderecoCobranca?>'>
			<input type='hidden' name='QTDLinha' value='5'>
			<input type='hidden' name='QTDColuna' value='2'>
			<input type='hidden' name='TotalEtiqueta' value='<?=$total_etiq?>'>
			<input type='hidden' name='QTDEtiqueta' value=''>
			<input type='hidden' name='QTDPagina' value=''>
			<input type='hidden' name='Cedulas' value=''>
			<div>
				<div id='cp_tit' style='margin-top:0; text-align:center'><?=getParametroSistema(112,$local_FormatoSaida)?></div>
				<table id='table_etiqueta'></table>
				<table style='margin-right:6px; width:840px; text-align:right'>
					<tr>
						<td class='campo'>
							<input type='submit' name='bt_exportar' value='Gerar Arquivo' class='botao' tabindex='9'>
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
<script>
	inicia();
</script>

