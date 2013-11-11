<?
	$localModulo		=	1;
	$localOperacao		=	17;
	$localSuboperacao	=	"C";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	
	$local_IdContaReceber					= $_POST['IdContaReceber'];
	$local_IdContaReceberRecebimento		= $_POST['IdContaReceberRecebimento'];
	$local_ObsCancelamento					= formatText($_POST['ObsCancelamento'],NULL);
	$local_CreditoFuturo					= formatText($_POST['CreditoFuturo'],NULL);
	$local_IdLocalEstorno					= formatText($_POST['IdLocalEstorno'],NULL);
	$local_IdContratoEstorno				= $_POST['IdContratoEstorno'];
	$local_IdCancelarNotaFiscal				= $_POST['IdCancelarNotaFiscal'];
	$local_NumeroNF							= $_POST['NumeroNF'];
	$local_DataNF							= $_POST['DataNF'];
	$local_CancelarNotaFiscalRecebimento	= $_POST['CancelarNotaFiscalRecebimento'];
	
	if($_GET['IdContaReceber']!=''){
		$local_IdContaReceber	=	$_GET['IdContaReceber'];
	}
	if($_GET['IdContaReceberRecebimento']!=''){
		$local_IdContaReceberRecebimento	=	$_GET['IdContaReceberRecebimento'];
	}

	switch ($local_Acao){
		case 'alterar':
			include('files/editar/editar_cancelar_conta_receber_recebimento.php');
			break;
		default:
			$local_Acao = 'alterar';
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
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/cancelar_conta_receber_recebimento.js'></script>
		<script type = 'text/javascript' src = 'js/cancelar_conta_receber_recebimento_default.js'></script>
		<script type = 'text/javascript' src = 'js/conta_receber_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
	    <link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
			#sp_titCaixa, #titCaixa, #sp_titMovimentacao, #titMovimentacao, #sp_titItem, #titItem, #sp_cpIdCaixa, #cpIdCaixa, #sp_cpIdCaixaMovimentacao, #cpIdCaixaMovimentacao, #sp_cpIdCaixaItem, #cpIdCaixaItem { display: none; }
		</style>
	</head>
	<body  onLoad="ativaNome('Cancelar Contas a Receber Recebimento')">
		<? include('filtro_conta_receber.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='files/editar/editar_cancelar_conta_receber_recebimento.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'/>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'/>
				<input type='hidden' name='IdStatus' value='<?=$local_IdStatus?>'/>
				<input type='hidden' name='QuantParametros' value='<?=$local_QuantParametros?>'/>
				<input type='hidden' name='Local' value='CancelarContaReceberRecebimento'/>
				<input type='hidden' name='Voltar' value=''/>
				<input type='hidden' name='LancamentoEV' value=''/>
				<input type='hidden' name='Endereco' value=''/>
				<input type='hidden' name='Numero' value=''/>
				<input type='hidden' name='CEP' value=''/>
				<input type='hidden' name='CancelarNotaFiscalRecebimento' value=''/>
                <input type='hidden' name='PermissaoCancelarRecebimento' value='<?=permissaoSubOperacao($localModulo,171,"C")?>'/>
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
								<input type='text' name='IdContaReceber' value='' autocomplete="off" style='width:70px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_cancelar_conta_receber(this.value,false,document.formulario.Local.value)" onkeypress="mascara(this,event,'int')" tabindex='1'>
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
								<input type='text' name='CPF' value='' style='width:124px' maxlength='18' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_lancamentos_financeiros'>
					<div id='cp_tit' style='margin-bottom:0'>Lançamentos Financeiros</div>
					<table id='tabelaLancFinanceiro' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td style='width: 40px'>Tipo</td>
							<td style='width: 55px'>Cod.</td>
							<td>Descrição</td>
							<td>Referência</td>
							<td class='valor'>Valor (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td colspan='2' id='tabelaLancFinanceiroTotal'></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td id='tabelaLancFinanceiroTotalValor' class='valor'>0,00</td>
						</tr>
					</table>
				</div>
				<div id='cp_listar_conta_receber'>
					<div id='cp_tit'>Dados do Contas a Receber</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Local de Cobrança</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Despesas (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Numero Documento</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Lançamento</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Vencimento</td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdLocalCobranca' style='width:280px'  onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" disabled>
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
								<input type='text' name='ValorDespesas' value='' style='width:124px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NumeroDocumento' value='' style='width:112px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataLancamento' style='width:100px' value='' readOnly>
							</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataVencimento' id='cpDataVencimento' style='width:100px' value='' autocomplete="off" style='width:110px' maxlength='10' readOnly>
							</td>
							<td class='find' id='cpDataVencimentoIco'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
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
							<td class='descCampo'>(+) Taxa Reimpressão (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorContaReceber' value='' style='width:191px' readOnly>
							</td>							
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorMulta' value='' style='width:191px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorJuros' value='' style='width:191px' readOnly>
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
				</div>
				<div id='cp_recebimento'>
					<div id='cp_tit'>Dados Recebimento</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Receb.</td>
							<td class='separador' id='sp_titLocalRecebimento'>&nbsp;</td>
							<td class='descCampo' id='titLocalRecebimento'>Local Recebimento</td>
							<td class='separador' id='sp_titCaixa'>&nbsp;</td>
							<td class='descCampo' id='titCaixa'>Caixa</td>
							<td class='separador' id='sp_titMovimentacao'>&nbsp;</td>
							<td class='descCampo' id='titMovimentacao'>Movimentação</td>
							<td class='separador' id='sp_titItem'>&nbsp;</td>
							<td class='descCampo' id='titItem'>Item</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='DataRecebimento'  style='color:#000'>Data Recebimento</B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Arq. Retorno</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Recibo</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdContaReceberRecebimento' value='' style='width:70px' maxlength='11' tabindex='7' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_conta_receber_recebimento(document.formulario.IdContaReceber.value,this.value)">
							</td>
							<td class='separador' id='sp_cpIdLocalRecebimento'>&nbsp;</td>
							<td class='campo' id='cpIdLocalRecebimento'>
								<select name='IdLocalRecebimento' style='width:365px' disabled>
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
							<td class='separador' id='sp_cpIdCaixa'>&nbsp;</td>
							<td class='campo' id='cpIdCaixa'>
								<input type='text' name='IdCaixa' value='' style='width:108px' maxlength='10' readOnly>
							</td>
							<td class='separador' id='sp_cpIdCaixaMovimentacao'>&nbsp;</td>
							<td class='campo' id='cpIdCaixaMovimentacao'>
								<input type='text' name='IdCaixaMovimentacao' value='' style='width:108px' maxlength='10' readOnly>
							</td>
							<td class='separador' id='sp_cpIdCaixaItem'>&nbsp;</td>
							<td class='campo' id='cpIdCaixaItem'>
								<input type='text' name='IdCaixaItem' value='' style='width:109px' maxlength='10' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataRecebimento' id='cpDataRecebimento' value='' style='width:100px' maxlength='10' readOnly>
							</td>
							<td class='find' id='cpDataRecebimentoIco'><img id='imgDataRecebimento' src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdArquivoRetorno' value='' style='width:99px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdRecibo' value='' style='width:98px' readOnly>
							</td>									
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>(=) Valor (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpValorDesconto' style='color:#000'>(-) Desconto (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpValorMoraMulta' style='color:#000'>(+) Valor Mora Multa (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpValorOutrasDespesas' style='color:#000'>(+) Outras Despesas (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(=) Valor Receber (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorRecebimento' value='' style='width:150px' maxlength='9' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorDescontoRecebimento' value='' style='width:150px' maxlength='9' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorMoraMulta' value='' style='width:150px' maxlength='9' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorOutrasDespesas' value='' style='width:149px' maxlength='9' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorReceber' value='' style='width:149px' maxlength='9' readOnly>
							</td>
						</tr>
					</table>
				<div>
				<div id='cp_parametros' style='display:none'>
					<div id='cp_tit'>Parâmetros Recebimento</div>
					<table id='tabelaParametro' class='tableListarCad' cellspacing='0'  style='width:820px'>
						
					</table>
				</div>
				<div id='cp_log'>
					<div id='cp_tit'>Observações do Cancelamento do Recebimento</div>					
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Crédito Futuro</B></td>
							<td class='separador'>&nbsp;</td>				
							<td class='descCampo' id='titLocalEstorno' style='display:none'><B>Local Estorno</B></td>
							<td class='descCampo' id='titContratoEstorno' style='display:none'>Contrato Estorno</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='CreditoFuturo' style='width:100px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="verifica_estorno(this.value)" tabindex='100'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=94 order by IdParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>				
							<td class='campo' id='cpLocalEstorno' style='display:none'>
								<select name='IdLocalEstorno' style='width:350px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='101'>
									<option value='' selected></option>
									<?
										$sql = "select IdLocalCobranca, DescricaoLocalCobranca from LocalCobrancaGeracao where IdLoja=$local_IdLoja order by DescricaoLocalCobranca ASC";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdLocalCobranca]'>$lin[DescricaoLocalCobranca]</option>";
										}
									?>
								</select>
							</td>
							<td class='campo' id='cpContratoEstorno' style='display:none'>
								<select name='IdContratoEstorno' style='width:350px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='102'>
									<option value='' selected></option>
								</select>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Observações do Cancelamento do Recebimento</B></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='ObsCancelamento' style='width: 816px;' rows=5 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='103'></textarea>
							</td>
						</tr>
					</table>
					<table id='cp_titCancelarNotaFiscal' style='display:none;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Número Nota Fiscal</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Nota Fiscal</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Cancelar Nota Fiscal</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NumeroNF' value='' style='width:190px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataNF' value='' style='width:95px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdCancelarNotaFiscal' style='width:140px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='104'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=200 order by IdParametroSistema";
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
				<div id='cp_log'>
					<div id='cp_tit'>Observações e Log</div>					
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Observações do Contas a Receber</td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='Obs' style='width: 816px;' rows=5 readOnly></textarea>
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
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAlteracao' value='' style='width:180px'  maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataAlteracao' value='' style='width:203px' readOnly>
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='button' style='width:55px;' name='bt_voltar' value='Voltar' class='botao' tabindex='105' onClick="cadastrar('voltar')">
							</td>
							<td class='campo' style='text-align:right;'>
								<input type='button' style='width:170px;' name='bt_cancelar' value='Confirmar Cancelamento' class='botao' tabindex='106' onClick="cadastrar('cancelar')">
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
				<div id='cp_listar_recebimentos'>
					<div id='cp_tit' style='margin-bottom:0; margin-top:10px'>Recebimentos</div>
					<table id='tabelaRecebimentos' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td style='width: 70px'>Receb.</td>
							<td style='width: 130px'>Data Recebimento</td>
							<td style='width: 130px'>Valor Desconto (<?=getParametroSistema(5,1)?>)</td>
							<td style='width: 130px'>Valor Recebido (<?=getParametroSistema(5,1)?>)</td>
							<td>Local Recebimento</td>
							<td style='width: 70px'>Recibo</td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td colspan='2' id='totalRecebimentos'>Total: 0</td>
							<td id='totalValorDesconto' class='valor'>0,00</td>
							<td id='totalValorRecebido' class='valor'>0,00</td>
							<td>&nbsp;</td>
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
	if($local_IdContaReceber!='' && $local_IdContaReceberRecebimento!=""){
		echo "busca_cancelar_conta_receber($local_IdContaReceber,false,document.formulario.Local.value,'',$local_IdContaReceberRecebimento);";	
		echo "scrollWindow('bottom');";	
	}
?>
	function status_inicial(){
		if(document.formulario.ValorContaReceber.value == ''){
			document.formulario.ValorContaReceber.value	=	'0,00';
		}
		if(document.formulario.ValorDesconto.value == ''){
			document.formulario.ValorDesconto.value	=	'0,00';
		}
		if(document.formulario.ValorDespesas.value == ''){
			document.formulario.ValorDespesas.value	=	'0,00';
		}
		if(document.formulario.ValorFinal.value == ''){
			document.formulario.ValorFinal.value	=	'0,00';
		}
		if(document.formulario.ValorRecebimento.value == ''){
			document.formulario.ValorRecebimento.value	=	'0,00';
		}
		if(document.formulario.ValorDescontoRecebimento.value == ''){
			document.formulario.ValorDescontoRecebimento.value	=	'0,00';
		}
		if(document.formulario.ValorMoraMulta.value == ''){
			document.formulario.ValorMoraMulta.value	=	'0,00';
		}
		if(document.formulario.ValorOutrasDespesas.value == ''){
			document.formulario.ValorOutrasDespesas.value	=	'0,00';
		}
		if(document.formulario.ValorReceber.value == ''){
			document.formulario.ValorReceber.value	=	'0,00';
		}
		if(document.formulario.Local.value == 'ContaReceber'){
			if(document.formulario.VoltarDataBase.value == ''){
				document.formulario.VoltarDataBase.value	=	'<?=getCodigoInterno(3,21)?>';
			}
		}
		if(document.formulario.CreditoFuturo.value == ''){
			document.formulario.CreditoFuturo.value	=	'<?=getCodigoInterno(3,62)?>';
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
		if(document.formulario.ValorMulta.value == ''){
			document.formulario.ValorMulta.value	=	'0,00';
		}
		if(document.formulario.ValorJuros.value == ''){
			document.formulario.ValorJuros.value	=	'0,00';
		}
	}
	function inicia(){
		document.formulario.IdContaReceberRecebimento.focus();
		status_inicial();
	}
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
