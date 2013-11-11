<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdArquivoRetorno			= $_POST['IdArquivoRetorno'];
	$local_IdLocalRecebimento		= $_POST['IdLocalRecebimento'];
	$local_EndArquivo				= formatText($_POST['EndArquivo'],NULL);
	$local_ValorTotal				= formatText($_POST['ValorTotal'],NULL);
	$local_Processado				= formatText($_POST['Processado'],NULL);
	$local_DataRetorno				= formatText($_POST['DataRetorno'],NULL);
	$local_DataProcessamento		= formatText($_POST['DataProcessamaneto'],NULL);
	$local_QtdRegistro				= formatText($_POST['QtdRegistro'],NULL);
	$local_NomeArquivo				= formatText($_POST['NomeArquivo'],NULL);
	$local_EnderecoArquivo			= formatText($_POST['EnderecoArquivo'],NULL);
	$local_NumSeqArquivo			= formatText($_POST['NumSeqArquivo'],NULL);
	$local_IdArquivoRetornoTipo		= formatText($_POST['IdArquivoRetornoTipo'],NULL);
	$local_IdTipoLocalCobranca		= formatText($_POST['IdTipoLocalCobranca'],NULL);
	
	if($_GET['IdLocalRecebimento']!=''){
		$local_IdLocalRecebimento	= $_GET['IdLocalRecebimento'];	
	}
	if($_GET['IdArquivoRetorno']!=''){
		$local_IdArquivoRetorno	= $_GET['IdArquivoRetorno'];	
	}
	
	switch ($local_Acao){	
		case 'processar':
			include('rotinas/cancelar_arquivo_retorno.php');
			break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/procurar.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/cancelar_arquivo_retorno.js'></script>
		<script type = 'text/javascript' src = 'js/arquivo_retorno_default.js'></script>
		<script type = 'text/javascript' src = 'js/arquivo_retorno_tipo_default.js'></script>
		<script type = 'text/javascript' src = 'js/local_cobranca_default.js'></script>
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
	<body  onLoad="ativaNome('Arquivo de Retorno (Cancelar Processamento)')">
		<? include('filtro_cancelar_arquivo_retorno.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' onSubmit='return validar()' enctype ='multipart/form-data'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='CancelarArquivoRetorno'>
				<input type='hidden' name='tempEndArquivo' id='tempEndArquivo' value=''>
				<input type='hidden' name='Arquivo' value=''>
				<input type='hidden' name='IdStatus' value=''>
				<input type='hidden' name='IdTipoLocalCobranca' value=''>
				<input type='hidden' name='CorRecebido' value='<?=getParametroSistema(15,3)?>'>
				<input type='hidden' name='CorRecebidoDesc' value='<?=getParametroSistema(15,7)?>'>
				<input type='hidden' name='CorCancelado' value='<?=getParametroSistema(15,2)?>'>
				<div >
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descricao' style='width: 822px; text-align:right;'><B id='cp_Status'>&nbsp;</B></td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Dados do Arquivo de Retorno</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='cp_LocalRecebimento'>Local Recebimento</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:6px'>Arq. Retorno</B><B id='cp_Arquivo'>Arquivo</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdLocalRecebimento' tabindex='1' style='width:400px' onChange='verificaLocalRecebimento(this.value)' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
									<option value='' selected></option>
									<?
										$sql = "select IdLocalCobranca, DescricaoLocalCobranca from LocalCobranca where IdLoja=$local_IdLoja and IdArquivoRetornoTipo != '' order by DescricaoLocalCobranca";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdLocalCobranca]'>$lin[DescricaoLocalCobranca]</option>";
										}
									?>
								</select>
							</td>					
							<td class='separador'>&nbsp;</td>
							<td class='campo' id='EndArquivo' style='display:block;'>								
								<input type='text' name='IdArquivoRetorno' value='' autocomplete="off" style='width:72px' maxlength='11' onChange='busca_arquivo_retorno(document.formulario.IdLocalRecebimento.value,this.value,false,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='2'>
								<input type="text" id="fakeupload" style='width: 240px;' name="fakeupload" class="fakeupload" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='3'/>								
								<div id='bt_procurar' tabindex='4'></div>
								<input type="file" id="realupload" name='EndArquivo' size='1' class="realupload" onchange="document.formulario.fakeupload.value = this.value; document.formulario.tempEndArquivo.value=document.formulario.EndArquivo.value; verifica_arquivo_retorno(this.value)" /> 																								
							</td>
							<td class='campo' id='EnderecoArquivo' style='display:none;'>
								<input type='text' name='IdArquivoRetorno2' value='' autocomplete="off" style='width:72px' maxlength='11' onChange='busca_arquivo_retorno(document.formulario.IdLocalRecebimento.value,this.value,false,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='2'><input type='text' class='agrupador' style='width:328px' name='EnderecoArquivo' value='' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Arquivo de Retorno Tipo</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Número Seqüencial</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Nome Original</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdArquivoRetornoTipo' value='' autocomplete="off" style='width:72px' maxlength='11' readOnly><input type='text' class='agrupador' style='width:308px' name='DescricaoArquivoRetornoTipo' value='' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NumSeqArquivo' value='' style='width:105px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NomeArquivo' value='' style='width:292px' maxlength='20' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Data Retorno</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>QTD. Lançamentos</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Tamanho (KB)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor Total (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor Taxas (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor Líquido (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataRetorno' value='' style='width:110px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdRegistro' value='' style='width:120px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='FileSize' value='' style='width:120px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorTotal' value='' style='width:127px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorTotalTaxa' value='' style='width:127px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorLiquido' value='' style='width:127px' readOnly>
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
								<textarea name='LogRetorno' style='width: 816px;' rows=5 readOnly></textarea>
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
							<td class='descCampo'>Usuário Processamento</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Processamento</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginProcessamento' value='' style='width:180px'  maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataProcessamento' value='' style='width:203px' readOnly>
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_cancelar' value='Cancelar' class='botao' tabindex='4' onClick="cadastrar()">
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
<?
	if($local_IdLocalRecebimento!= '' && $local_IdArquivoRetorno!=""){
		echo "busca_arquivo_retorno($local_IdLocalRecebimento,'$local_IdArquivoRetorno',false);";		
	}
?>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
