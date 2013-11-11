<?
	$localModulo		=	1;
	$localOperacao		=	17;
	$localSuboperacao	=	"V";	
	$Path				=   "../../";

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	include ('../../classes/envia_mensagem/envia_mensagem.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_IdPessoaLogin	= $_SESSION["IdPessoa"];
	$local_Acao				= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdContaReceber				= $_POST['IdContaReceber'];
	$local_IdPessoa						= $_POST['IdPessoa'];
	$local_DataRecebimento				= formatText($_POST['DataRecebimento'],NULL);	
	$local_DataVencimento				= formatText($_POST['DataVencimento'],NULL);		
	$local_IdLocalRecebimento			= formatText($_POST['IdLocalRecebimento'],NULL);
	$local_ValorMoraMulta				= formatText($_POST['ValorMoraMulta'],NULL);
	$local_ValorDesconto				= formatText($_POST['ValorDesconto'],NULL);
	$local_ValorDespesas				= formatText($_POST['ValorDespesas'],NULL);
	$local_IdStatus						= formatText($_POST['IdStatus'],NULL);
	$local_NumeroNF						= formatText($_POST['NumeroNF'],NULL);
	$local_Obs							= formatText($_POST['Obs'],NULL);
	$local_ValorDescontoRecebimento		= formatText($_POST['ValorDescontoRecebimento'],NULL);
	$local_ValorOutrasDespesas			= formatText($_POST['ValorOutrasDespesas'],NULL);
	$local_ValorReceber					= formatText($_POST['ValorReceber'],NULL);
	$local_DataNF						= formatText($_POST['DataNF'],NULL);	
	$local_ModeloNF						= formatText($_POST['ModeloNF'],NULL);	
	$local_IdPosicaoCobranca			= formatText($_POST['IdPosicaoCobranca'],NULL);
	$local_IdPessoaEndereco				= formatText($_POST['IdPessoaEndereco'],NULL);
	$local_ObsNotaFiscal				= formatText($_POST['ObsNotaFiscal'],NULL);
	$local_IdPessoaEnderecoTemp			= formatText($_POST['IdPessoaEnderecoTemp'],NULL);
	$local_NumeroCartaoCredito			= $_POST['NumeroCartaoCredito'];
	$local_IdContaDebito				= $_POST['IdContaDebito'];
	$local_NumeroContaDebito			= $_POST['NumeroContaDebito'];
	$local_NumeroCartaoCreditoAux		= $_POST['NumeroCartaoCreditoAux'];
	$local_IdLocalCobranca				= $_POST['IdLocalCobranca'];
	$local_IdLocalCobrancaTemp			= $_POST['IdLocalCobrancaTemp'];
	$local_IdContaCartaoAux				= "";
	$local_NossoNumero					= $_GET['NossoNumero'];
	
	if($local_NumeroCartaoCredito != ""){
		$local_IdContaCartaoAux = $local_NumeroCartaoCredito;
	}else{
		$local_NumeroCartaoCredito = "NULL";
	}
	
	if($local_IdContaDebito != ""){
		$local_IdContaCartaoAux = $local_IdContaDebito;
	}else{
		$local_IdContaDebito = "NULL";
	}         	
	if($_GET['IdContaReceber']!=''){
		$local_IdContaReceber	=	$_GET['IdContaReceber'];
	}
	
	if($_GET['IdRecibo']!=''){
		$local_IdRecibo = $_GET['IdRecibo'];
	}

	if($local_NossoNumero != ''){
		$sqlNossoNumero = "select 
							*							
						from 
							ContaReceber
						where
							ContaReceber.IdLoja = $local_IdLoja and
							ContaReceber.NossoNumero = $local_NossoNumero";							
		$resNossoNumero = @mysql_query($sqlNossoNumero, $con);
		$linNossoNumero = @mysql_fetch_array($resNossoNumero);
		$local_NossoNumero = $linNossoNumero[IdContaReceber];
	}
	
	$sql17 = "select 
							*							
						from 
							ContaReceber
						where
							ContaReceber.IdLoja = $IdLoja and
							ContaReceber.IdContaReceber = $lin[IdContaReceber]";							
				$res17 = @mysql_query($sql17, $con);
	
	
	switch ($local_Acao){
		case 'alterar':
			include('files/editar/editar_conta_receber.php');
			break;
		case 'cancelar':
			header("Location: cadastro_cancelar_conta_receber.php?IdContaReceber=$local_IdContaReceber");
			break;
		case 'receber':
			include('files/editar/receber_conta_receber.php');
			break;
		case 'enviar':
			header("Location: enviar_mensagem_conta_receber.php?IdContaReceber=$local_IdContaReceber");
			$local_Erro=64;
			break;
		case 'pagamento':
			header("Location: cadastro_informar_pagamento.php?IdContaReceber=$local_IdContaReceber&IdPessoa=$local_IdPessoa");
			break;
		default:
			$local_Acao = '';
			break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
	    <link rel='stylesheet' type='text/css' href='../../classes/calendar/calendar-blue.css' />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/mascara_real.js'></script>
		<script type='text/javascript' src='../../js/val_data.js'></script>
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>
		<script type='text/javascript' src='js/conta_receber.js'></script>
		<script type='text/javascript' src='js/conta_receber_busca_pessoa_aproximada.js'></script>
		<script type='text/javascript' src='js/conta_receber_default.js'></script>
		<script type='text/javascript' src='js/pessoa_default.js'></script>
		<script type='text/javascript' src='js/arquivo_remessa_tipo_default.js'></script>
		
    	<style type="text/css">
			input[type=text]:readOnly { background-color: #FFF; }
			input[type=datetime]:readOnly { background-color: #FFF; }
			input[type=date]:readOnly { background-color: #FFF; }
			textarea:readOnly { background-color: #FFF; }
			select:disabled { background-color: #FFF; }
			select:disabled { color: #000; }
			.ocultar-bl { display: none; }
		</style>
	</head>
	<body onLoad="ativaNome('<?=dicionario(56)?>');">
		<? include('filtro_conta_receber.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form id='form' name='formulario' method='post' action='cadastro_conta_receber.php'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
				<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
				<input type='hidden' name='IdStatus' value='<?=$local_IdStatus?>' />
				<input type='hidden' name='IdStatusRecebimento' value='' />
				<input type='hidden' name='QuantParametros' value='<?=$local_QuantParametros?>' />
				<input type='hidden' name='Local' value='ContaReceber' />
				<input type='hidden' name='IdTipoLocalCobranca' value='' />
				<input type='hidden' name='CorRecebidoDesc' value='<?=getParametroSistema(15,7)?>' />
				<input type='hidden' name='BaseVencimento' value='' />
				<input type='hidden' name='PercentualMulta' value='' />
				<input type='hidden' name='PercentualJurosDiarios' value='' />
				<input type='hidden' name='SiglaEstado' value='' />
				<input type='hidden' name='IdContaReceberAgrupador' value='2' />
				<input type='hidden' name='IdStatusConfirmacaoPagamento' value='' />
				<input type='hidden' name='PermissaoCancelarRecebimento' value='<?=permissaoSubOperacao($localModulo,171,"C")?>' />
				<input type='hidden' name='EditarDataRecebimento' value='<?=getCodigoInterno(3,183)?>' />
				<input type='hidden' name='DataAtual' value='<?=date("Y-m-d")?>' /> 
				<input type='hidden' name='DataVencimentoTemp' value='' />
				<input type='hidden' name='IdPessoaEnderecoTemp' value='' />
				<input type='hidden' name='CaixaAtivado' value='<?php echo getCodigoInterno(66,1); ?>' />
				<input type='hidden' name='ObrigatoriedadeNumeroCartao' value='' />
				<input type='hidden' name='ObrigatoriedadeNumeroContaDebito' value='' />
				<input type='hidden' name='NumeroContaDebito' value='' />
				<input type='hidden' name='NumeroCartaoCreditoAux' value='' />
				<input type='hidden' name='PermissaoListarPosicoesCobranca' value='<?=permissaoSubOperacao($localModulo,207,"R")?>' />
				<input type='hidden' name='IdLocalCobrancaTemp' value='' />
				
				<div id='cp_conta_receber'>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(670)?></td>
							<td class='separador'>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdContaReceber' value='' autocomplete="off" style='width:70px' onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" onChange="busca_conta_receber(this.value,false,document.formulario.Local.value); verificaAcao();" onkeypress="mascara(this,event,'int')" tabindex='1' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='descricao' style='width: 728px;'><B id='cp_Status'>&nbsp;</B></td>
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
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' readonly='readonly' /><input type='text' class='agrupador' name='Nome' value='' style='width:279px' maxlength='100' readonly='readonly' />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo' id='cp_RazaoSocial'>
										<input type='text' name='RazaoSocial' value='' style='width:279px' maxlength='100' readonly='readonly' />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='CNPJ' value='' style='width:149px' maxlength='18' readonly='readonly' />
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
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' readonly='readonly' /><input type='text' class='agrupador' name='RazaoSocial' value='' style='width:279px' maxlength='100' readonly='readonly' />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='Nome' value='' style='width:279px' maxlength='100' readonly='readonly' />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='CNPJ' value='' style='width:149px' maxlength='18' readonly='readonly' />
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
								<input type='text' name='IdPessoaF' value='' autocomplete="off" style='width:70px' maxlength='11' readonly='readonly' /><input type='text' class='agrupador' name='NomeF' value='' style='width:279px' maxlength='100' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Email' value='' style='width:283px' maxlength='255' readonly='readonly' />
							</td>
							<td class='find' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CPF' value='' style='width:123px' maxlength='18' readonly='readonly' />
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_lancamentos_financeiros'>
					<div id='cp_tit' style='margin-bottom:0'><?=dicionario(649)?></div>
					<table id='tabelaLancFinanceiro' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width: 40px'><?=dicionario(82)?></td>
							<td style='width: 55px'><?=dicionario(678)?>.</td>
							<td><?=dicionario(125)?></td>
							<td><?=dicionario(202)?></td>
							<td class='valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='2' id='tabelaLancFinanceiroTotal'></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td id='tabelaLancFinanceiroTotalValor' class='valor'>0,00</td>
						</tr>
					</table>
				</div>
				<div id='cp_listar_conta_receber' style='padding-top:6px'>
					<div id='cp_tit'><?=dicionario(679)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><font name='busca' id='LocalCobracaLabel'><?=dicionario(40)?></font></td> 
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(1051)?> </td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(1050)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(1049)?></td>
							<td class='separador'>&nbsp;</td>
							
							<td class='descCampo'><?=dicionario(409)?>.</td>					
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(410)?>. [<a style='cursor:pointer' onClick="javascript:cadastro_vencimento()">+</a>]</td>						
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdLocalCobranca' style='width:250px'  onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" disabled='disabled' onchange='busca_conta_debito(document.formulario.IdPessoa.value,document.formulario.IdContaDebito.value,1);'>
									<option value='' selected></option>
									<?
										$sql = "select IdLocalCobranca, DescricaoLocalCobranca from LocalCobranca where IdLoja=$local_IdLoja order by DescricaoLocalCobranca";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdLocalCobranca]'>$lin[DescricaoLocalCobranca]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>							
							<td class='campo'>
								<input type='text' name='IdProcessoFinanceiro' value='' style='width:100px' maxlength='12'  readonly='readonly' />
							</td>	
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NumeroDocumento' value='' style='width:100px' maxlength='12' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NossoNumero' value='' style='width:100px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataLancamento' style='width:93px' value='' readonly='readonly' />
							</td>						
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataVencimento' id='cpDataVencimento' style='width:93px' value='' style='width:110px' maxlength='10' readonly='readonly' />
							</td>							
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='descCampo'>(=) <?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>						
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(+) <?=dicionario(680)?> (<?=getParametroSistema(5,1)?>)</td>							
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(+) <?=dicionario(683)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(+) <?=dicionario(684)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(+) <?=dicionario(685)?> (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>				
							<td class='campo'>
								<input type='text' name='ValorContaReceber' value='' style='width:191px' readonly='readonly' />
							</td>	
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorDespesas' value='' style='width:120px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorMulta' value='' style='width:124px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorJuros' value='' style='width:120px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorTaxa' value='' style='width:192px' readonly='readonly' />
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='descCampo'>(+) <?=dicionario(686)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(-) <?=dicionario(411)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(-) <?=dicionario(521)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(=) <?=dicionario(206)?> (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorOutrasDesp' value='' style='width:191px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorDesconto' value='' style='width:191px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorPercentual' value='' style='width:191px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorFinal' value='' style='width:192px' readonly='readonly' />
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(662)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(663)?> (%)</td>
							<td>
								<table id='titLimiteDesconto' style='margin:0; padding-left:2px;' cellpadding='0' cellspacing='0'>
									<tr>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><?=dicionario(687)?></td>
									</tr>
								</table>
							</td>
							<td>
								<table id='titDataLimiteDesconto' style='margin:0; padding-left:2px;' cellpadding='0' cellspacing='0'>
									<tr>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><?=dicionario(688)?></td>
										<td class='find'>&nbsp;</td>
									</tr>
								</table>
							</td>
							<td >
								<table id='titPosicaoCobranca' style='margin:0; padding-left:2px; display:none;' cellpadding='0' cellspacing='0'>
									<tr>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><?=dicionario(689)?></td>
									</tr>
								</table>
							</td>
							<td id='titNumeroCartaoCredito' style='display: none'>
								<table style='margin:0; padding-left:2px;' cellpadding='0' cellspacing='0'>
									<tr>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><B><?=dicionario(961)?></B></td>
										<td class='find'>&nbsp;</td>
									</tr>
								</table>
							</td>
							<td id='titNumeroContaDebito' style='display: none'>
								<table style='margin:0; padding-left:2px;' cellpadding='0' cellspacing='0'>
									<tr>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><B><?=dicionario(1032)?></B></td>
										<td class='find'>&nbsp;</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorDescontoAConceber' value='' style='width:122px' maxlength='20' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='PercentualDesconto' style='width:122px' value='' readonly='readonly' />
							</td>
							<td>
								<table id='cpLimiteDesconto' style='margin:0; padding-left:2px;' cellpadding='0' cellspacing='0'>
									<tr>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='LimiteDesconto' style='width:121px' value='' readonly='readonly' />
										</td>
									</tr>
								</table>
							</td>
							<td>
								<table id='cpDataLimiteDesconto' style='margin:0; padding-left:2px;' cellpadding='0' cellspacing='0'>
									<tr>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='DataLimiteDesconto' style='width:100px' value='' autocomplete="off" style='width:100px' maxlength='10' readonly='readonly' />
										</td>
										<td class='find'><img id='imgDataLimiteDesconto' src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
									</tr>
								</table>
							</td>
							<td>
								<table id='cpPosicaoCobranca' style='margin:0; padding-left:2px; display:none;' cellpadding='0' cellspacing='0'>
									<tr>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<select name='IdPosicaoCobranca' style='width:269px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_posicao_cobranca(document.formulario.IdStatus.value,this.value, document.formulario.IdContaReceber.value,document.formulario.IdTipoLocalCobranca.value,document.formulario.DataVencimentoTemp.value,document.formulario.DataAtual.value)" tabindex='2'>
												<option value='' selected></option>											
											</select>
										</td>
									</tr>
								</table>
							</td>
							<td id='cpNumeroCartaoCredito' style='display: none'>
								<table style='margin:0; padding-left:2px;' cellpadding='0' cellspacing='0'>
									<tr>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<select name='NumeroCartaoCredito' value='' autocomplete="off" style='width:180px;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onchange='atualizar_NumeroCartaCredito()'>
											</select>
										</td>
									</tr>
								</table>
							</td>
							<td id='cpNumeroContaDebito' style='display: none'>
								<table  style='margin:0; padding-left:2px;' cellpadding='0' cellspacing='0'>
									<tr>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<select name='IdContaDebito' value='' autocomplete="off" style='width:180px;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onchange='atualizar_NumeroContaDebito()'></select>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
				<!--div id='cpEndereco' style='padding-top:6px'-->
				<div id='blNotaFiscal' style='padding-top:6px;'>
					<div class='cp_tit'><?=dicionario(53)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(690)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='DataNF' style='color:#000'><?=dicionario(691)?></B></td>
							<td id='spDataNFIco' class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='ModeloNF' style='color:#000'><?=dicionario(692)?></B></td>							
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='SerieNF' style='color:#000;display:none;'><?=dicionario(968)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='BaseNF' style='color:#000;display:none;'><?=dicionario(970)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='StatusNF' style='color:#000;display:none;'><?=dicionario(140)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NumeroNF' value='' style='width:115px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyUp="obrigatorio_nf(this);" onkeypress="mascara(this.name,event,'int')" maxlength='20' tabindex='3' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataNF' id='cpDataNF' style='width:100px' value='' autocomplete="off" style='width:100px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('DataNF',this)" tabindex='4' readonly='readonly' />
							</td>
							<td class='find' id='cpDataNFIco'><img id='imgDataNF' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataNF",
							        ifFormat       : "%d/%m/%Y",
							        button         : "imgDataNF"
							    });
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='ModeloNFTemp' style='width:140px' onFocus="Foco(this,'in',true)" onBlur="Foco(this,'out')" tabindex='5' onChange="document.formulario.ModeloNF.value = this.value;" disabled='disabled'>
									<option value='' selected></option>
									<?
										$sql = "SELECT 
													Modelo 
												FROM 
													NotaFiscalLayout 
												ORDER BY 
													Modelo";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[Modelo]'>$lin[Modelo]</option>";
										}
									?>
								</select>
								<input type='hidden' name='ModeloNF' value='' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='SerieNF' value='' style='width:140px;display:none' maxlength='255'  tabindex='6' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='BaseNF' value='' style='width:140px;display:none' maxlength='255'  tabindex='7' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='StatusNF' value='' style='width:80px;display:none' maxlength='255'  tabindex='8' />
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(693)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ObsNotaFiscal' value='' style='width:816px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" maxlength='255'  tabindex='9' />
							</td>
						</tr>
					</table>
				</div>
				<div  id='cpEndereco' style='padding-top:6px'>
					<div class='cp_tit'><?=dicionario(694)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(249)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(250)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdPessoaEndereco' style='width:400px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_pessoa_endereco(document.formulario.IdPessoa.value,this.value)" tabindex='10'>
									<option value='0'></option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NomeResponsavelEndereco' value='' style='width:405px' maxlength='100' readonly='readonly' />
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(156)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(155)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Nº</td>
							<td class='separador'>&nbsp;</td>					
							<td class='descCampo'><?=dicionario(255)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(160)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CEP' value='' style='width:70px' maxlength='9' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Endereco' value='' style='width:268px' maxlength='60' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Numero' value='' style='width:55px' maxlength='5' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Complemento' value='' style='width:161px' maxlength='30' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Bairro' value='' style='width:194px' maxlength='30' readonly='readonly' />
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:54px; color:#000'><?=dicionario(256)?></B><?=dicionario(257)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:38px; color:#000'><?=dicionario(157)?></b><?=dicionario(259)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:38px; color:#000'><?=dicionario(186)?></B><?=dicionario(260)?></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa_c.gif' alt='<?=dicionario(166)?>'></td>
							<td class='campo'>
								<input type='text' name='IdPais' value='' style='width:70px' maxlength='11' readonly='readonly' /><input  class='agrupador' type='text' name='Pais' value='' style='width:140px' maxlength='100' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa_c.gif' alt='<?=dicionario(166)?>'></td>
							<td class='campo'>
								<input type='text' name='IdEstado' value='' style='width:70px' maxlength='11' readonly='readonly' /><input class='agrupador' type='text' name='Estado' value='' style='width:140px' maxlength='100' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa_c.gif' alt='<?=dicionario(166)?>'></td>
							<td class='campo'>
								<input type='text' name='IdCidade' value='' style='width:70px' maxlength='11' readonly='readonly' /><input class='agrupador' type='text' name='Cidade' value='' style='width:233px' maxlength='100' readonly='readonly' />
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_recebimento' style='padding-top:6px'>
					<div id='cp_tit'><?=dicionario(695)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='cpLocalRecebimento' style='color:#000'><?=dicionario(696)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='DataRecebimento' style='color:#000'><?=dicionario(697)?></B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpCalcularMulta' style='color:#000'><?=dicionario(698)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdLocalRecebimento' style='width:502px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="verificaAcao(); listarParametro(this.value,false);" tabindex='11'>
									<option value='' selected></option>
									<?
										$where = "";
										
										if($_SESSION["RestringirAgenteAutorizado"] == true){
											$sqlAgente	=	"select 
																AgenteAutorizado.IdLocalCobranca 
															from 
																AgenteAutorizado
															where 
																AgenteAutorizado.IdLoja = $local_IdLoja  and 
																AgenteAutorizado.IdAgenteAutorizado = '$local_IdPessoaLogin' and 
																AgenteAutorizado.Restringir = 1 and 
																AgenteAutorizado.IdStatus = 1 and
																AgenteAutorizado.IdLocalCobranca is not null";
											$resAgente	=	@mysql_query($sqlAgente,$con);
											while($linAgente	=	@mysql_fetch_array($resAgente)){
												$where    =	" and LocalCobrancaGeracao.IdLocalCobranca = $linAgente[IdLocalCobranca]"; 
											}
										}
										if($_SESSION["RestringirAgenteCarteira"] == true){
											$sqlAgente	=	"select 
																AgenteAutorizado.IdLocalCobranca 
															from 
																AgenteAutorizado,
																Carteira
															where 
																AgenteAutorizado.IdLoja = $local_IdLoja  and 
																AgenteAutorizado.IdLoja = Carteira.IdLoja and
																AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
																Carteira.IdCarteira = '$local_IdPessoaLogin' and 
																AgenteAutorizado.Restringir = 1 and 
																AgenteAutorizado.IdStatus = 1 and 
																AgenteAutorizado.IdLocalCobranca is not null";
											$resAgente	=	@mysql_query($sqlAgente,$con);
											while($linAgente	=	@mysql_fetch_array($resAgente)){
												$where    .=	" and LocalCobrancaGeracao.IdLocalCobranca = $linAgente[IdLocalCobranca]"; 
											}
										}
										
										echo $sql = "select 
													IdLocalCobranca, 
													DescricaoLocalCobranca 
												from 
													LocalCobrancaGeracao 
												where 
													IdLoja = $local_IdLoja and 
													IdStatus = 1 and
													IdTipoLocalCobranca = 1 and
													IdArquivoRetornoTipo is null 
													$where 
												order by 
													DescricaoLocalCobranca";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdLocalCobranca]'>$lin[DescricaoLocalCobranca]</option>";
										}
									?>
								</select>
								<p style='margin:0; padding:0;'>&nbsp;</p>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataRecebimento' id='cpDataRecebimento' value='' style='width:100px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('DataRecebimento',this); verificaAcao(); calcular_multa(document.formulario.CalcularMulta.value);" tabindex='12' />
								<p style='margin:0; padding:0;'>&nbsp;</p>
							</td>
							<td class='find' id='cpDataRecebimentoIco'><img id='imgDataRecebimento' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data' /><p style='margin:0; padding:0;'>&nbsp;</p></td>
							<script type="text/javascript">
								if(Number(document.formulario.EditarDataRecebimento.value) != 2){
									Calendar.setup({
										inputField     : "cpDataRecebimento",
										ifFormat       : "%d/%m/%Y",
										button         : "cpDataRecebimentoIco"
									});
								}
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='CalcularMulta' style='width:170px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="calcular_multa(this.value);" tabindex='13'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=114 order by IdParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
								<p id='titMultaJuros' style='margin:0; padding:0;'><?=dicionario(683)?> 0,000%.  <?=dicionario(684)?> 0,000%</p>
							</td>								
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>(=) <?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpValorDesconto' style='color:#000'>(-) <?=dicionario(411)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpValorMoraMulta' style='color:#000'>(+) <?=dicionario(699)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpValorOutrasDespesas' style='color:#000'>(+) <?=dicionario(700)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(=) <?=dicionario(701)?> (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorRecebimento' value='' style='width:150px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorDescontoRecebimento' value='' style='width:150px' maxlength='11' onFocus="Foco(this,'in')" onBlur="Foco(this,'out'); calculaValor('ContaRecebimento')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" tabindex='14' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorMoraMulta' value='' style='width:150px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out'); calculaValor('ContaRecebimento')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" tabindex='15' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorOutrasDespesas' value='' style='width:149px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out'); calculaValor('ContaRecebimento')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" tabindex='16' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorReceber' value='' style='width:149px' readonly='readonly' />
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_parametros' style='display:none'>
					<div id='cp_tit'><?=dicionario(702)?></div>
					<table id='tabelaParametro' class='tableListarCad' cellspacing='0'  style='width:820px'>
					</table>
				</div>
				<div id='cp_log'>
					<div id='cp_tit'><?=dicionario(703)?></div>					
					<table id='cpHistorico'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(704)?></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='HistoricoObs' style='width:816px;' rows='5' readonly='readonly'></textarea>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(705)?></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='Obs' style='width:816px;' rows='5' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='100'></textarea>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(132)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(133)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(93)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(135)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20'  readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px'  readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAlteracao' value='' style='width:180px'  maxlength='20' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataAlteracao' value='' style='width:203px' readonly='readonly' />
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:left'>
								<input type='button' name='bt_chegar' style='width:100px' value='Como Chegar?' class='botao' tabindex='101' onClick='como_chegar()' />
							</td>							
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' name='bt_enviar' style='width:80px' value='E-mail' class='botao' tabindex='102' onClick="cadastrar('enviar')" />
							</td>
							<td class='separador'>&nbsp;</td>					
							<td class='campo'>
								<input type='button' name='bt_nota_fiscal' style='width:80px' value='Nota Fiscal' class='botao' tabindex='103' onClick="cadastrar('notaFiscal')" />
								<input type='button' name='bt_imprimir1' style='width:80px' value='Boleto' class='botao' tabindex='104' onClick="cadastrar('imprimirPdf')" />							
							</td>
							<td class='campo'>
								<input type='button' name='bt_duplicata' style='width:80px' value='Duplicata' class='botao' tabindex='105' onClick="cadastrar('duplicataPdf')" />
							</td>						
							<td class='separador'>&nbsp;</td>						
							<td class='campo' style='text-align:right'>
								<input type='button' name='bt_pagamento' style='width:140px' value='Informar Pagamento' class='botao' tabindex='106' onClick="cadastrar('pagamento')" disabled='disabled' />
								<input type='button' name='bt_receber' style='width:70px' value='Receber' class='botao' tabindex='107' onClick="cadastrar('receber')" />
								<input type='button' name='bt_alterar' style='width:60px' value='Alterar' class='botao' tabindex='108' onClick="cadastrar('alterar')" />
								<input type='button' name='bt_cancelar' style='width:70px' value='Cancelar' class='botao' tabindex='109' onClick="cadastrar('cancelar')" />
							</td>
						</tr>
					</table>
				</div>
				<table style='width:100%;'>
					<tr>
						<td class='find'>&nbsp;</td>
						<td><h1 id='helpText' name='helpText' style='margin:0'>&nbsp;</h1></td>
					</tr>
				</table>
				<div id='cp_listar_recebimentos'  style='margin-top:0'>
					<div id='cp_tit' style='margin-bottom:0; margin-top:0'><?=dicionario(706)?></div>
					<table id='tabelaRecebimentos' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width: 70px'><?=dicionario(424)?>.</td>
							<td style='width: 130px'><?=dicionario(697)?></td>
							<td style='width: 130px' class='valor'><?=dicionario(579)?> (<?=getParametroSistema(5,1)?>)</td>
							<td style='width: 130px' class='valor'><?=dicionario(707)?> (<?=getParametroSistema(5,1)?>)</td>
							<td><?=dicionario(696)?></td>
							<td style='width: 70px'><?=dicionario(708)?></td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' id='totalRecebimentos' colspan='2'><?=dicionario(128)?>: 0</td>
							<td id='totalValorDesconto' class='valor'>0,00</td>
							<td id='totalValorRecebido' class='valor'>0,00</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div id='cp_listar_posicao_cobraca'  style='margin-top:0;display: none'>
					<br/>
					<div id='cp_tit' style='margin-bottom:0; margin-top:0'>Posições de Cobrança</div>
					<table id='tabelaPosicaoCobranca' class='tableListarCad' cellspacing='0' style>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width: 70px'>Id Loja</td>
							<td style='width: 90px'>Cod. Arquivo de Remessa</td>
							<td style='width: 130px' class='valor'>Posição de Cobrança</td>
							<td class='bt_lista' style='text-align: center'>Data da Remessa</td>
						</tr>
					</table>
					<table class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width: 35px;'>Total: </td>
							<td id='quantPosicaoCobranca'>0</td>
						</tr>
					</table>
				</div>
			</form>
		</div>
		<script type='text/javascript'>
		<?
			if($local_IdContaReceber!=''){
				echo "busca_conta_receber($local_IdContaReceber,false,document.formulario.Local.value);";		
				echo "scrollWindow('bottom');";	
			} elseif($local_IdRecibo!=''){
				echo "buscar_conta_receber($local_IdRecibo,false,document.formulario.Local.value);";
			}
			if($local_NossoNumero!=''){
				echo "busca_conta_receber($local_NossoNumero,false,document.formulario.Local.value);";
			}
		?>
			function statusInicial(){
				if(Number(document.formulario.EditarDataRecebimento.value) == 2){
					document.formulario.DataRecebimento.onfocus = function (){
					};
					document.formulario.DataRecebimento.readOnly = true;
					document.formulario.DataRecebimento.value = '<?=date("d/m/Y")?>';
				} else{
					document.formulario.DataRecebimento.onfocus = function (){
						Foco(this,'in',true);
					};
					document.formulario.DataRecebimento.readOnly = false;
					document.formulario.DataRecebimento.value = '';
				}
				
				if(document.formulario.CalcularMulta.value == ''){
					document.formulario.CalcularMulta.value = '<?=getCodigoInterno(3,74)?>';
				}
				
				if(document.formulario.ValorRecebimento.value == ''){
					document.formulario.ValorRecebimento.value = '0,00';
				}
				if(document.formulario.ValorDescontoRecebimento.value == ''){
					document.formulario.ValorDescontoRecebimento.value = '0,00';
				}
				
				if(document.formulario.ValorMoraMulta.value == ''){
					document.formulario.ValorMoraMulta.value = '0,00';
				}
				
				if(document.formulario.ValorOutrasDespesas.value == ''){
					document.formulario.ValorOutrasDespesas.value = '0,00';
				}
				
				if(document.formulario.ValorReceber.value == ''){
					document.formulario.ValorReceber.value = '0,00';
				}
				
				if(document.formulario.ValorTaxa.value == ''){
					document.formulario.ValorTaxa.value = '0,00';
				}
				
				if(document.formulario.ValorOutrasDesp.value == ''){
					document.formulario.ValorOutrasDesp.value = '0,00';
				}
				
				if(document.formulario.ValorPercentual.value == ''){
					document.formulario.ValorPercentual.value = '0,00';
				}
				
				if(document.formulario.ValorContaReceber.value == ''){
					document.formulario.ValorContaReceber.value = '0,00';
				}
				
				if(document.formulario.ValorMulta.value == ''){
					document.formulario.ValorMulta.value = '0,00';
				}
				
				if(document.formulario.ValorJuros.value == ''){
					document.formulario.ValorJuros.value = '0,00';
				}
				
				if(document.formulario.ValorFinal.value == ''){
					document.formulario.ValorFinal.value = '0,00';
				}
				
				if(document.formulario.ValorDespesas.value == ''){
					document.formulario.ValorDespesas.value = '0,00';
				}
				
				if(document.formulario.ValorDesconto.value == ''){
					document.formulario.ValorDesconto.value = '0,00';
				}
				
				if(document.formulario.ValorDescontoAConceber.value == ''){
					document.formulario.ValorDescontoAConceber.value = '0,00';
				}
				
				if(document.formulario.PercentualDesconto.value == ''){
					document.formulario.PercentualDesconto.value = '0,00';
				}
				
				if(document.formulario.CaixaAtivado.value != '1'){
					document.getElementById("cp_recebimento").className = null;
				} else {
					document.getElementById("cp_recebimento").className = "ocultar-bl";
				}
			}
	
			inicia();
			verificaAcao();
			verificaErro();
			enterAsTab(document.forms.formulario);
		</script>
	</body>
</html>
