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
	
	$local_IdContaReceber			= $_POST['IdContaReceber'];
	$local_ObsCancelamento			= formatText($_POST['ObsCancelamento'],NULL);
	$local_IdCancelarNotaFiscal		= $_POST['IdCancelarNotaFiscal'];
	$local_NumeroNF					= $_POST['NumeroNF'];
	$local_DataNF					= $_POST['DataNF'];
	$local_CancelarNotaFiscal		= $_POST['CancelarNotaFiscal'];
	
	if($_GET['IdContaReceber']!=''){
		$local_IdContaReceber	=	$_GET['IdContaReceber'];
	}

	switch ($local_Acao){
		case 'cancelar':
			include('files/editar/editar_cancelar_conta_receber.php');
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
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/cancelar_conta_receber.js'></script>
		<script type = 'text/javascript' src = 'js/cancelar_conta_receber_default.js'></script>
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
		</style>
	</head>
	<body  onLoad="ativaNome('Cancelar Contas a Receber')">
		<? include('filtro_conta_receber.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form id='form' name='formulario' method='post' action='files/editar/editar_cancelar_conta_receber.php' onSubmit="return valida()">
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'/>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'/>
				<input type='hidden' name='IdStatus' value='<?=$local_IdStatus?>'/>
				<input type='hidden' name='QuantParametros' value='<?=$local_QuantParametros?>'/>
				<input type='hidden' name='Local' value='ContaReceber'/>
				<input type='hidden' name='Endereco' value=''/>
				<input type='hidden' name='Numero' value=''/>
				<input type='hidden' name='CEP' value=''/>
				<input type='hidden' name='CancelarNotaFiscal' value=''/>
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
							<td class='descCampo'>(+) Despesas (<?=getParametroSistema(5,1)?>)</td>
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
								<select name='IdLocalCobranca' style='width:278px'  onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" disabled>
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
								<input type='text' name='ValorDespesas' value='' style='width:124px' maxlength='9' readOnly>
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
								<input type='text' name='ValorContaReceber' value='' style='width:191px' maxlength='9' readOnly>
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
								<input type='text' name='ValorTaxa' value='' style='width:191px' readOnly>
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
								<input type='text' name='ValorDesconto' value='' style='width:191px' maxlength='9' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorPercentual' value='' style='width:191px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorFinal' value='' style='width:191px' maxlength='9' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_log'>
					<div id='cp_tit'>Observações do Cancelamento</div>					
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Observações do Cancelamento</B></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='ObsCancelamento' style='width: 816px;' rows=5 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='100'></textarea>
							</td>
						</tr>
					</table>
					<div id='cpVoltarDataBase'></div>
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
								<input type='text' name='NumeroNF' value='' style='width:190px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataNF' value='' style='width:95px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdCancelarNotaFiscal' style='width:140px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='103'>
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
					<div id='cp_tit'>Log</div>					
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Histórico</td>				
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
								<input type='button' style='width:170px;' name='bt_cancelar' value='Confirmar Cancelamento' class='botao' tabindex='106'>
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
							<td style='width: 130px' class='valor'>Valor Desconto (<?=getParametroSistema(5,1)?>)</td>
							<td style='width: 130px' class='valor'>Valor Recebido (<?=getParametroSistema(5,1)?>)</td>
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
				<div>
			</form>
		</div>
	</body>
</html>
<script>
<?
	
	if($local_IdContaReceber!=''){
		echo "busca_cancelar_conta_receber($local_IdContaReceber,false,document.formulario.Local.value);";	
	#	echo "scrollWindow('bottom');";	
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
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
