<?
	$localModulo		=	1;
	$localOperacao		=	81;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdContaReceber				= $_POST['IdContaReceber'];
	$local_IdPessoa						= $_POST['IdPessoa'];
	$local_DataVencimento				= $_POST['DataVencimento'];
	$local_DataVencimentoAntiga			= $_POST['DataVencimentoAntiga'];
	$local_ValorVencimento				= formatText($_POST['ValorVencimento'],NULL);
	$local_ValorMoraMulta				= formatText($_POST['ValorMoraMulta'],NULL);
	$local_ValorDescontoVencimento		= formatText($_POST['ValorDescontoVencimento'],NULL);
	$local_ValorJurosVencimento			= formatText($_POST['ValorJurosVencimento'],NULL);
	$local_ValorTaxaReImpressaoBoleto	= formatText($_POST['ValorTaxaReImpressaoBoleto'],NULL);
	$local_ValorOutrasDespesas			= formatText($_POST['ValorOutrasDespesas'],NULL);
	$local_ValorFinalVencimento			= formatText($_POST['ValorFinalVencimento'],NULL);
	$local_ValorFinal					= formatText($_POST['ValorFinal'],NULL);
	$local_ManterDescontoAConceber		= formatText($_POST['ManterDescontoAConceber'],NULL);
	
	if($_GET['IdContaReceber']!=''){
		$local_IdContaReceber	=	$_GET['IdContaReceber'];
	}
	if($_GET['DataVencimento']!=''){
		$local_DataVencimento	=	$_GET['DataVencimento'];
	}

	switch ($local_Acao){
		case 'alterar':
			include('files/inserir/inserir_conta_receber_vencimento.php');
			break;
		default:
			$local_Acao = '';
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
		<script type = 'text/javascript' src = '../../js/mascara_real.js'></script>
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/conta_receber_vencimento.js'></script>
		<script type = 'text/javascript' src = 'js/conta_receber_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
	
		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>
	    <link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled					{ background-color: #FFF; }
			select:disabled					{ color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Contas a Receber/Vencimento')">
		<? include('filtro_conta_receber.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_conta_receber_vencimento.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='IdStatus' value='<?=$local_IdStatus?>'>
				<input type='hidden' name='Local' value='ContaReceberVencimento'>
				<input type='hidden' name='IdTipoLocalCobranca' value=''>
				<input type='hidden' name='PercentualMulta' value=''>
				<input type='hidden' name='PercentualJurosDiarios' value=''>
				<input type='hidden' name='ValorTaxaReImpressaoBoletoLocalCobranca' value=''>
				<input type='hidden' name='Moeda' value='<?=getParametroSistema(5,1)?>'>
				<input type='hidden' name='ValorTaxaReImpressaoDefault' value='<?=getCodigoInterno(3,80)?>'>
				<input type='hidden' name='DataVencimentoDiaUtil' value=''>
				<input type='hidden' name='DataPrimeiroVencimento' value=''>
				<input type='hidden' name='ValorPrimeiroVencimento' value=''>
				<input type='hidden' name='MultaJurosValorAtual' value='<?=getCodigoInterno(3,206)?>'>
				
				<div id='cp_conta_receber'>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Conta Receber</td>
							<td class='separador'>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdContaReceber' value='' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange='busca_conta_receber(this.value,true,document.formulario.Local.value);retorna_campos_padrao();' autocomplete="off" style='width:70px'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='descricao' style='width: 728px;'><B id='cp_Status'>&nbsp;</B></td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosCliente'>
					<div id='cp_tit'>Dados do Cliente</div>
					<?	
						$nome="	<table id='cp_juridica'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:36px; color:#000'>Pessoa</B>Nome Pessoa</td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo' id='cp_RazaoSocial_Titulo'>Razão Social</td>
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
										<input type='text' name='CNPJ' value='' style='width:150px' maxlength='18' readOnly>
									</td>
								</tr>
							</table>";
							
						$razao="	<table id='cp_juridica'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:36px; color:#000'>Pessoa</B><B style='color:#000'>Razão Social</B></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'>Nome Fantasia</td>
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
							<td class='descCampo'><B style='margin-right:36px; color:#000'>Pessoa</B>Nome Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>E-mail</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>CPF</td>
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
				<div id='cp_lancamentos_financeiros'>
					<div id='cp_tit' style='margin-bottom:0'><?=dicionario(649)?></div>
					<table id='tabelaLancFinanceiro' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width: 40px'>Tipo</td>
							<td style='width: 55px'>Cod.</td>
							<td>Descrição</td>
							<td>Referência</td>
							<td class='valor'>Valor (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='2' id='tabelaLancFinanceiroTotal'></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td id='tabelaLancFinanceiroTotalValor' class='valor'>0,00</td>
						</tr>
					</table>
				</div>
				<div id='cp_listar_conta_receber' style='padding-top:5px'>
					<div id='cp_tit'>Dados do Contas a Receber</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><font name='busca'>Local de Cobrança</font></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(1051)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>N° Documento</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(1049)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Lanç.</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Vencim.</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdLocalCobranca' style='width:250px'  onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" disabled>
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
								<input type='text' name='NumeroDocumento' value='' style='width:100px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NossoNumero' value='' style='width:100px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataLancamento' style='width:93px' value='' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataVencimentoAntiga' id='cpDataVencimentoAntiga' style='width:93px' value='' style='width:110px' maxlength='10' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>(=) Valor (<?=getParametroSistema(5,1)?>)</td>					
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(+) Despesas (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(+) Multa (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(+) Juros (<?=getParametroSistema(5,1)?>)</td>							
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(+) Taxa de Atualização (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorContaReceber' value='' style='width:191px' readOnly>
							</td>							
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorMulta' value='' style='width:120px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorDespesas' value='' style='width:124px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorJuros' value='' style='width:120px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorTaxa' value='' style='width:192px' readOnly>
							</td>							
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='descCampo'>(+) Valor Outras Despesas (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(-) Desconto (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(-) Percentual (<?=getParametroSistema(5,1)?>)</td>							
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(=) Valor Final (<?=getParametroSistema(5,1)?>)</td>							
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorOutrasDesp' value='' style='width:191px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorDesconto' value='' style='width:191px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorPercentual' value='' style='width:191px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorFinal' value='' style='width:192px' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Número Nota Fiscal</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='DataNF' style='color:#000'>Data Nota Fiscal</B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Desc. a Conceber (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Perc. Desconto (%)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titLimiteDesconto' style='color:#000'>Limite Desconto</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titDataLimiteDesconto' style='color:#000'>Data Limite Desc</B></td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NumeroNF' value='' style='width:122px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataNF' id='cpDataNF' style='width:122px' value='' autocomplete="off" style='width:100px' maxlength='10' readOnly>
							</td>
							<td class='find' id='cpDataNFIco'><img id='imgDataNF' src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorDescontoAConceber' value='' style='width:122px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='PercentualDesconto' style='width:122px' value='' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input id='cpLimiteDesconto' type='text' name='LimiteDesconto' style='width:121px' value='' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataLimiteDesconto' id='cpDataLimiteDesconto' style='width:100px' value='' autocomplete="off" style='width:100px' maxlength='10' readOnly>
							</td>
							<td class='find'><img id='imgDataLimiteDesconto' src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
						</tr>
					</table>
					<table id='cpPosicaoCobranca'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000'>Posição de Cobrança</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdPosicaoCobranca' style='width:545px'  readOnly>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=81 order by IdParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_vencimento' style='padding-top:6px'>
					<div id='cp_tit'>Novo Vencimento</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='DataVencimento'>Data Vencimento</B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Quant. Dias</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpCalcularMulta'>Calcular Multa e Juros</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Taxa de Atualização</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titManterDescontoAConceber'>Manter Desc. a Conceber</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='DataVencimento' id='cpDataVencimento' value='' style='width:122px' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('DataVencimento',this); busca_conta_receber_vencimento(document.formulario.IdContaReceber.value,formatDate(this.value),false); verificaAcao()" onClick="retorna_campos_padrao()" tabindex='9'>
							</td>
							<td class='find' id='cpDataVencimentoIco' valign='top'><img style='margin-top:5px' id='imgDataVencimento' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataVencimento",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataVencimentoIco"
							    });
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='QuantDias' value='' style='width:100px' maxlength='10' readOnly>
								<p style='margin:0; padding:0;'>Úteis</p>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='CalcularMulta' style='width:210px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="calculaValor(this.name);" tabindex='10'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=114 order by IdParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
								<p id='titMultaJuros' style='margin:0; padding:0;'>Multa 0,000%.  Juros 0,000%</p>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='TaxaReimpressao' style='width:150px; margin:0'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="calculaValor(this.name)" tabindex='11'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=117 order by IdParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
								<p id='titTaxa'  style='margin:0; padding:0;'><?=getParametroSistema(5,1)?> 0,00</p>
							</td>	
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='ManterDescontoAConceber' style='width:161px; margin:0;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="calculaValor(this.name)" tabindex='11'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=218 order by IdParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>(=) Valor (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(+) Multa (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(+) Juros (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(+) Taxa de Atualização (<?=getParametroSistema(5,1)?>)</td>							
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorVencimento' value='' style='width:191px' readOnly>
							</td>	
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorMoraMulta' value='' style='width:191px' maxlength='11' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onChange="calculaValor(this.name)" onkeypress="mascara(this,event,'float')" tabindex='12'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorJurosVencimento' value='' style='width:191px' maxlength='11' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onChange="calculaValor(this.name)" onkeypress="mascara(this,event,'float')" tabindex='13'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorTaxaReImpressaoBoleto' value='' style='width:191px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onChange="calculaValor(this.name)" onkeypress="mascara(this,event,'float')" tabindex='14'>
							</td>							
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='cpValorOutrasDespesas' style='color:#000'>(+) Outras Despesas (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>(-) Desconto (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>(-) Percentual (%)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(=) Valor Final (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorOutrasDespesas' value='' style='width:191px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onChange="calculaValor(this.name)" onkeypress="mascara(this,event,'float')" tabindex='15'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorDescontoVencimento' value='' style='width:191px' maxlength='12' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onChange="calculaValor(this.name)" onkeypress="mascara(this,event,'float')" tabindex='16'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='PercentualVencimento' value='' style='width:191px' maxlength='12' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onChange="calculaValor(this.name)" onkeypress="mascara(this,event,'float')" tabindex='17'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorFinalVencimento' value='' style='width:191px' readOnly>
							</td>
						</tr>
					</table>
				<div>
				<div id='cp_log'>
					<div id='cp_tit'>Log</div>					
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Usuário Cadastro</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Cadastro</td>
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
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width:848px;'>
						<tr>
							<td class='campo' style='text-align: right;'>
								<input type='button' name='bt_voltar' value='Voltar' class='botao' tabindex='104' onClick="voltar()">
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='105' onClick="cadastrar('alterar')">
							</td>
						</tr>
					</table>
				</div>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText' name='helpText' style='margin:0'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>
				<div id='cp_listar_vencimentos'>
					<div id='cp_tit' style='margin-bottom:0; margin:top:10px'>Vencimentos</div>
					<table id='tabelaVencimentos' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width: 30px'>&nbsp;</td>
							<td>Vencimento</td>
							<td class='valor'>Valor(<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Multa(<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Juros(<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Reimpressão(<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Outras Desp.(<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Desconto(<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Valor Final(<?=getParametroSistema(5,1)?>)</td>
							<td>Usuário</td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' id='totalVencimentos' colspan='2'>Total: 0</td>
							<td id='totalValorReceber' class='valor'>0,00</td>
							<td id='totalValorMulta' class='valor'>0,00</td>
							<td id='totalValorJuros' class='valor'>0,00</td>
							<td id='totalValorTaxa' class='valor'>0,00</td>
							<td id='totalValorOutrasDespessas' class='valor'>0,00</td>
							<td id='totalValorDesconto' class='valor'>0,00</td>
							<td id='totalValorFinal' class='valor'>0,00</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</div>
			</form>
		</div>
	</body>
</html>
<script>
<?
	if($local_IdContaReceber!=''){
		echo "busca_conta_receber($local_IdContaReceber,true,document.formulario.Local.value);";		
		echo "scrollWindow('bottom');";	
	}
	if($local_DataVencimento!=''){
		echo "busca_conta_receber_vencimento($local_IdContaReceber,'$local_DataVencimento',false,document.formulario.Local.value);";		
	}
?>
	function statusInicial(){
		if(document.formulario.CalcularMulta.value == ''){
			document.formulario.CalcularMulta.value	=	'<?=getCodigoInterno(3,74)?>';
		}
		if(document.formulario.ManterDescontoAConceber.value == ''){
			document.formulario.ManterDescontoAConceber.value = '<?=getCodigoInterno(3,163)?>';
		}
		if(document.formulario.TaxaReimpressao.value == ''){
			document.formulario.TaxaReimpressao.value	=	'<?=getCodigoInterno(3,80)?>';
			if(document.formulario.TaxaReimpressao.value == 1){
				calculaValor("TaxaReimpressao");
			}
		}
		if(document.formulario.ValorMoraMulta.value == ''){
			document.formulario.ValorMoraMulta.value	=	'0,00';
		}
		if(document.formulario.ValorJurosVencimento.value == ''){
			document.formulario.ValorJurosVencimento.value	=	'0,00';
		}
		if(document.formulario.ValorTaxaReImpressaoBoleto.value == ''){
			document.formulario.ValorTaxaReImpressaoBoleto.value	=	'0,00';
		}
		if(document.formulario.ValorDescontoVencimento.value == ''){
			document.formulario.ValorDescontoVencimento.value	=	'0,00';
		}
		if(document.formulario.PercentualVencimento.value == ''){
			document.formulario.PercentualVencimento.value	=	'0,00';
		}
		if(document.formulario.ValorOutrasDespesas.value == ''){
			document.formulario.ValorOutrasDespesas.value	=	'0,00';
		}
		if(document.formulario.ValorTaxa.value == ''){
			document.formulario.ValorTaxa.value	=	'0,00';
		}
		if(document.formulario.ValorOutrasDesp.value == ''){
			document.formulario.ValorOutrasDesp.value	=	'0,00';
		}
		if(document.formulario.ValorPercentual.value == ''){
			document.formulario.ValorPercentual.value	=	'0,00';
		}
		
		if(document.formulario.MultaJurosValorAtual.value == '1'){
			document.formulario.ValorFinalVencimento.value = document.formulario.ValorFinal.value;
		}
	}

	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>