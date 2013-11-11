<?
	$localModulo		=	1;
	$localOperacao		=	62;
	$localSuboperacao	=	"V";
	$Path				=   "../../";

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	@include ('../../files/funcoes_personalizadas.php');
	include ('../../rotinas/verifica.php');
	include ('../../classes/envia_mensagem/envia_mensagem.php');	 
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	$local_AcaoAuxiliar		= $_POST['AcaoAuxiliar'];
	
	$local_IdContrato				= $_POST['IdContrato'];
	$local_IdStatus					= $_POST['IdStatus'];
	$local_IdStatusAnterior			= $_POST['IdStatusAnterior'];
	$local_DataBloqueioStatus		= $_POST['DataBloqueioStatus'];
	$local_DataTerminoStatus		= $_POST['DataTerminoStatus'];
	$local_DataUltimaCobrancaStatus	= $_POST['DataUltimaCobrancaStatus'];
	$local_IdStatusAnteriorTemp		= $_POST['IdStatusAnteriorTemp'];
	$local_DataBloqueioStatus		= $_POST['DataBloqueioStatus'];
	$local_EmailNotificacao			= formatText($_POST['EmailNotificacao'],getParametroSistema(4,5));
	$local_CancelarContaReceber		= $_POST['CancelarContaReceber'];
	$local_Local					= $_POST['Local'];
	$local_ProtocoloAssunto			= $_POST['ProtocoloAssunto'];
	$local_ProtocoloObservacao		= $_POST['ProtocoloObservacao'];
	
	if($_POST['ObsCancelamento'] != ""){
		$local_Obs						= formatText($_POST['ObsCancelamento'],NULL);
	} elseif($_POST['Obs'] != ""){
		$local_Obs						= formatText($_POST['Obs'],NULL);
	}
	
	if($_GET['IdContrato'] != ''){
		$local_IdContrato = $_GET['IdContrato'];
	}
	if($_GET['IdStatus'] != ''){
		$local_IdStatus	= $_GET['IdStatus'];
	}
	
	switch ($local_Acao){	
		case 'alterar': 
			include('files/editar/editar_contrato_status.php');
			break;
		case 'cancelar':
			include('files/editar/editar_contrato_status.php');
			include('files/editar/editar_cancelar_conta_receber.php');		
			break;
		default:
			$local_Acao  = 'alterar';
			break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/val_email.js'></script>
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_status.js'></script>
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
	<body  onLoad="ativaNome('<?=dicionario(27)?>')">
		<? include('filtro_contrato.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form id='form' name='formulario' method='post' action='cadastro_contrato_status.php'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='ContratoStatus'>
				<input type='hidden' name='VarStatusAnterior' value=''>
				<input type='hidden' name='ServicoAutomaticoAnterior' value=''>
				<input type='hidden' name='IdStatusAnteriorTemp' value=''>
				<input type='hidden' name='StatusTempoAlteracao' value=''>
				<input type='hidden' name='TabIndex' value='8'>
				<input type='hidden' name='CancelarContaReceber' value=''>
				<input type='hidden' name='LancamentoFinanceiroTipoContrato' value=''>
				
				<div>
					<table style='width: 844px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(27)?></td>
							<td class='find'></td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdContrato' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly>
							</td>
							<td class='find'>&nbsp;</td>
							<td class='descricao' style='width:500px;'><B id='cpStatusContrato'>&nbsp;</B></td>
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
									<td class='descCampo'><B style='margin-right:36px; color:#000'>Pessoa</B><B style='color:#000'>".dicionario(172)."</B></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>".dicionario(92)."</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>".dicionario(179)."</td>
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
					<div id='cp_tit'><?=dicionario(435)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:32px'><?=dicionario(30)?></b><?=dicionario(223)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(224)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(651)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdServico' value=''  style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:483px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescPeriodicidade' value=''  style='width:134px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdParcela' value=''  style='width:90px' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(230)?></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(517)?>.</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(232)?></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(233)?></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(234)?></td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataInicio' value='' style='width:135px' maxlength='10' readOnly>
							</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataPrimeiraCobranca' id='cpDataPrimeiraCobranca' value='' style='width:135px' maxlength='10' readOnly>
							</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataBaseCalculo' value='' style='width:124px' maxlength='10' readOnly>
							</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataTermino' id='cpDataTermino' value='' style='width:124px' maxlength='10' readOnly>
							</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataUltimaCobranca' id='cpDataUltimaCobranca' value='' style='width:123px' maxlength='10' readOnly>
							</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(237)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(240)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(675)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' id='tDataBloqueio' style='display:none'><?=dicionario(676)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' id='tDataFinal' style='display:none'><?=dicionario(304)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorServico' value='' style='width:155px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorPeriodicidade' value='' style='width:135px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdStatusAnterior' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width: 263px' disabled>
									<option value='0' selected></option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' id='cmpDataBloqueio' style='display:none'>
								<input type='text' name='DataBloqueio' value='' style='width:100px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' id='cmpDataFinal' style='display:none'>
								<input type='text' name='DataFinal' value='' style='width:100px' maxlength='12' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosContrato'>
					<div id='cp_tit'><?=dicionario(518)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(312)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titDesconto' style='color:#000'><?=dicionario(411)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titPercentual' style='color:#000'><?=dicionario(521)?> (%)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(206)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(520)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>			
							<td class='descCampo'><B id='titValor' style='color:#000'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='IdTipoDesconto' style='width:126px' disabled>
									<option value=''>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=73 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='ValorDesconto' value='' style='width:115px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='ValorPercentual' value='' style='width:100px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='ValorFinal' value='' style='width:115px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='ValorRepasseTerceiro' value='' style='width:155px' readOnly>
							</td>						
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Valor' value='' style='width:125px' readOnly>
							</td>													
						</tr>
					</table>					
					<table id='tabDataDiaDesconto' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='descCampo'><B style='color:#000; display:none' id='titLimiteDesconto'>Dia Limite Desconto</B></td>							
							<td class='find'  id='titDataLimiteDescontoIco' style='display:none'>&nbsp;</td>				
						</tr>
						<tr>
							<td class='separador'>&nbsp;</td>
							<td class='campo' style='display:none' id='cpDiaLimiteDesconto'>
								<input type='text' name='DiaLimiteDesconto' value='' style='width:129px' readOnly><BR><B style='font-size:9px; font-weight:normal'>Base Vencimento</b>
							</td>							
							<td class='campo' style='display:none;' id='cpDataLimiteDesconto'>
								<input type='text' name='DataLimiteDesconto' id='cpDataLimiteDesconto2' value='' style='width:100px' readOnly>																
								<img style='padding-top:4px' src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'>
							</td>														
						</tr>
					</table>
				</div>
				<div id='cp_dadosContrato'>
					<div id='cp_tit'><?=dicionario(454)?></div>
					<table cellpadding='0' cellspacing='0' style='margin:0'>
						<tr>
							<td style='margin:0; vertical-align:top;'>
								<table style='margin:0'>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B><?=dicionario(140)?></B></td>
										<td class='separador'>&nbsp;</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<select name='IdStatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:232px' onChange="verifica_status(this.value); desativar_confirmar(document.formulario.IdStatusAnterior,document.formulario.IdStatus)" tabindex='1'>
												<option value='0' selected></option>
												<?	
													$aux = "";
													if($local_IdStatus > 200 && $local_IdStatus < 299){
														if($local_IdStatus == 204){
															$aux = " and IdParametroSistema != 204 and IdParametroSistema != 306";
														}	
													}
													if($local_IdStatus > 300 && $local_IdStatus < 399){
														if($local_IdStatus == 306){
															$aux = " and IdParametroSistema != 306 and IdParametroSistema != 204";
														}	
													}
													
													$sql = "select 
																IdParametroSistema, 
																ValorParametroSistema 
															from 
																ParametroSistema 
															where 
																IdGrupoParametroSistema = 69 and 
																IdParametroSistema not in (205, 305, 304, 101, 102, 203, $local_IdStatus) 
																$aux
															order by 
																ValorParametroSistema";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														if($lin[IdParametroSistema] > 1){														
															echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
														}else{															
															if(permissaoSubOperacao($localModulo,$localOperacao,"C") == true){					
																if($local_IdStatus > 1){
																	echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";										
																}
															}											
														}
													}
												?>
											</select>
										</td>
										<td class='separador'>&nbsp;</td>
									</tr>
								</table>
							</td>
							<td style='margin:0; vertical-align:top;'>
								<table id='tableAtivoTemp'  style='display:none; margin:0'>
									<tr>
										<td class='descCampo'><B id='titDataBloqueio'><?=dicionario(676)?></B></td>
										<td class='find'>&nbsp;</td>
										<td class='separador'>&nbsp;</td>
									</tr>
									<tr>
										<td class='campo'>
											<input type='text' name='DataBloqueioStatus' id='cpDataBloqueioStatus' value='' style='width:120px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')"  onChange="validar_Data('titDataBloqueio',this);" tabindex='2'>
										</td>
										<td class='find' id='imgDataBloqueioStatus'><img id='cpDataBloqueioStatusIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
										<script type="text/javascript">
										    Calendar.setup({
										        inputField     : "cpDataBloqueioStatus",
										        ifFormat       : "%d/%m/%Y",
										        button         : "cpDataBloqueioStatusIco"
										    });
										</script>
										<td class='separador'>&nbsp;</td>
									</tr>
								</table>
								<table id='tableCancelado'  style='display:none;'>
									<tr>
										<td class='descCampo'><B id='titDataUltimaCobranca'><?=dicionario(434)?>.</B></td>
										<td class='find'>&nbsp;</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><B id='titDataTermino'><?=dicionario(751)?>.</B></td>
										<td class='find'>&nbsp;</td>
										<td class='separador'>&nbsp;</td>
									</tr>
									<tr>
										<td class='campo'>
											<input type='text' name='DataUltimaCobrancaStatus' id='cpDataUltimaCobrancaStatus' value='' style='width:120px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')"  onChange="validar_Data('titDataUltimaCobranca',this);" tabindex='3'>
										</td>
										<td class='find' id='imgDataUltimaCobrancaStatus'><img id='cpDataUltimaCobrancaStatusIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
										<script type="text/javascript">
										    Calendar.setup({
										        inputField     : "cpDataUltimaCobrancaStatus",
										        ifFormat       : "%d/%m/%Y",
										        button         : "cpDataUltimaCobrancaStatusIco"
										    });
										</script>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='DataTerminoStatus' id='cpDataTerminoStatus' value='' style='width:120px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')"  onChange="validar_Data('titDataTermino',this);" tabindex='4'>
										</td>
										<td class='find' id='imgDataTerminoStatus'><img id='cpDataTerminoStatusIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
										<script type="text/javascript">
										    Calendar.setup({
										        inputField     : "cpDataTerminoStatus",
										        ifFormat       : "%d/%m/%Y",
										        button         : "cpDataTerminoStatusIco"
										    });
										</script>
										<td class='separador'>&nbsp;</td>
									</tr>
								</table>
							</td>
							<td style='margin:0;'>
								<table>
									<tr>
										<td class='descCampo'><span id='titEmailNotificacao'><?=dicionario(764)?></span></td>
										<td class='find'>&nbsp;</td>
									</tr>
									<tr>
										<td class='campo'>
											<input type='text' name='EmailNotificacao' value='' style='width:232px' maxlength='255' autocomplete="off" onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out'); validar_Email(this.value,'titEmailNotificacao')" tabindex='5'>
										</td>
										<td class='find' onClick='JsMail(document.formulario.EmailNotificacao.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr  style='margin:0'>
							<table id='avisoAtivo'style='display:none; margin:0; padding:0'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td colspan='3'>Somente preencher o campo "Data Reativação" caso deseja isentar o período cancelado do cliente.</td>
								</tr>
							</table>
						</tr>
					</table>
					<table id='tableObs' style='margin-top:3px'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='cpObs'><?=dicionario(159)?></B></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' id='cp_Obs'>
								<textarea name='Obs' style='width: 816px;' rows=5 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='6'></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_conta_receber' style='display:none'>
					<div id='cp_tit' style='margin-bottom:0'><?=dicionario(761)?></div>
					<table id='tabelaContaReceber' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width:10px;'><input style='border:0' type='checkbox' name='todos_cr' onClick='selecionar(this)' tabindex='7'></td>
							<td style='width:40px; padding-left:5px;'><?=dicionario(141)?></td>
							<td style='padding-left:5px;'><?=dicionario(423)?>.</td>
							<td><?=dicionario(760)?>.</td>
							<td><?=dicionario(285)?>.</td>
							<td><?=dicionario(409)?>.</td>
							<td class='valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
							<td><?=dicionario(229)?></td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='3' id='tabelaTotal'><?=dicionario(128)?>: 0</td>
							<td colspan='3'>&nbsp;</td>
							<td class='valor' id='tabelaTotalValor'>0,00</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div id='cp_log' style='display:none'>
					<div id='cp_tit'><?=dicionario(665)?></div>
					<div id='cpVoltarDataBase'></div>
					<table id='ObsCancelamento'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(665)?></B></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='ObsCancelamento' style='width:816px;' rows='5' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='101'></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div id='cpLancamentoFinanceiroAguardandoCobranca' style='margin-top:10px'>
					<div id='cp_tit' style='margin:0'><?=dicionario(765)?></div>
					<table id='tabelaLancamentoFinanceiro' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'><?=dicionario(141)?></td>
							<td><?=dicionario(82)?></td>
							<td><?=dicionario(201)?></td>
							<td><?=dicionario(85)?></td>
							<td><?=dicionario(125)?></td>
							<td><?=dicionario(672)?>.</td>
							<td><?=dicionario(202)?></td>
							<td class='valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'><?=dicionario(673)?> (<?=getParametroSistema(5,1)?>)</td>
							<td><?=dicionario(140)?></td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td id='tabelaTotalLancamentoFinanceiro' class='tableListarEspaco' colspan='7'><?=dicionario(128)?>: 0</td>
							<td id='cpValorTotalLancamentoFinanceiro' class='valor'>0,00</td>
							<td id='cpDescTotalLancamentoFinanceiro' class='valor'>0,00</td>
							<td>&nbsp;</td>
							<td class='find'>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div id='bl_Protocolo' style='display:none;'>
					<div id='cp_tit'><?=dicionario(35)?></div>
					<table id='ObsCancelamento'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(719)?></B></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ProtocoloAssunto' value='' style='width:816px' maxlength='10' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='8' />
							</td>
						</tr>
					</table>
					<table id='ObsCancelamento'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(766)?></B></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='ProtocoloObservacao' style='width:816px;' rows='5' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='101'></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='button' name='bt_protocolo' value='Gerar Protocolo' class='botao' tabindex='102' onClick="abrir_protocolo()">
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' style='text-align:right;'>
								<input type='button' name='bt_voltar' value='Voltar' class='botao' tabindex='102' onClick="voltar()">
								<input type='submit' name='bt_alterar' value='Confirmar' class='botao' tabindex='103' onClick="cadastrar('alterar')">
								<input type='submit' name='bt_cancelar' value='Confirmar Cancelamento' class='botao' style='display:none;' tabindex='104' onClick="cancelar_conta_receber(document.formulario.CancelarContaReceber.value)">
							</td>
						</tr>
					</table>
					<!--table style='float:right; margin-right:6px;' border='0'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_voltar' value='Voltar' class='botao' tabindex='102' onClick="voltar()">
							</td>
							<td class='campo' id='bt_alterar'>	
								<input type='submit' name='bt_alterar' value='Confirmar' class='botao' tabindex='103' onClick="cadastrar('alterar')">
							</td>
							<td class='campo' id='bt_cancelar' style='display:none;'>	
								<input type='submit' name='bt_cancelar' value='Confirmar Cancelamento' class='botao' tabindex='104' onClick="cancelar_conta_receber(document.formulario.CancelarContaReceber.value)">
							</td>
						</tr>
					</table-->
					<table style='height:28px;'>
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
		if($local_IdContrato != ""){
			echo "busca_contrato($local_IdContrato,false,document.formulario.Local.value);";
			echo "scrollWindow('bottom');";
		}
	?>
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>