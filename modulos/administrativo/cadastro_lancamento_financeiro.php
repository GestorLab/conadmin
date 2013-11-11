<?
	$localModulo		=	1;
	$localOperacao		=	18;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdLancamentoFinanceiro	= $_POST['IdLancamentoFinanceiro'];
	$local_ValorRepasseTerceiro		= $_POST['ValorRepasseTerceiro'];
	$local_ValorDescontoAConceber	= $_POST['ValorDescontoAConceber'];
	$local_DataReferenciaInicial	= $_POST['DataReferenciaInicial'];
	$local_DataReferenciaFinal		= $_POST['DataReferenciaFinal'];
	$local_Edit						= $_GET['Edit'];
		
	if($_GET['IdLancamentoFinanceiro']!=''){
		$local_IdLancamentoFinanceiro	= $_GET['IdLancamentoFinanceiro'];	
	}
	
	if($_POST['Edit'] != ''){
		$local_Edit	= $_POST['Edit'];
	}
	
	if($local_Edit == '1'){
		$local_EditTemp = $local_Edit;
		$local_Edit = '';
	} else{
		$local_EditTemp = '';
		$local_Edit = "readOnly";
	}
	
	switch($local_Acao){
		case 'alterar':
			include('files/editar/editar_lancamento_financeiro.php');
			break;
		case 'cancelar':
			header("Location: cadastro_cancelar_lancamento_financeiro.php?IdLancamentoFinanceiro=$local_IdLancamentoFinanceiro");
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
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/lancamento_financeiro.js'></script>
		<script type = 'text/javascript' src = 'js/lancamento_financeiro_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_default.js'></script>
		<script type = 'text/javascript' src = 'js/conta_eventual_default.js'></script>
		<script type = 'text/javascript' src = 'js/processo_financeiro_default.js'></script>
		<script type = 'text/javascript' src = 'js/ordem_servico_default.js'></script>
		<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(649)?>')">
		<? include('filtro_lancamento_financeiro.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_lancamento_financeiro.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='CursorPos' value='<?=$local_CursorPos?>'>
				<input type='hidden' name='IdContaReceber' value=''>
				<input type='hidden' name='IdStatusContaReceber' value=''>
				<input type='hidden' name='Local' value='LancamentoFinanceiro'>
				<input type='hidden' name='Edit' value='<?=$local_EditTemp?>'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(650)?>.</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdLancamentoFinanceiro' value='' autocomplete="off" style='width: 73px' maxlength='11' onChange='busca_lancamento_financeiro(this.value,true,document.formulario.Local.Value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='descricao' id='cpStatus' style='width: 730px;' valign='top'><B id='cp_Status'>&nbsp;</B></td>
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
									<td class='descCampo'><B style='margin-right:36px; color:#000'>Pessoa</B><B style='color:#000'><?=dicionario(172)?></B></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><?=dicionario(92)?></td>
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
				<div id='cpContrato' style='display:none'>
					<div id='cp_tit'><?=dicionario(221)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Contrato</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:36px'><?=dicionario(30)?></B><?=dicionario(223)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(224)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(651)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(235)?>.</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdContrato' value='' autocomplete="off" style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" onChange="busca_contrato(this.value,false,document.formulario.Local.value)" tabindex='2'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdServico' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:300px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescPeriodicidade' value=''  style='width:131px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdParcela' value=''  style='width:90px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='AssinaturaContrato' style='width:90px' disabled>
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
							<td class='descCampo'><?=dicionario(226)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id="titDataReferenciaInicial" style='color:#000'><?=dicionario(652)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id="titDataReferenciaFinal" style='color:#000'><?=dicionario(653)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataInicio' value='' style='width:100px' maxlength='10' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataTermino' value='' style='width:100px' maxlength='10' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataBaseCalculo' value='' style='width:100px' maxlength='10' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataUltimaCobranca' value='' style='width:100px' maxlength='10' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='TipoContrato' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" disabled>
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
							<td class='campo'>
								<input type='text' name='DataReferenciaInicial' value='' style='width:100px' maxlength='10' onkeypress="mascara(this,event,'date')" onChange="validar_Data('titDataReferenciaInicial', this);" onFocus="Foco(this,'in',true)" onBlur="Foco(this,'out')" tabindex="3" <?= $local_Edit ?>>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' >
								<input type='text' name='DataReferenciaFinal' value='' style='width:100px' maxlength='10' onkeypress="mascara(this,event,'date')" onChange="validar_Data('titDataReferenciaFinal', this);" onFocus="Foco(this,'in',true)" onBlur="Foco(this,'out')" tabindex="4" <?= $local_Edit ?>> 
							</td>
						</tr>
					</table>
				</div>
				<div id='cpContaEventual' style='display:none'>
					<div class='cp_tit' id='titContaEventual'><?=dicionario(386)?></div>
					<table id='ContaEventualIndividual'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:10px'><?=dicionario(654)?>.</B><?=dicionario(386)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(197)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdContaEventual' value='' autocomplete="off" style='width:70px' readOnly><input type='text' class='agrupador' name='DescricaoContaEventual' value='' style='width:582px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='FormaCobranca' style='width:150px' disabled>
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
						</tr>
					</table>
					<table id='tableOrdemServico'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(655)?>.</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:36px'><?=dicionario(30)?></B><?=dicionario(223)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(197)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdOrdemServico' value='' autocomplete="off" style='width:70px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdServicoOS' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='DescricaoServicoOS' value='' style='width:495px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='FormaCobrancaOS' style='width:150px' disabled>
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
						</tr>
					</table>
					<table id='cpContaEventualContrato'>
						<tr>
							<td class='find'>&nbsp;</td>						
							<td class='descCampo'><?=dicionario(27)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(516)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(391)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdContratoAgrupador' style='width:400px' disabled>
									<option value='0'>&nbsp;</option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorTotalContrato' value='' style='width:150px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdParcelaContrato' value='' style='width:102px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataPrimeiroVencimentoContrato' value='' style='width:120px' maxlength='7' readOnly>
							</td>
						</tr>
					</table>
					<table id='cpContaEventualIndividual'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(40)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(392)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(516)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(393)?>.</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdLocalCobranca' style='width:311px' disabled>
									<option value='0'>&nbsp;</option>
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
								<input type='text' name='ValorTotalIndividual' value='' style='width:119px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' >
								<input type='text' name='ValorDespesaLocalCobranca' value='' style='width:119px' maxlength='12' readOnly> 
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdParcelaIndividual' value='' style='width:91px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataPrimeiroVencimentoIndividual' value='' style='width:115px' maxlength='10' readOnly>
							</td>
						</tr>
					</table>
				</div>	
				<div id='cpProcessoFinaceiro' style='display:none'>
					<div id='cp_tit'><?=dicionario(656)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(657)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(196)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(508)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(658)?>.</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(40)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(659)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdProcessoFinanceiro' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='MesReferencia' value='' style='width:90px' maxlength='7' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='MesVencimento' value='' style='width:90px' maxlength='7' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='MenorVencimento' style='width:90px' disabled>
									<option value='' selected></option>
									<?
										$sql = "select IdCodigoInterno, ValorCodigoInterno from CodigoInterno where IdLoja=$local_IdLoja and IdGrupoCodigoInterno=1 order by IdCodigoInterno";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdCodigoInterno]'>".visualizarNumber($lin[ValorCodigoInterno])."</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='Filtro_IdLocalCobranca' style='width:285px' disabled>
									<option value=''>&nbsp;</option>
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
								<select name='Filtro_TipoLancamento' style='width:126px' disabled>
									<option value=''>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=49 order by ValorParametroSistema";
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
				<div>
					<div id='cp_tit'><?=dicionario(660)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(661)?>(<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(662)?>(<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(663)?>(%)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(206)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(56)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Valor' value='' style='width:120px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorRepasseTerceiro' value='' style='width:130px' maxlength='12' onkeypress="mascara(this,event,'float');" onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onChange="verificar_valor();">
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorDescontoAConceber' value='' style='width:120px'  maxlength='12' onFocus="Foco(this,'in')" onkeypress="mascara(this,event,'float')" onBlur="Foco(this,'out');" onChange="calculaValorFinal(document.formulario.Valor.value,this.value,document.formulario.PercentualDesconto.value,this)" readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='PercentualDesconto' value='' style='width:120px' maxlength='12' onFocus="Foco(this,'in')" onkeypress="mascara(this,event,'float')" onBlur="Foco(this,'out');" onChange="calculaValorFinal(document.formulario.Valor.value,document.formulario.ValorDescontoAConceber.value,this.value,this)" readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorFinal' value='' style='width:120px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdStatus' style='display:none;' readOnly />
								<input type='text' name='ContasReceber' style='width: 122px;' readOnly />
							</td>
						</tr>
					</table>
				</div>
				<div id="cp_Observacoes" style="display:none;">
					<div id='cp_tit'><?=dicionario(159)?></div>
					<table>
						<tr>
							<td class="find"></td>
							<td class="descCampo">
								<textarea name='HistoricoObs' style='width:816px;' rows='5' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" readOnly></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div id='cpObservacoesLog' style='display:none'>
					<div class='cp_tit' id='titTitulo'><?=dicionario(664)?></div>				
					<table id='cpObsCancelamento'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='cpObs' style='color:#000'><?=dicionario(665)?></B></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='ObsLancamentoFinanceiro' style='width: 816px;' rows=5 readOnly></textarea>
							</td>
						</tr>
					</table>
					<table id='cpLogCancelamento'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(666)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(667)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginCancelamento' value='' style='width:180px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCancelamento' value='' style='width:202px'  readOnly>
							</td>
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='5' onClick="cadastrar('alterar')">
								<input type='button' name='bt_cancelar' value='<?=dicionario(405)?>' class='botao' tabindex='6' onClick="cadastrar('cancelar')">
							</td>
						</tr>
					</table>
					<table style='width:100%;'>
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
	if($local_IdLancamentoFinanceiro!=''){
		echo "busca_lancamento_financeiro($local_IdLancamentoFinanceiro,false,document.formulario.Local.value);";		
	}
?>
//	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>
