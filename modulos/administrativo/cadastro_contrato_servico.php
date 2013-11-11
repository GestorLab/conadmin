<?
	$localModulo		=	1;
	$localOperacao		=	2;
	$localSuboperacao	=	"V";
	$localModulo2		=	1;
	$localOperacao2		=	17;

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login						= $_SESSION["Login"];
	$local_IdLoja						= $_SESSION["IdLoja"];
	$local_IdAgenteAutorizadoLogin		= $_SESSION["IdAgenteAutorizado"];
	$local_IdPessoaLogin				= $_SESSION["IdPessoa"];
	$local_Acao 						= $_POST['Acao'];
	$local_Erro							= $_GET['Erro'];
	$local_QuantParametros				= $_POST['QuantParametros'];
	
	$local_IdContrato					= $_POST['IdContrato'];
	$local_IdServico					= $_POST['IdServico'];
	$local_IdPeriodicidade				= $_POST['IdPeriodicidade'];
	$local_QtdParcela					= $_POST['QtdParcela'];
	$local_ValorServico					= formatText($_POST['ValorServico'],NULL);
	$local_DataCancelamento				= formatText($_POST['DataCancelamento'],NULL);
	$local_ServicoAutomatico			= formatText($_POST['ServicoAutomatico'],NULL);
	$local_IdAgenteAutorizado			= formatText($_POST['IdAgenteAutorizado'],NULL);
	$local_IdCarteira					= formatText($_POST['IdCarteira'],NULL);
	$local_IdContratoTipoVigencia		= formatText($_POST['IdContratoTipoVigencia'],NULL);
	$local_IdCarteiraTemp				= formatText($_POST['IdCarteiraTemp'],NULL);
	$local_ValorRepasseTerceiro			= formatText($_POST['ValorRepasseTerceiro'],NULL);	
	$local_DiaCobranca					= formatText($_POST['DiaCobranca'],NULL);
	$local_NotaFiscalCDA				= formatText($_POST['NotaFiscalCDA'],NULL);
	$local_CFOPServico					= formatText($_POST['CFOPServico'],NULL);
	
	if($_GET['IdContrato']!=''){
		$local_IdContrato	=	$_GET['IdContrato'];
	}
	
	$local_IdContratoAnterior = $local_IdContrato;
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
		<script type = 'text/javascript' src = '../../js/mascara_real.js'></script>
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_servico.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js'></script>
		<script type = 'text/javascript' src = 'js/agente_default.js'></script>
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
			<form id="form" name='formulario' method='post' action='files/editar/editar_contrato_servico.php'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='IdAgenteAutorizadoLogin' value=<?=$local_IdAgenteAutorizadoLogin?>>
				<input type='hidden' name='IdPessoaLogin' value=<?=$local_IdPessoaLogin?>>
				<input type='hidden' name='Local' value='ContratoServico'>
				<input type='hidden' name='QuantParametros' value=''>
				<input type='hidden' name='QuantParametrosLocalCobranca' value=''>
				<input type='hidden' name='Periodicidade' value=''>
				<input type='hidden' name='ParametrosAnterior' value=''>
				<input type='hidden' name='IdContratoAnterior' value=''>
				<input type='hidden' name='QuantParcela' value=''>
				<input type='hidden' name='ServicoAutomaticoAnterior' value=''>
				<input type='hidden' name='ServicoAutomatico' value=''>
				<input type='hidden' name='IdStatus' value=''>
				<input type='hidden' name='MultaFidelidade' value=''>
				<input type='hidden' name='MoedaAtual' value='<?=getParametroSistema(5,1)?>'>
				<input type='hidden' name='TipoContratoTemp' value=''>
				<input type='hidden' name='MesFechadoTemp' value=''>
				<input type='hidden' name='IdLocalCobrancaTemp' value=''>
				<input type='hidden' name='IdTipoServico' value=''>
				<input type='hidden' name='DiaCobrancaDefault' value='<?=getCodigoInterno(3,6)?>'>
				<input type='hidden' name='DiaCobrancaTemp' value=''>
				<input type='hidden' name='IdContratoTipoVigencia' value=''>
				<input type='hidden' name='IdCarteiraTemp' value=''>
				<input type='hidden' name='ValorFinal' value=''>
				<input type='hidden' name='NotaFiscalCDADefault' value=''>
				<input type='hidden' name='Terceiros' value=''>
				<input type='hidden' name='ObrigatorioTerceiro' value='<?=getCodigoInterno(11,5)?>'>
				<input type='hidden' name='CancelarContaReceber' value='' />
				<input type='hidden' name='TabIndex' value='61'>
				<input type='hidden' name='LancamentoFinanceiroTipoContrato' value=''>
				<input type='hidden' name='PermissaoCancelarContaReceber' value='<?=permissaoSubOperacao($localModulo2,$localOperacao2,"C")?>' />
				<input type='hidden' name='SeletorContaCartao' value=''>
				<input type='hidden' name='ObrigatoriedadeContaCartao' value=''>
				<input type='hidden' name='SelecionarCamposUmaOpcao' value='<?=getCodigoInterno(3,237)?>'>
				
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
									<td class='descCampo'>CNPJ</td>
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
										<input type='text' name='CNPJ' value='' style='width:149px' maxlength='18' readOnly>
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
										<input type='text' name='CNPJ' value='' style='width:149px' maxlength='18' readOnly>
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
								<input type='text' name='CPF' value='' style='width:123px' maxlength='18' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosContrato'>
					<div id='cp_tit'><?=dicionario(750)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:32px'><?=dicionario(30)?></b><?=dicionario(223)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(224)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(225)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(226)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdServicoAnterior' value=''  style='width:70px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" readOnly><input type='text' class='agrupador' name='DescricaoServicoAnterior' value='' style='width:377px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescPeriodicidadeAnterior' value=''  style='width:134px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdParcelaAnterior' value=''  style='width:90px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='TipoContratoAnterior' value=''  style='width:89px' readOnly>
							</td>
						</tr>
					</table>
					<table  id='cpInserirContrato'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(40)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(227)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(237)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(240)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(229)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LocalCobrancaAnterior' value=''  style='width:309px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='MesFechadoAnterior' value=''  style='width:75px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorServicoAnterior' value='' style='width:150px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorPeriodicidadeAnterior' value='' style='width:150px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td>
								<select name='DiaCobrancaAnterior' tabindex='10' style='width: 70px' disabled>
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
							<td class='descCampo'><?=dicionario(517)?>.</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(232)?></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(751)?>.</td>
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
								<input type='text' name='DataInicio' id='cpDataInicio' value='' style='width:105px' maxlength='10' readOnly>
							</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataPrimeiraCobranca' id='cpDataPrimeiraCobranca' value='' style='width:105px' maxlength='10' readOnly>
							</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
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
							<td class='find'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='AssinaturaContrato' style='width:103px' disabled>
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
					<table id='cpAgenteCarteiraAnterior'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(32)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(117)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(33)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdAgenteAutorizadoAnterior' style='width:280px' disabled>
									<option value=''>&nbsp;</option>
									<?
										$sql = "SELECT
													AgenteAutorizado.IdAgenteAutorizado,
													if(Pessoa.RazaoSocial != '', Pessoa.RazaoSocial, Pessoa.Nome) Nome
												from
													AgenteAutorizado,
													Pessoa
												where
													AgenteAutorizado.IdLoja = $local_IdLoja and
													AgenteAutorizado.IdAgenteAutorizado = Pessoa.IdPessoa and
													AgenteAutorizado.IdStatus = 1;";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdAgenteAutorizado]'>$lin[Nome]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select type='text' name='IdCarteiraAnterior' value='' style='width:260px' disabled>
									<option value=''></option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select type='text' name='IdTerceiroAnterior' value='' style='width:260px' disabled>
									<option value=''>&nbsp;</option>
								</select>
							</td>
						</tr>
					</table>
					<table  id='cpInserirContrato'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(241)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(752)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(243)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(244)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' id='label_IdContaDebitoCartaoAnterior'></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorRepasseTerceiroAnterior' value='' style='width:150px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorPeriodicidadeTerceiroAnterior' value='' style='width:150px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdMesesFidelidadeAnterior' value='' style='width:130px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorMultaFidelidadeAnterior' value='' style='width:149px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>
								<select name='IdContaDebitoCartaoAnterior' disabled style='width: 174.5px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='22'/>
									<option>&nbsp;</option>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosContrato'>
					<div id='cp_tit'><?=dicionario(753)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:34px'><?=dicionario(30)?></B><?=dicionario(223)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(224)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpQtdParcela'><?=dicionario(225)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpTipoContrato'><?=dicionario(226)?></B></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaServico', true, event, null, 453); document.formularioServico.DescricaoServico.value=''; valorCampoServico = ''; busca_servico_lista(); document.formularioServico.DescricaoServico.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdServico' value=''  style='width:70px' maxlength='11' onChange="busca_servico(this.value,true,document.formulario.Local.value,'busca');" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:371px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdPeriodicidade' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:149px' tabindex='2' onChange="servico_periodicidade_parcelas(document.formulario.IdServico.value,this.value,'busca');calculaPeriodicidade(this.value,document.formulario.ValorServico.value);  calculaPeriodicidadeTerceiroServico(document.formulario.IdPeriodicidade.value,document.formulario.ValorRepasseTerceiro.value,document.formulario.ValorPeriodicidadeTerceiro); calculaServicoAutomatico(this.value);selecionaCampos()">
									<option value='' selected></option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='QtdParcela' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:100px' tabindex='3' onChange="document.formulario.QuantParcela.value=document.formulario.QtdParcela.value; busca_servico_tipo_contrato(document.formulario.IdServico.value,document.formulario.IdPeriodicidade.value,this.value,'busca')">
									<option value='0' selected></option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='TipoContrato' style='width:88px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='4' OnChange="document.formulario.TipoContratoTemp.value=document.formulario.TipoContrato.value; busca_servico_local_cobranca(document.formulario.IdServico.value,document.formulario.IdPeriodicidade.value,document.formulario.QtdParcela.value,this.value,'busca');">
									<option value='0'>&nbsp;</option>
								</select>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='cpLocalCobranca'><?=dicionario(40)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpMesFechado'><?=dicionario(227)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(237)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(240)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(229)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdLocalCobranca' onFocus="Foco(this,'in')"  style='width:315px' onBlur="Foco(this,'out')" tabindex='5' onChange="document.formulario.IdLocalCobrancaTemp.value=document.formulario.IdLocalCobranca.value; busca_servico_mes_fechado(document.formulario.IdServico.value,document.formulario.IdPeriodicidade.value,document.formulario.QtdParcela.value,document.formulario.TipoContrato.value,this.value,'busca'); listarLocalCobrancaParametro(this.value,true);verificar_local_cobranca(this.value)">
									<option value='0'>&nbsp;</option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='MesFechado' onFocus="Foco(this,'in')"  style='width:81px' onBlur="Foco(this,'out')" tabindex='6' onChange="document.formulario.MesFechadoTemp.value=document.formulario.MesFechado.value; busca_meses_fidelidade(document.formulario.IdServico.value,document.formulario.IdPeriodicidade.value,document.formulario.QtdParcela.value,document.formulario.TipoContrato.value,document.formulario.IdLocalCobranca.value,this.value,'busca')">
									<option value='0'>&nbsp;</option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorServico' value='' style='width:150px' maxlength='15' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onBlur="Foco(this,'out'); calculaPeriodicidadeServico(document.formulario.IdPeriodicidade.value,this.value,document.formulario.ValorPeriodicidade)"  tabindex='7'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorPeriodicidade' value='' style='width:150px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td>
								<select name='DiaCobranca' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='8' style='width: 70px'>
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
					<table id='cpAgenteCarteira'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000' id='cpAgenteAutorizado'><?=dicionario(32)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000' id='cpCarteira'><?=dicionario(117)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000' id='cpTerceiro'><?=dicionario(33)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdAgenteAutorizado' style='width:280px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="listar_carteira(this.value);" tabindex='9'>
									<option value=''>&nbsp;</option>
									<?
										$sql = "SELECT
													AgenteAutorizado.IdAgenteAutorizado,
													if(Pessoa.RazaoSocial != '', Pessoa.RazaoSocial, Pessoa.Nome) Nome
												from
													AgenteAutorizado,
													Pessoa
												where
													AgenteAutorizado.IdLoja = $local_IdLoja and
													AgenteAutorizado.IdAgenteAutorizado = Pessoa.IdPessoa and
													AgenteAutorizado.IdStatus = 1;";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdAgenteAutorizado]'>$lin[Nome]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select type='text' name='IdCarteira' value='' style='width:260px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='10' disabled>
									<option value=''>&nbsp;</option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select type='text' name='IdTerceiro' value='' style='width:260px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onchange="obter_valor_repasse_terceiro(this.value);" tabindex='11' disabled>
									<option value=''>&nbsp;</option>
								</select>
							</td>
						</tr>
					</table>
					<table  id='cpInserirContrato'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(241)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(752)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(243)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(244)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='DataCancelamento'><?=dicionario(667)?></B></td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorRepasseTerceiro' value='' style='width:150px' maxlength='15' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out'); calculaPeriodicidadeTerceiroServico(document.formulario.IdPeriodicidade.value,this.value,document.formulario.ValorPeriodicidadeTerceiro)" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" tabindex='12'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorPeriodicidadeTerceiro' value='' style='width:150px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdMesesFidelidade' value='' style='width:130px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorMultaFidelidade' value='' style='width:150px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCancelamento' id='cpDataCancelamento' value='' style='width:146px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('DataCancelamento',this); verificaCredito(document.formulario.IdServico.value,document.formulario.DataBaseCalculo.value,this.value)" tabindex='12'>
							</td>
							<td class='find'><img id='cpDataCancelamentoIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataCancelamento",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataCancelamentoIco"
							    });
							</script>
						</tr>
					</table>
					<table cellpadding='0' cellspacing='0'>
						<td>
							<table id='cpIdContaDebitoCartao' style='display: none;'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo' id='label_IdContaDebitoCartao' style='color: #C10000'></td>
								</tr>
								<tr>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>
										<select name='IdContaDebitoCartao' style='width: 174.5px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='13'/>
											<option></option>
										</select>
									</td>
								</tr>
							</table>
						</td>
						<td>								
							<table id='cpNotaFiscalCDA' style='display:none;' >
								<tr>		
									<td class='find' id='espacamentoNotaFiscal'>&nbsp;</td>
									<td class='descCampo'><?=dicionario(246)?></td>
								</tr>
								<tr>	
									<td >&nbsp;</td>										
									<td>											
										<select name='NotaFiscalCDA' style='width:205px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='14'>
											<option value='0'>&nbsp;</option>
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=133 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
							</table>
						</td>
						<td>
							<table id="cpServicoCFOP" style="display:none;">
								<tr>
									<td id="spServicoCFOP" class="separador">&nbsp;</td>
									<td class="descCampo"><B><?=dicionario(247)?></B></td>
								</tr>
								<tr>
									<td class="separador">&nbsp;</td>
									<td>
										<select name='CFOPServico' style='width:605px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='15'>
											<option value='0'>&nbsp;</option>
										</select>
									</td>
								</tr>
							</table>
						</td>
					</table>
				</div>
				<div id='cp_parametrosServico' style='margin-bottom:0; display:none;'>
					<div id='cp_tit'><?=dicionario(350)?></div>
					<table id='tabelaParametro'>
					</table>	
				</div>
				<div id='cp_parametrosLocalCobranca' style='margin-bottom:0; display:none'>
					<div id='cp_tit'><?=dicionario(263)?></div>
					<table id='tabelaParametroLocalCobranca'>
						<tr><td></td></tr>
					</table>
				</div>
				<div id='cp_automatico' style='display:none'></div>
				<div class='cp_botao'>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:right;'>
								<input type='submit' style='padding:0; margin:0;' name='bt_alterar' value='Confirmar' class='botao' tabindex='1000'>
							</td>
						</tr>
					</table>
					<table style='height:28px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>	
				<div id='cp_credito' style='display:none'>
					<div id='cp_tit'><?=dicionario(754)?></div>
					<table id='tabelaCredito'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(755)?>.</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(756)?>.</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(27)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(30)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(376)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(433)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(757)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(758)?></B></td>
						</tr>
					</table>
				</div>
				<div id='cp_contasReceber' style='display:none'>
					<div id='cp_tit' style='margin:0'><?=dicionario(759)?></div>
					<table id='tabelaContasReceber' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'><?=dicionario(141)?></td>
							<td><?=dicionario(85)?></td>
							<td><?=dicionario(423)?>.</td>
							<td><?=dicionario(760)?></td>
							<td><?=dicionario(285)?>.</td>
							<td><?=dicionario(409)?>.</td>
							<td class='valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
							<td><?=dicionario(229)?></td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='6' id='tabelaTotal'><?=dicionario(128)?>: 0</td>
							<td id='cpValorTotal' class='valor'>0,00</td>
							<td>&nbsp;</td>
							<td class='find'>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div id='cp_contaReceber' style='display:none'>
					<div id='cp_tit' style='margin-bottom:0'><?=dicionario(761)?></div>
					<table id='tabelaContaReceber' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width:10px;'><input style='border:0' type='checkbox' name='todos_cr' onClick='selecionar(this)' tabindex='60'></td>
							<td style='width:40px; padding-left:5px;'><?=dicionario(141)?></td>
							<td style='padding-left:5px;'><?=dicionario(423)?>.</td>
							<td><?=dicionario(760)?>.</td>
							<td><?=dicionario(285)?>.</td>
							<td><?=dicionario(409)?>.</td>
							<td class='valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
							<td style='text-align: center'><?=dicionario(229)?></td>
							<td><?=dicionario(762)?></td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='3' id='tabelaTotal2'>Total: 0</td>
							<td colspan='3'>&nbsp;</td>
							<td class='valor' id='tabelaTotalValor'>0,00</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
					<div id='cpVoltarDataBase'></div>
				</div>
				<div id='cpLancamentoFinanceiroAguardandoCobranca' style='display:none; margin-top:10px'>
					<div id='cp_tit' style='margin:0'><?=dicionario(763)?>div>
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
							<td id='tabelaTotalLancamentoFinanceiro' class='tableListarEspaco' colspan='7'>Total: 0</td>
							<td id='cpValorTotalLancamentoFinanceiro' class='valor'>0,00</td>
							<td id='cpDescTotalLancamentoFinanceiro' class='valor'>0,00</td>
							<td>&nbsp;</td>
							<td class='find'>&nbsp;</td>
						</tr>
					</table>
				</div>
			</form>
		</div>
		<div id='quadros_fluantes'>
			<?
				include("files/busca/servico_contrato.php");
				include("files/busca/agente.php");
			?>
		</div>
	</body>	
