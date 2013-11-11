<?
	$localModulo		=	1;
	$localOperacao		=	57;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdOrdemServico					= $_POST['IdOrdemServico'];
	$local_QtdParcela						= $_POST['QtdParcela'];
	$local_ValorDespesaLocalCobranca		= $_POST['ValorDespesaLocalCobranca'];
	$local_ValorTotal						= $_POST['ValorTotal'];
	$local_IdLocalCobrancaTemp				= $_POST['IdLocalCobrancaTemp'];
	$local_FormaCobranca					= $_POST['FormaCobranca'];
	$local_FormaCobrancaTemp				= $_POST['FormaCobrancaTemp'];
	$local_IdContratoAgrupador				= $_POST['IdContratoAgrupador'];
	$local_IdContratoIndividual				= $_POST['IdContratoIndividual'];
	$local_IdPessoa							= $_POST['IdPessoa'];
	$local_IdPessoaEnderecoCobranca			= $_POST['IdPessoaEnderecoCobranca'];
	$local_IdTerceiro						= $_POST['IdTerceiro'];
	$local_PercentualRepasseTerceiro		= $_POST['PercentualRepasseTerceiro'];
	$local_PercentualRepasseTerceiroOutros	= $_POST['PercentualRepasseTerceiroOutros'];
	$local_PercentualParcela				= $_POST['PercentualParcela'];
	$local_ValorRepasseTerceiro				= $_POST['ValorRepasseTerceiro'];
	$local_ValorRepasseTerceiroOutros		= $_POST['ValorRepasseTerceiroOutros'];
	$local_IdFormatoCarne					= $_POST['IdFormatoCarne'];
	$local_IdStatus							= $_POST['IdStatus'];
	$local_IdFormatoCarne					= formatText($_POST['IdFormatoCarne'],NULL);
	$local_IdContaDebitoCartao				= formatText($_POST['IdContaDebitoCartao'],NULL);	
	$local_SeletorContaCartao				= formatText($_POST['SeletorContaCartao'],NULL);
	
	if($_GET['IdOrdemServico']!=''){
		$local_IdOrdemServico	= $_GET['IdOrdemServico'];	
	}
	
	switch ($local_Acao){
		case 'alterar':
			include('files/editar/editar_ordem_servico_fatura.php');
			break;
		case 'cancelar':		
			header("Location: cadastro_cancelar_lancamento_financeiro.php?IdOrdemServico=$local_IdOrdemServico&FormaCobranca=$local_FormaCobrancaTemp");
			break;
		case 'confirmar':
			include('rotinas/confirmar_ordem_servico_fatura.php');
			break;
		case 'enviar':
			header("Location: cadastro_enviar_mensagem.php?IdOrdemServico=$local_IdOrdemServico");
			$local_Erro=64;
			break;
		case 'imprimir':
			$sql = "select 
						LocalCobranca.IdLocalCobrancaLayout
					from 
						OrdemServico,
						LocalCobranca
					where 
						OrdemServico.IdLoja = $local_IdLoja and
						OrdemServico.IdLoja = LocalCobranca.IdLoja and
						OrdemServico.IdOrdemServico = $local_IdOrdemServico and
						OrdemServico.IdLocalCobranca = LocalCobranca.IdLocalCobranca";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);
			
			$file = "local_cobranca/$lin[IdLocalCobrancaLayout]/pdf_all.php";
			$fileurl = $file."?IdLoja=$local_IdLoja&IdOrdemServico=$local_IdOrdemServico";
			if(file_exists($file)){
				header("Location: $fileurl");
			}else{				
				$local_Erro = 58;
			}
			break;
		case 'imprimirCarne':
			$sql = "select
						LocalCobranca.IdLocalCobrancaLayout,
						ContaReceberDados.IdCarne
					from
						OrdemServico,
						LancamentoFinanceiro,
						LancamentoFinanceiroContaReceber,
						ContaReceberDados,
						LocalCobranca
					where
						OrdemServico.IdLoja = $local_IdLoja and
						OrdemServico.IdOrdemServico = $local_IdOrdemServico and
						OrdemServico.IdLoja = LancamentoFinanceiro.IdLoja and
						OrdemServico.IdOrdemServico = LancamentoFinanceiro.IdOrdemServico and
						LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
						LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
						LancamentoFinanceiroContaReceber.IdLoja = ContaReceberDados.IdLoja and
						LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceberDados.IdContaReceber and
						ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
						ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca
					group by
						ContaReceberDados.IdCarne;";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);
			
			$file = "local_cobranca/$lin[IdLocalCobrancaLayout]/pdf_all.php";
			$fileurl = $file."?IdLoja=$local_IdLoja&IdCarne=$lin[IdCarne]";
			
			
			if(file_exists($file)){
				header("Location: $fileurl");
			}else{				
				$local_Erro = 58;
			}
			break;
		default:
			$local_Acao = 'inserir';
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
		<script type = 'text/javascript' src = '../../js/mascara_real.js'></script>
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/val_time.js'></script>
		<script type = 'text/javascript' src = '../../js/val_mes.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/ordem_servico_fatura.js'></script>
		<script type = 'text/javascript' src = 'js/ordem_servico_default.js'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_default.js'></script>
		<script type = 'text/javascript' src = 'js/usuario_default.js'></script>
		<script type = 'text/javascript' src = 'js/local_cobranca_default.js'></script>
		<script type = 'text/javascript' src = 'js/terceiro_default.js'></script>
		
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
	<body  onLoad="ativaNome('Faturamento/Ordem de Serviço')">
		<? include('filtro_ordem_servico.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_ordem_servico_fatura.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='OrdemServicoFatura'>
				<input type='hidden' name='IdStatus' value=''>
				<input type='hidden' name='IdLocalCobrancaTemp' value=''>
				<input type='hidden' name='FormaCobrancaTemp' value=''>
				<input type='hidden' name='ValorCobrancaMinima' value=''>
				<input type='hidden' name='Login' value='<?=$local_Login?>'>
				<input type='hidden' name='simulado' value=''>
				<input type='hidden' name='ServicoAutomatico' value=''>
				<input type='hidden' name='Periodicidade' value=''>
				<input type='hidden' name='PercentualParcela' value=''>
				<input type='hidden' name='EditarTerceiro' value='0'>
				<input type='hidden' name='Faturado' value=''>
				<input type='hidden' name='QtdMesesVencimento' value='<?=getCodigoInterno(3,85)?>'>
				<input type='hidden' name='Terceiros' value=''>
				<input type='hidden' name='PercentualRepasseTerceiro' value=''>
				<input type='hidden' name='PercentualRepasseTerceiroOutros' value=''>
				<input type='hidden' name='AtualizarValorRepasseTerceiro' value='1'>
				<input type='hidden' name='SeletorContaCartao' value=''>
				<input type='hidden' name='ObrigatoriedadeContaCartao' value=''>
				
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(427)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(428)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(429)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdOrdemServico' value='' autocomplete="off" style='width:70px' maxlength='11' onChange='busca_ordem_servico(this.value,true,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoOrdemServico' style='width: 200px' disabled>
									<?
										$sql = "select IdTipoOrdemServico, DescricaoTipoOrdemServico from TipoOrdemServico order by DescricaoTipoOrdemServico";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdTipoOrdemServico]'>$lin[DescricaoTipoOrdemServico]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td><td class='campo'>
								<select name='IdSubTipoOrdemServico' style='width: 200px' disabled>
									<option value=''></option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='descricao' style='width: 310px;'><B id='cp_Status'>&nbsp;</B></td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosCliente'>
					<div id='cp_tit'><?=dicionario(177)?></div>
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
					<div id='cp_tit'><?=dicionario(221)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(27)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:36px'><?=dicionario(30)?></B><?=dicionario(223)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(224)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(225)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdContrato' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdServicoContrato' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='DescricaoServicoContrato' value='' style='width:380px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescPeriodicidadeContrato' value=''  style='width:150px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdParcelaContrato' value=''  style='width:91px' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(376)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(433)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(232)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(434)?>.</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(235)?>.</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(226)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataInicio' value='' style='width:120px' maxlength='10' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataTermino' value='' style='width:120px' maxlength='10' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataBaseCalculo' value='' style='width:120px' maxlength='10' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataUltimaCobranca' value='' style='width:120px' maxlength='10' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='AssinaturaContrato' style='width:123px' disabled>
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
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='TipoContrato' style='width:141px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" disabled>
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
						</tr>
					</table>
				</div>	
				<div id='cpDadosServico'>
					<div id='cp_tit' style='margin-top:0'><?=dicionario(435)?></div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:34px; color:#000'><?=dicionario(30)?></b><?=dicionario(223)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(436)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(437)?> (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdServico' value=''  style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:325px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoServico' style='width:146px' disabled>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=71 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' id='cpValor'>
								<input type='text' name='Valor' value='' style='width:113px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' id='cpValorOutros'>
								<input type='text' name='ValorOutros' value='' style='width:113px' maxlength='12' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(438)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(440)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='DetalheServico' style='width: 400px;' rows=5 readOnly></textarea>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<textarea name='DescricaoOS' style='width: 400px;' rows=5 readOnly></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div valign='top'>
					<div id='cp_tit'><?=dicionario(464)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo' style="width:77px;"><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titFormaCobranca'><?=dicionario(197)?></B></td>
							<td class='separador' id='septitTab'>&nbsp;</td>
							<td  id='titDisabled'>
								<table cellpadding='0' cellspacing='0' valign='botton' style='line-height:1%;padding-top:5px'>
									<tr>
										<td class='descCampo' id='tdtitLocalCob' style='line-height:01%'><B id='titLocalCobranca' style='display:none'><?=dicionario(40)?></B></td>
										<td class='descCampo' style='line-height:01%'><B id='titContrato' style='display:none'><?=dicionario(27)?></B></td>
										<td class='separador' id='septitValorDesp' style='line-height:01%'><span id='septitValorDespesas'>&nbsp;</span></td>
										<td class='descCampo' id='tdValorDespesas' style='line-height:01%' style='width:133px'><B id='titValorDespesas' style='display:none'><?=dicionario(392)?> (<?=getParametroSistema(5,1)?>)</B></td>
										<td class='separador' id='septitQtdParcela' style='line-height:01%;width:14px'>&nbsp;</span></td>
										<td class='descCampo' valign='botton' id='titcpQtdParcela'><B id='titQtdParcela' style='display:none'><?=dicionario(225)?></B></td>
										<td class='separador' id='septitDtPriVenCo' style='line-height:01%;width:14px'>&nbsp;</span></td>
										<td class='descCampo' valign='botton'><B id='titDataPrimeiroVencimentoContrato' style='display:none'><?=dicionario(391)?></B></td>
									</tr>
								</table>
							</td>
							<td class='descCampo'><B id='titDataPrimeiroVencimentoIndividual'><?=dicionario(393)?>.</B></td>
							<td />
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='ValorTotal' value='' style='width:77px' maxlength='16' onBlur="/*verificaValor(this.value)*/" onkeypress="reais(this,event)" onkeydown="backspace(this,event);" readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='FormaCobranca' id='cpFormaCobranca' style='width:120px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="document.formulario.FormaCobrancaTemp.value = document.formulario.FormaCobranca.value; return busca_forma_cobranca(this.value);"  tabindex='2'>
									<option value=''>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=50 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador' id='sepcpTab'>&nbsp;</td>
							<td id='cpDisabled'>
								<table cellpadding='0' cellspacing='0'>
									<tr>
										<!--td class='separador' id='sepcpLocalCob' valign='top'><span id='sepcpLocalCobranca'>&nbsp;</span></td-->
										<td class='campo' valign='top' id='tdcpLocalCob'>
											<select name='IdLocalCobranca' id='cpLocalCobranca' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" valign='top' style='width:240px;display:none'  onChange="atualizarSimulacao(); document.formulario.IdLocalCobrancaTemp.value = document.formulario.IdLocalCobranca.value; busca_local_cobranca(this.value,false,'OrdemServico');verificar_local_cobranca(this.value)" tabindex='3'>
												<option value='0'>&nbsp;</option>
												<?/*
													$sql = "select IdLocalCobranca, DescricaoLocalCobranca from LocalCobrancaGeracao where IdLoja=$local_IdLoja order by DescricaoLocalCobranca";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[IdLocalCobranca]'>$lin[DescricaoLocalCobranca]</option>";
													}*/
												?>
											</select>
										</td>
										<td class='separador' id='sepcpIdContrato' style='display:none;'>&nbsp;</td>
										<td class='campo' valign='top'>
											<select name='IdContratoAgrupador' id='cpContrato' onFocus="Foco(this,'in')" valign='top' style='width:330px; display:none' onBlur="Foco(this,'out')" onChange='atualizaPrimeiraReferencia(this.value); buscar_descricao_layout(this.value);' tabindex='4'>
												<option value='0'>&nbsp;</option>
											</select>
											<div valign='top' id='descricaoNotaFiscal'></div>
										</td>
										<td class='separador' id='sepcpValorDespesa'>&nbsp;</td>
										<td class='campo' valign='top' id='tdValorDespesa'>
											<input type='text' name='ValorDespesaLocalCobranca' id='cpValorDespesa' value='' valign='top' style='width:119px; display:none' maxlength='11' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onBlur="Foco(this,'out')" onChange="atualizarSimulacao();" tabindex='5'> 
										</td>
										<td class='separador' id='sepcpQtdParcela'>&nbsp;</td>
										<td class='campo' valign='top'>
											<input type='text' id='cpQtdParcela' name='QtdParcela' value='' valign='top' style='width:84px; display:none' maxlength='12' onChange="verificaParcela(this.value); atualizarSimulacao();" onFocus="Foco(this,'in')" onkeypress="mascara(this,event,'int')" onBlur="Foco(this,'out')"  tabindex='6'>
										</td> 
										<td class='separador' id='DvCo'>&nbsp;</td>
										<td class='campo' valign='top'>
											<input type='text' name='DataPrimeiroVencimentoContrato' id='cpDataPrimeiroVencimentoContrato' valign='top' value='<?=$local_DataPrimeiroVencimentoContrato?>' style='width:116px; display:none' maxlength='7' onChange="atualizarSimulacao()" onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'mes')"  tabindex='7'>
											<p id='obsDataPrimeiroVencimentoContrato' style='margin:0; padding:0; display:none'><?=dicionario(472)?></p>
										</td>
									</tr>
								</table>
							</td>
							<td class='campo' valign='top'>
								<input type='text' name='DataPrimeiroVencimentoIndividual' id='cpDataPrimeiroVencimentoIndividual' value='<?=$local_DataPrimeiroVencimentoIndividual?>' style='width:115px' maxlength='10' onChange="atualizarSimulacao()" onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('titDataPrimeiroVencimentoIndividual',this)"  tabindex='8'>
							</td>
							<td class='find' id='findDataPrimeiroVencimentoIndividualIco'>
								<table id='tbIco'>
									<tr>
										<td>
											<img id='cpDataPrimeiroVencimentoIndividualIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'>
										</td>
									</tr>
								</table>
							</td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataPrimeiroVencimentoIndividual",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataPrimeiroVencimentoIndividualIco"
							    });
							</script>
						</tr>
					</table>
					<table id='cpTerceiro' valign='top'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(33)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpPercRepasseMensal' style='color:#000'><?=dicionario(473)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpPercRepasseMensalOutros' style='color:#000;'><?=dicionario(474)?> (<?=getParametroSistema(5,1)?>)</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='vertical-align:top;'>
								<select type='text' name='IdTerceiro' value='' style='width:437px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onchange="obter_valor_repasse_terceiro(this.value);" tabindex='9' disabled>
									<option value=''>&nbsp;</option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorRepasseTerceiro' value='' style='width:176px' maxlength='8' readOnly>
								<p id='obsValorRepasseTerceiro' style='margin:0; padding:0;'>0,00 (%)</p>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorRepasseTerceiroOutros' value='' style='width:176px;' maxlength='8' readOnly>
								<p id='obsValorRepasseTerceiroOutros' style='margin:0; padding:0;'>0,00 (%)</p>
							</td>
						</tr>
					</table>
					<table style='width:851px' valign='top'>
						<tr>
							<td style='width:700px; margin:0;'>
								<table id='cpDataPrimeiroVencimento' style='display:none; margin:0; padding:0;'>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B id='titContratoInd' style='display:none; color:#000'><?=dicionario(27)?></B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><B id='titIdFormCarne'><?=dicionario(475)?></B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo' id='label_IdContaDebitoCartao' style='display: none;color: #c10000'><?=dicionario(244)?>(<?=getParametroSistema(5,1)?>)</td>
						
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo' valign='top'>
											<select name='IdContratoIndividual' id='cpContratoInd' onFocus="Foco(this,'in')"  style='width:330px;display:none' onBlur="Foco(this,'out')"  tabindex='10'>
												<option value='0'>&nbsp;</option>
											</select><B style='font-size:9px; font-weight:normal'><?=dicionario(476)?></b>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo' valign='top'>
											<select name='IdFormatoCarne' onFocus="Foco(this,'in')"  style='width:126px' onBlur="Foco(this,'out')"  tabindex='11'>
												<option value='0'>&nbsp;</option>
												<?
													$sql = "select
																IdParametroSistema,
																ValorParametroSistema
															from
																ParametroSistema
															where
																IdGrupoParametroSistema = 162
															order by
																ValorParametroSistema;";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
													}
												?>
											</select>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo' valign='top'>
											<select name='IdContaDebitoCartao' style='display: none; width: 155px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='11'>
												<option>&nbsp;</option>
											</select>
										</td>
									</tr>
								</table>
							</td>
							<td style='width:344px;' class='campo'>
								<table style='float:right;' valign='top'>
									<tr>
										<td>
											<input type='button' name='bt_simular' value='Simular' class='botao' onClick='simular()'  tabindex='12'>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_Vencimento' style='margin-bottom:0; display:none'>
					<div id='cp_tit' style='margin-bottom:0'><?=dicionario(396)?></div>
					<table id='tabelaVencimento' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'><?=dicionario(353)?></td>
							<td class='valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'><?=dicionario(397)?>. (%)</td>
							<td class='valor' id='tabValorDesp'><?=dicionario(392)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'><?=dicionario(398)?> (<?=getParametroSistema(5,1)?>)</td>
							<td id='tableDataVenc'><?=dicionario(399)?></td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' id='totalVencimentos'><?=dicionario(128)?>: 0</td>
							<td id='totalValor' class='valor'>0,00</td>
							<td id='totalPercentual' class='valor'>0,00</td>
							<td id='totalValorDespesa' class='valor'>0,00</td>
							<td id='totalValorTotal' class='valor'>0,00</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div id="cpEnderecoCorrespondencia" style='display:none;'>
					<div class='cp_tit'><?=dicionario(261)?></div>
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
								<select name='IdPessoaEnderecoCobranca' style='width:400px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_pessoa_endereco_cobranca(document.formulario.IdPessoa.value,this.value)" tabindex='10000'>
									<option value='0'></option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NomeResponsavelEnderecoCobranca' value='' style='width:406px' maxlength='100' readOnly>
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
								<input type='text' name='CEPCobranca' value='' style='width:70px' maxlength='9' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='EnderecoCobranca' value='' style='width:268px' maxlength='60' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NumeroCobranca' value='' style='width:55px' maxlength='5' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ComplementoCobranca' value='' style='width:161px' maxlength='30' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='BairroCobranca' value='' style='width:195px' maxlength='30' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:54px; color:#000'><?=dicionario(256)?></B><?=dicionario(257)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:38px; color:#000'><?=dicionario(157)?></b>Nome Estado</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:38px; color:#000'>Cidade</B>Nome Cidade</td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa_c.gif' alt='Buscar'></td>
							<td class='campo'>
								<input type='text' name='IdPaisCobranca' value='' style='width:70px' maxlength='11' readOnly><input  class='agrupador' type='text' name='PaisCobranca' value='' style='width:140px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa_c.gif' alt='Buscar'></td>
							<td class='campo'>
								<input type='text' name='IdEstadoCobranca' value='' style='width:70px' maxlength='11' readOnly><input class='agrupador' type='text' name='EstadoCobranca' value='' style='width:140px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa_c.gif' alt='Buscar'></td>
							<td class='campo'>
								<input type='text' name='IdCidadeCobranca' value='' style='width:70px' maxlength='11' readOnly><input class='agrupador' type='text' name='CidadeCobranca' value='' style='width:234px' maxlength='100' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_log'>
					<div id='cp_tit'>Log</div>
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
								<input type='text' name='DataAlteracao' value='' style='width:204px' readOnly>
							</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Usuário Faturamento</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Faturamento</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginFaturamento' value='' style='width:180px'  maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataFaturamento' value='' style='width:202px' readOnly>
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao'> 
					<table style='width:849px'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='button' style='width:110px' name='bt_voltar' value='Voltar' class='botao' tabindex='10001' onClick="voltar()">
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' style='width:100px' name='bt_enviar' value='Enviar E-mails' class='botao' tabindex='10002' onClick="cadastrar('enviar')">
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' style='width:100px; display:none;' name='bt_gerar' value='Gerar Boletos' class='botao' tabindex='10003' onClick="cadastrar('imprimir')">
								<input type='button' style='width:100px; display:none;' name='bt_imprimirCarne' value='Imprimir Carnê' class='botao' tabindex='10004' onClick="cadastrar('imprimirCarne')">
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' style='text-align:right;'>
								<input type='button' name='bt_cancelar' value='Cancelar Faturamento'  tabindex='10005' class='botao' onClick="selecionar_cancelamento(document.formulario.IdOrdemServico.value,document.formulario.FormaCobranca.value)">
								<input type='button' name='bt_alterar' value='Alterar'  tabindex='10006' class='botao' onClick="cadastrar('alterar')">
								<input type='button' name='bt_confirmar' value='Confirmar'  tabindex='10007' class='botao' onClick="cadastrar('confirmar')">
							</td>
						</tr>
					</table>
				</div>
				<div>
					<table> 
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText' name='helpText' style='margin-bottom:0'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>
			</form>
		</div>
		<div id='quadros_fluantes'>
			<?
				include("files/busca/terceiro.php");
			?>
		</div>
	</body>	
</html>
<script>
	<?
		if($local_IdOrdemServico != ""){
			echo "busca_ordem_servico($local_IdOrdemServico,false,document.formulario.Local.value);";
			echo "scrollWindow('bottom');";
		}
	?>
	function status_inicial(){
		document.formulario.IdFormatoCarne.value = '<?=getCodigoInterno(3,123)?>';
	}
	
	verificaAcao();
	verificaErro();
	inicia();
	enterAsTab(document.forms.formulario);
</script>