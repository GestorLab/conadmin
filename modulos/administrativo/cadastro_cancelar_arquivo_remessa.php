<?
	$localModulo				= 1;
	$localOperacao				= 58;
	$localSuboperacao			= "V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');	 
	
	$local_Login				= $_SESSION["Login"];
	$local_IdLoja				= $_SESSION["IdLoja"];
	$local_Acao 				= $_POST['Acao'];
	$local_Erro					= $_GET['Erro'];
	
	$local_IdArquivoRemessa		= $_POST['IdArquivoRemessa'];
	$local_IdLocalCobranca		= $_POST['IdLocalCobranca'];
	$local_DataRemessa			= $_POST['DataRemessa'];
	$local_IdArquivoRemessaTipo	= formatText($_POST['IdArquivoRemessaTipo'],NULL);
	
	if($_GET['IdLocalCobranca']!=''){
		$local_IdLocalCobranca	= $_GET['IdLocalCobranca'];	
	}
	
	if($_GET['IdArquivoRemessa']!=''){
		$local_IdArquivoRemessa	= $_GET['IdArquivoRemessa'];	
	}
	
	switch ($local_Acao){
		case 'cancelar':
			include('rotinas/cancelar_arquivo_remessa.php');
			break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/cancelar_arquivo_remessa.js' charset="iso-8859-1"></script>
		<script type = 'text/javascript' src = 'js/arquivo_remessa_default.js'></script>
		<script type = 'text/javascript' src = 'js/arquivo_remessa_tipo_default.js'></script>
	    <link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Arquivo de Remessa (Cancelar Processamento)')">
		<? include('filtro_cancelar_arquivo_remessa.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_cancelar_arquivo_remessa.php' onSubmit='return validar()' enctype ='multipart/form-data'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='CancelarArquivoRemessa'>
				<input type='hidden' name='EndArquivo' value=''>
				<input type='hidden' name='Visualizar' value=''>
				<input type='hidden' name='QtdLimitContaReceber' value=''>
				<input type='hidden' name='IdStatus' value=''>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>&nbsp;</td>							
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='width: 72px'></td>
							<td class='separador'>&nbsp;</td>
							<td class='descricao' style='width: 732px;'><B id='cp_Status'>&nbsp;</B></td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Dados do Arquivo de Remessa</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Local Cobrança</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Arq. Remessa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Arquivo de Remessa Tipo</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdLocalCobranca' tabindex='1' style='width:400px' onChange='verificaLocalCobranca(this.value);' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
									<option value='' selected></option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdArquivoRemessa' value='' autocomplete="off" style='width:75px' maxlength='11' onChange='busca_arquivo_remessa(this.value,document.formulario.IdLocalCobranca.value,false,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='2'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdArquivoRemessaTipo' value='' autocomplete="off" style='width:72px' maxlength='11' readOnly><input type='text' class='agrupador' style='width:238px' name='DescricaoArquivoRemessaTipo' value='' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Número Seqüencial</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Nome do Arquivo</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Remessa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>QTD. Lançamentos</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Tamanho (KB)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor Total (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NumSeqArquivo' value='' style='width:105px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NomeArquivo' value='' style='width:200px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataRemessa' value='' style='width:110px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdRegistro' value='' style='width:110px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='FileSize' value='' style='width:80px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorTotal' value='' style='width:127px' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_log'>
					<div id='cp_tit'>Log</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Log de Processamento</td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='LogRemessa' style='width: 816px;' rows=5 readOnly></textarea>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Usuário Cadastro</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Cadastro</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Usuário Alteração</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Alteração</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAlteracao' value='' style='width:180px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataAlteracao' value='' style='width:202px'  readOnly>
							</td>								
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Usuário Processamento</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Processamento</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Usuário Confirmação</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Confirmação</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginProcessamento' value='' style='width:180px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataProcessamento' value='' style='width:202px'readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginConfirmacao' value='' style='width:180px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataConfirmacao' value='' style='width:203px' readOnly>
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='text-align: right; width: 848px' border='0'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='width: 247px; text-align:right;'>
								<input type='button' style='width:70px;' name='bt_cancelar' value='Cancelar' class='botao' tabindex='109' onClick="cadastrar('cancelar')">
							</td>
						</tr>
					</table>
				</div>				
				<div>
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
<script type="text/javaScript">
<?
	if($local_IdLocalCobranca != '' && $local_IdArquivoRemessa != ''){
		echo "listaLocalCobranca('$local_IdArquivoRemessa',$local_IdLocalCobranca);";		
	} else{
		echo "listaLocalCobranca();";
	}
?>
	
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>