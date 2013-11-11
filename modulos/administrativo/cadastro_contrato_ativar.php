<?
	$localModulo		=	1;
	$localOperacao		=	2;
	$localSuboperacao	=	"V";	

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdContrato					= formatText($_POST['IdContrato'],NULL);
	$local_DataAtivacaoInicio			= formatText($_POST['DataAtivacaoInicio'],NULL);
	$local_DataAtivacaoFim				= formatText($_POST['DataAtivacaoFim'],NULL);
	$local_AgruparContrato				= formatText($_POST['AgruparContrato'],NULL);
	$local_DataPrimeiroVenc				= formatText($_POST['DataPrimeiroVenc'],NULL);
	

	if($_GET['IdContrato']!=''){
		$local_IdContrato	=	$_GET['IdContrato'];
	}

	switch ($local_Acao){
		case 'confirmar':
			include('rotinas/processo_ativacao_contrato.php');
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
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_ativar.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js'></script>
		
		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>
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
	<body  onLoad="ativaNome('<?=dicionario(742)?>')">
		<?	include('filtro_contrato.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_contrato_ativar.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='ContratoAtivar'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(27)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdContrato' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosCliente'>
					<div id='cp_tit'><?=dicionario(219)?></div>
					<?	
						$nome="	<table id='cp_juridica'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:36px; color:#000'>".dicionario(26)."</B>".dicionario(85)."</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo' id='cp_RazaoSocial_Titulo'>".dicionario(172)."</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>".dicionario(179)."</td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='Nome' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo' id='cp_RazaoSocial'>
										<input type='text'  name='RazaoSocial' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='CNPJ' value='' style='width:150px' maxlength='18' readOnly>
									</td>
								</tr>
							</table>";
							
						$razao="	<table id='cp_juridica'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:36px; color:#000'>".dicionario(26)."</B><B style='color:#000'>".dicionario(172)."</B></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>".dicionario(92)."</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>CNPJ</td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='RazaoSocial' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='Nome' value='' style='width:279px' maxlength='100' readOnly>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='CNPJ' value='' style='width:150px' maxlength='18' readOnly>
									</td>
								</tr>
							</table>";
							
						switch(getCodigoInterno(3,24)){
							case 'Nome':
								echo "$razao";
								break;
							default:
								echo "$nome";
						}
					?>
					<table id='cp_fisica' style='display:none;padding-bottom:6px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:36px; color:#000'><?=dicionario(26)?></B><?=dicionario(85)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(104)?></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(210)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdPessoaF' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='NomeF' value='' style='width:279px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Email' value='' style='width:283px' maxlength='255' readOnly>
							</td>
							<td class='find' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CPF' value='' style='width:124px' maxlength='18' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosContrato'>
					<div id='cp_tit' >Dados do Contrato</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:36px'><?=dicionario(30)?></B><?=dicionario(223)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(224)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(152)?> <?=dicionario(516)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(226)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(229)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdServico' value=''  style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='DescricaoServico' value='<?=$local_DescricaoServico?>' style='width:305px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescPeriodicidade' value=''  style='width:140px' maxlength='11' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdParcela' value=''  style='width:80px' maxlength='11' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='TipoContrato' style='width:89px' disabled>
									<option value='0'>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=28 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td>
								<select name='DiaCobranca' disabled style='width: 71px'>
									<option value='0'></option>
									<?
										$sql = "select ValorCodigoInterno from (select convert(ValorCodigoInterno,UNSIGNED) ValorCodigoInterno from CodigoInterno where IdLoja = $local_IdLoja and IdGrupoCodigoInterno = 1) CodigoInterno order by ValorCodigoInterno";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[ValorCodigoInterno]' ".compara($local_DiaCobranca,$lin[ValorCodigoInterno],"selected", "").">".visualizarNumber($lin[ValorCodigoInterno])."</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(674)?>.</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(743)?>.</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(232)?></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(233)?></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(434)?>.</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(235)?>.</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataInicio' id='cpDataInicio' value='' style='width:105px' maxlength='10'  readOnly>
							</td>
							<td class='find'><img id='cpDataInicioIco' src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataPrimeiraCobranca' id='cpDataPrimeiraCobranca' value='' style='width:105px' maxlength='10' readOnly>
							</td>
							<td class='find'><img id='cpDataPrimeiraCobrancaIco' src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataBaseCalculo' value='' style='width:105px' maxlength='10' readOnly>
							</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataTermino' id='cpDataTermino' value='' style='width:102px' maxlength='10' readOnly>
							</td>
							<td class='find' id='imgDataTermino'><img id='cpDataTerminoIco' src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataUltimaCobranca' id='cpDataUltimaCobranca' value='' style='width:105px' maxlength='10' readOnly>
							</td>
							<td class='find' id='imgDataUltimaCobranca'><img id='cpDataUltimaCobrancaIco' src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='AssinaturaContrato' style='width:104px' disabled>
									<option value='0'>&nbsp;</option>
									<?
										$sql = "select IdCodigoInterno, ValorCodigoInterno from CodigoInterno where IdLoja = $local_IdLoja and IdGrupoCodigoInterno=9 order by ValorCodigoInterno";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdCodigoInterno]'>$lin[ValorCodigoInterno]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
				</div>	
				<div id='cp_dadosContrato'>
					<div id='cp_tit'><?=dicionario(744)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(283)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='tit_DataAtivacaoFim'><?=dicionario(745)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>							
							<td class='descCampo'><?=dicionario(1018)?></td>
							<td class='separador'>&nbsp;</td>		
							<td class='descCampo'><B id='tit_DataPrimeiroVenc'><?=dicionario(393)?>.</B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(109)?></B></td>
							
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataAtivacaoInicio' value='' style='width:105px' maxlength='10' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataAtivacaoFim' id='cpDataAtivacaoFim' value='' style='width:105px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('tit_DataAtivacaoFim',this);  verificaDataFinal('tit_DataAtivacaoFim',document.formulario.DataAtivacaoInicio.value,this.value); quantidadeDias(document.formulario.DataAtivacaoInicio.value,this.value)" tabindex='1'>
							</td>
							<td class='find'><img id='cpDataAtivacaoFimIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input name="qtdDias" type="text" style="width: 70px;" readOnly />
							</td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataAtivacaoFim",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataAtivacaoFimIco"
							    });
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataPrimeiroVenc' id='cpDataPrimeiroVenc' value='' style='width:105px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('tit_DataPrimeiroVenc',this)" tabindex='2'>
							</td>
							<td class='find'><img id='cpDataPrimeiroVencIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataPrimeiroVenc",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataPrimeiroVencIco"
							    });
							</script>	
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='AgruparContrato' style='width:113px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  tabindex='3'>
									<option value='0'>&nbsp;</option>
									<?	
										$sql = "select IdCodigoInterno, ValorCodigoInterno from CodigoInterno where IdLoja = $local_IdLoja and IdGrupoCodigoInterno=9 order by ValorCodigoInterno";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdCodigoInterno]'>$lin[ValorCodigoInterno]</option>";
										}
									?>
								</select>
							</td>
							
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_confirmar' value='<?=dicionario(746)?>' class='botao' tabindex='4' onClick="editar_contrato()">
							</td>
							<td class='campo'>
								<input type='button' name='bt_confirmar' value='<?=dicionario(404)?>' class='botao' tabindex='4' onClick="cadastrar()">
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 style='margin-bottom:0' id='helpText' name='helpText'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>	
			</form>
		</div>
	</body>	
</html>
<script>
	<?
		if($local_IdContrato != ""){
			echo "busca_contrato($local_IdContrato,false,document.formulario.Local.value);";
			echo "scrollWindow('top');";
		}
	?>
	function status_inicial(){
		if(document.formulario.AgruparContrato.value == 0){
			document.formulario.AgruparContrato.value = '<?=getCodigoInterno(3,39)?>';
		}
	}
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>