</html>
<script>
	function status_inicial(){
		if('<?=getCodigoInterno(13,1)?>' == '2'){
			document.getElementById('cpAgenteCarteira').style.display			=	'block';
			document.getElementById('cpAgenteCarteiraAnterior').style.display	=	'block';	
		}else{
			document.getElementById('cpAgenteCarteira').style.display			=	'none';	
			document.getElementById('cpAgenteCarteiraAnterior').style.display	=	'none';	
		}
		if('<?=getCodigoInterno(11,1)?>' == '1'){
			document.getElementById('cpAgenteAutorizado').style.color	=	'#C10000';	
			document.getElementById('cpCarteira').style.color			=	'#C10000';	
		}else{
			document.getElementById('cpAgenteAutorizado').style.color	=	'#000';	
			document.getElementById('cpCarteira').style.color			=	'#000';	
		}
		if(document.formulario.NotaFiscalCDA.value == 0 && document.formulario.IdContrato.value == ""){
			document.formulario.NotaFiscalCDA.value	= '';
			document.formulario.NotaFiscalCDADefault.value 	= '<?=getCodigoInterno(3,110)?>';				
		}
	}
	function validar(){
		if(document.formulario.IdServico.value==''){
			mensagens(1);
			document.formulario.IdServico.focus();
			return false;
		}
		if(document.formulario.IdPeriodicidade.value==''){
			mensagens(1);
			document.formulario.IdPeriodicidade.focus();
			return false;
		}
		if(document.formulario.QtdParcela.value==''){
			mensagens(1);
			document.formulario.QtdParcela.focus();
			return false;
		}
		if(document.formulario.TipoContrato.value==''){
			mensagens(1);
			document.formulario.TipoContrato.focus();
			return false;
		}		
		if(document.formulario.IdLocalCobranca.value==''){
			mensagens(1);
			document.formulario.IdLocalCobranca.focus();
			return false;
		}
		if(document.formulario.MesFechado.value==''){
			mensagens(1);
			document.formulario.MesFechado.focus();
			return false;
		}
		if(document.formulario.ValorServico.value==''){
			mensagens(1);
			document.formulario.ValorServico.focus();
			return false;
		}
		if(document.formulario.DiaCobranca.value=='' || document.formulario.DiaCobranca.value=='0'){
			mensagens(1);
			document.formulario.DiaCobranca.focus();
			return false;
		}
		if('<?=getCodigoInterno(13,1)?>' == '2'){
			if('<?=getCodigoInterno(11,1)?>' == '1'){
				if(document.formulario.IdAgenteAutorizado.value==''){
					mensagens(1);
					document.formulario.IdAgenteAutorizado.focus();
					return false;
				}
			}
		}
		if(Number(document.formulario.ObrigatorioTerceiro.value) == 1 && !document.formulario.IdTerceiro.disabled && document.formulario.IdTerceiro.value == '') {
			mensagens(1);
			document.formulario.IdTerceiro.focus();
			return false;
		}
		if(document.formulario.ValorRepasseTerceiro.value==''){
			mensagens(1);
			document.formulario.ValorRepasseTerceiro.focus();
			return false;
		}
		if(document.formulario.DataCancelamento.value==''){
			mensagens(1);
			document.formulario.DataCancelamento.focus();
			return false;
		}else{
			if(isData(document.formulario.DataCancelamento.value) == false){		
				mensagens(27);
				document.formulario.DataCancelamento.focus();
				return false;
			}
		}
		
		if(document.formulario.IdContaDebitoCartao.value == "" && document.formulario.ObrigatoriedadeContaCartao.value == 1){
			mensagens(1);
			document.formulario.IdContaDebitoCartao.focus();
			return false;
		}
		
		if(document.getElementById("cpServicoCFOP").style.display != "none" && document.formulario.CFOPServico.value == ''){
			mensagens(1);
			document.formulario.CFOPServico.focus();
			return false;
		}
		var posInicial=0,posFinal=0;
		for(i = 0; i<document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,6) == 'Valor_'){
					if(posInicial == 0){
						posInicial = i;
					}
					posFinal = i;
				}
			}
		}
		if(posInicial != 0){
			for(i = posInicial; i<=posFinal; i++){
				if(document.formulario[i].name.substring(0,6) == 'Valor_'){
					if(document.formulario[i+1].value == '1'){
						if(document.formulario[i].type == 'text'){
						 	if(document.formulario[i].value == '' && document.formulario[i+2].value == '1'){
								mensagens(1);
								document.formulario[i].focus();
								return false;
							}
						}
						if(document.formulario[i].type == 'select-one'){
							var cont = 0;
							for(j=0;j<document.formulario[i].options.length;j++){
								//alert(document.formulario[i].name+" "+document.formulario[i][j].value+" "+document.formulario[i][j].selected);
							 	if(document.formulario[i][j].selected == true && (document.formulario[i][j].value != "" && document.formulario[i][j].value != "0")){
							 		cont++;
							 		break;
								}
							}
							if(cont == 0 && document.formulario[i+2].value == '1'){
								mensagens(1);
								document.formulario[i].focus();
								return false;
							} 	
						}
					}
				}
			}
		}
		var posInicial=0,posFinal=0,temp=0;
		if(document.formulario.IdLocalCobranca.value!=''){
			for(i = 0; i<document.formulario.length; i++){
				if(document.formulario[i].name != undefined){
					if(document.formulario[i].name.substring(0,20) == 'LocalCobranca_Valor_'){
						if(posInicial == 0){
							posInicial = i;
						}
						posFinal = i;
					}
				}
			}
			if(posInicial != 0){
				for(i = posInicial; i<=posFinal; i=i+3){
					if(document.formulario[i+1].value == '1'){
						if(document.formulario[i].type == 'text'){
						 	if(document.formulario[i].value == '' && document.formulario[i+2].value == '1'){
								mensagens(1);
								document.formulario[i].focus();
								return false;
							}
						}
						if(document.formulario[i].type == 'select-one'){
							var cont = 0;
							 for(j=0;j<document.formulario[i].options.length;j++){
							 	if(document.formulario[i][j].selected == true && (document.formulario[i][j].value != "" && document.formulario[i][j].value != "0")){
							 		cont++;
							 		break;
								}
							}
							if(cont == 0 && document.formulario[i+2].value == '1'){
								mensagens(1);
								document.formulario[i].focus();
								return false;
							} 	
						}
					}
				}
			}
		}
		if(document.formulario.ServicoAutomatico.value != ""){
			temp	=	document.formulario.ServicoAutomatico.value.split("#");
			
			posInicial=0;
			posFinal=0;
			for(ii = 0; ii<document.formulario.length; ii++){
				if(document.formulario[ii].name != undefined){
					if(document.formulario[ii].name.substring(0,16) == 'ValorAutomatico_'){
						if(posInicial == 0){
							posInicial = ii;
						}
						posFinal = ii;
					}
				}
			}
			if(posInicial != 0){
				for(ii = posInicial; ii<=posFinal; ii++){
					if(document.formulario[ii].name.substring(0,16) == 'ValorAutomatico_'){
						if(document.formulario[ii+1].value == '1'){
							if(document.formulario[ii].type == 'text'){
							 	if(document.formulario[ii].value == '' && document.formulario[ii+2].value == '1'){
									mensagens(1);
									document.formulario[ii].focus();
									return false;
								}
							}
							if(document.formulario[ii].type == 'select-one'){
								var cont = 0;
								 for(j=0;j<document.formulario[ii].options.length;j++){
								 	if(document.formulario[ii][j].selected == true && (document.formulario[ii][j].value != "" && document.formulario[ii][j].value != "0")){
								 		cont++;
								 		break;
									}
								}
								if(cont == 0 && document.formulario[ii+2].value == '1'){
									mensagens(1);
									document.formulario[ii].focus();
									return false;
								}
							}
						}
					}
				}
			}	
		}
		for(ii = 0; ii<document.formulario.length; ii++){
			if(document.formulario[ii].name != undefined){
				if(document.formulario[ii].name.substring(0,10) == 'Autorizar_'){
					if(document.formulario[ii].value == '' || document.formulario[ii].value == 0){
						mensagens(1);
						document.formulario[ii].focus();
						return false;
					}
				}
			}
		}
		
		posInicial=0;
		posFinal=0;
		
		for(ii = 0; ii<document.formulario.length; ii++){
			if(document.formulario[ii].name != undefined){
				if(document.formulario[ii].name.substring(0,11) == 'IdTerceiro_'){
					if(posInicial == 0){
						posInicial = ii;
					}
					
					posFinal = ii;
				}
			}
		}
		
		if(posInicial != 0){
			for(ii = posInicial; ii<=posFinal; ii++){
				if(document.formulario[ii].name.substring(0,11) == 'IdTerceiro_' && !document.formulario[ii].disabled){
					if(document.formulario[ii].value == ''){
						mensagens(1);
						document.formulario[ii].focus();
						return false;
					}
				}
			}
		}	
		
		mensagens(0);
		return true;
	}	

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