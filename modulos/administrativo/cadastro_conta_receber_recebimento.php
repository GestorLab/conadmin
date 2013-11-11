<?
	$localModulo		=	1;
	$localOperacao		=	17;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdContaReceber				= $_POST['IdContaReceber'];
	$local_IdContaReceberRecebimento	= $_POST['IdContaReceberRecebimento'];
	
	if($_GET['IdContaReceber']!=''){
		$local_IdContaReceber	=	$_GET['IdContaReceber'];
	}
	if($_GET['IdContaReceberRecebimento']!=''){
		$local_IdContaReceberRecebimento	=	$_GET['IdContaReceberRecebimento'];
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
		<script type = 'text/javascript' src = 'js/conta_receber_recebimento.js'></script>
		<script type = 'text/javascript' src = 'js/conta_receber_recebimento_default.js'></script>
		<script type = 'text/javascript' src = 'js/conta_receber_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		<script type = 'text/javascript' src = 'js/arquivo_remessa_tipo_default.js'></script>
	
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
			#sp_titCaixa, #titCaixa, #sp_titMovimentacao, #titMovimentacao, #sp_titItem, #titItem, #sp_cpIdCaixa, #cpIdCaixa, #sp_cpIdCaixaMovimentacao, #cpIdCaixaMovimentacao, #sp_cpIdCaixaItem, #cpIdCaixaItem { display: none; }
		</style>
	</head>
	<body  onLoad="ativaNome('Contas a Receber/Recebimento')">
		<? include('filtro_conta_receber.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_conta_receber_recebimento.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'/>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'/>
				<input type='hidden' name='IdStatus' value='<?=$local_IdStatus?>'/>
				<input type='hidden' name='QuantParametros' value='<?=$local_QuantParametros?>'/>
				<input type='hidden' name='Local' value='ContaReceberRecebimento'/>
				<input type='hidden' name='IdTipoLocalCobranca' value=''/>
				<input type='hidden' name='CorRecebidoDesc' value='<?=getParametroSistema(15,7)?>'/>
                <input type='hidden' name='PermissaoCancelarRecebimento' value='<?=permissaoSubOperacao($localModulo,171,"C")?>'/>
				<input type='hidden' name='CaixaAtivado' value='<?php echo getCodigoInterno(66,1); ?>' />
				<input type='hidden' name='IdCaixa' value='' />
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
								<input type='text' name='IdContaReceber' value='' autocomplete="off" style='width:70px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_conta_receber(this.value,false,document.formulario.Local.value); busca_conta_receber_recebimento(this.value,0,false,document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" tabindex='1'>
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
				<div id='cp_listar_conta_receber'>
					<div id='cp_tit'>Dados do Contas a Receber</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><font name='busca'>Local de Cobrança</font></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Despesas (<?=getParametroSistema(5,1)?>)</td>	
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Número Documento</td>
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
								<select name='IdLocalCobranca' style='width:275px'  onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" disabled>
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
								<input type='text' name='ValorDespesas' value='' style='width:110px' readOnly>
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
								<input type='text' name='DataVencimento' id='cpDataVencimento' style='width:118px' value='' style='width:110px' maxlength='10' readOnly>
							</td>
							<td class='find' id='cpDataVencimentoIco'><img id='imgDataVencimento' src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
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
								<input type='text' name='ValorFinal' value='' style='width:191px' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Número Nota Fiscal</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Nota Fiscal</td>
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
								<input type='text' name='NumeroNF' value='' style='width:122px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataNF' id='cpDataNF' style='width:122px' value='' autocomplete="off" style='width:110px' maxlength='10' readOnly>
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
								<select name='IdPosicaoCobranca' disabled>
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
				<div id='cp_recebimento' style='padding-top:6px'>
					<div id='cp_tit'>Dados Recebimento</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Recebim.</B></td>
							<td class='separador' id='sp_titLocalRecebimento'>&nbsp;</td>
							<td class='descCampo' id='titLocalRecebimento'>Local Recebimento</td>
							<td class='separador' id='sp_titCaixa'>&nbsp;</td>
							<td class='descCampo' id='titCaixa'>Caixa</td>
							<td class='separador' id='sp_titMovimentacao'>&nbsp;</td>
							<td class='descCampo' id='titMovimentacao'>Movimentação</td>
							<td class='separador' id='sp_titItem'>&nbsp;</td>
							<td class='descCampo' id='titItem'>Item</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Recebimento</B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Arq. Retorno</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Recibo</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Status</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdContaReceberRecebimento' value='' autocomplete="off" style='width:70px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_conta_receber_recebimento(document.formulario.IdContaReceber.value,this.value,false,document.formulario.Local.value)" onkeypress="mascara(this,event,'int')" tabindex='2'>
							</td>
							<td class='separador' id='sp_cpIdLocalRecebimento'>&nbsp;</td>
							<td class='campo' id='cpIdLocalRecebimento'>
								<select name='IdLocalRecebimento' style='width:290px' disabled>
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
								<input type='text' name='IdCaixa' value='' style='width:84px' maxlength='10' readOnly>
							</td>
							<td class='separador' id='sp_cpIdCaixaMovimentacao'>&nbsp;</td>
							<td class='campo' id='cpIdCaixaMovimentacao'>
								<input type='text' name='IdCaixaMovimentacao' value='' style='width:84px' maxlength='10' readOnly>
							</td>
							<td class='separador' id='sp_cpIdCaixaItem'>&nbsp;</td>
							<td class='campo' id='cpIdCaixaItem'>
								<input type='text' name='IdCaixaItem' value='' style='width:83px' maxlength='10' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataRecebimento' id='cpDataRecebimento' value='' style='width:100px' maxlength='10' readOnly>
							</td>
							<td class='find' id='cpDataRecebimentoIco'><img id='imgDataRecebimento' src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdArquivoRetorno' value='' style='width:70px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdRecibo' value='' style='width:75px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdStatusRecebimento' style='width:115px'  disabled>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=85 order by IdParametroSistema";
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
							<td class='descCampo'>(-) Desconto (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(+) Valor Mora Multa (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(+) Outras Despesas (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(=) Valor Receber (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorRecebimento' value='' style='width:150px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorDescontoRecebimento' value='' style='width:150px' maxlength='11' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorMoraMulta' value='' style='width:150px' maxlength='11' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorOutrasDespesas' value='' style='width:149px' maxlength='11' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorReceber' value='' style='width:149px' readOnly>
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
					<div id='cp_tit'>Observações e Log</div>					
					<table id='cpHistorico'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Histórico Contas a Receber</td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='HistoricoObs' style='width: 816px;' rows=5 readOnly></textarea>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Observações do Contas a Receber Recebimento</td>				
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
					<table style='width: 100%' cellpadding='0'>
						<tr>
							<td class='campo' style='padding-left: 22px;'>
								<input type='button' name='bt_voltar' value='Voltar' class='botao' style='width: 110px' tabindex='106' onClick="voltar()">
							</td>
							<td class='campo' style='text-align: right; padding-right: 5px'>
								<input type='button' name='bt_cancelar' value='Cancelar' class='botao' tabindex='106' onClick="cadastrar()">
							</td>
						</tr>
					</table>
				</div>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td><h1 id='helpText' name='helpText' style='margin:0'>&nbsp;</h1></td>
					</tr>
				</table>
				<div id='cp_listar_recebimentos'  style='margin-top:10px'>
					<div id='cp_tit' style='margin-bottom:0; margin:top:0'>Recebimentos</div>
					<table id='tabelaRecebimentos' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width: 70px'>Receb.</td>
							<td style='width: 130px'>Data Recebimento</td>
							<td style='width: 130px' class='valor'>Valor Desconto (<?=getParametroSistema(5,1)?>)</td>
							<td style='width: 130px' class='valor'>Valor Recebido (<?=getParametroSistema(5,1)?>)</td>
							<td>Local Recebimento</td>
							<td style='width: 100px'>Recibo</td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' id='totalRecebimentos' colspan='2'>Total: 0</td>
							<td id='totalValorDesconto' class='valor'>0,00</td>
							<td id='totalValorRecebido' class='valor'>0,00</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				<div>
			</form>
		</div>
	</body>
</html>
<script>
<?
	if($local_IdContaReceber!=''){
		echo "busca_conta_receber($local_IdContaReceber,false,document.formulario.Local.value,$local_IdContaReceberRecebimento);";		
		echo "scrollWindow('bottom');";	
	}
	if($local_IdContaReceberRecebimento!=''){
		echo "busca_conta_receber_recebimento($local_IdContaReceber,$local_IdContaReceberRecebimento,false,document.formulario.Local.value);";		
	}
?>
	function statusInicial(){
		if(document.formulario.ValorTaxa.value == ''){
			document.formulario.ValorTaxa.value	=	'0,00';
		}
		if(document.formulario.ValorOutrasDesp.value == ''){
			document.formulario.ValorOutrasDesp.value	=	'0,00';
		}
		if(document.formulario.ValorPercentual.value == ''){
			document.formulario.ValorPercentual.value	=	'0,00';
		}
	}	
	
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>