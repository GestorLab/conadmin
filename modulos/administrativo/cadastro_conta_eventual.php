<?
	$localModulo		=	1;
	$localOperacao		=	31;
	$localSuboperacao	=	"V";	

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdContaEventual						= formatText($_POST['IdContaEventual'],NULL);
	$local_IdPessoa								= formatText($_POST['IdPessoa'],NULL);
	$local_DescricaoContaEventual				= formatText($_POST['DescricaoContaEventual'],NULL);
	$local_IdLocalCobranca						= formatText($_POST['IdLocalCobranca'],NULL);
	$local_ValorTotalContrato					= formatText($_POST['ValorTotalContrato'],NULL);
	$local_ValorTotalIndividual					= formatText($_POST['ValorTotalIndividual'],NULL);
	$local_QtdParcelaContrato					= formatText($_POST['QtdParcelaContrato'],NULL);
	$local_QtdParcelaIndividual					= formatText($_POST['QtdParcelaIndividual'],NULL);
	$local_ValorDespesaLocalCobranca			= formatText($_POST['ValorDespesaLocalCobranca'],NULL);
	$local_ObsContaEventual						= formatText($_POST['ObsContaEventual'],NULL);
	$local_DataPrimeiroVencimentoContrato		= formatText($_POST['DataPrimeiroVencimentoContrato'],NULL);
	$local_DataPrimeiroVencimentoIndividual		= formatText($_POST['DataPrimeiroVencimentoIndividual'],NULL);
	$local_IdStatus								= formatText($_POST['IdStatus'],NULL);
	$local_FormaCobranca						= formatText($_POST['FormaCobranca'],NULL);
	$local_IdContratoAgrupador					= formatText($_POST['IdContratoAgrupador'],NULL);
	$local_IdContrato							= formatText($_POST['IdContrato'],NULL);
	$local_OcultarReferencia					= formatText($_POST['OcultarReferencia'],NULL);
	$local_IdPessoaEnderecoCobranca				= formatText($_POST['IdPessoaEnderecoCobranca'],NULL);
	$local_IdFormatoCarne						= formatText($_POST['IdFormatoCarne'],NULL);
	$local_IdContaDebitoCartao					= formatText($_POST['IdContaDebitoCartao'],NULL);	
	$local_SeletorContaCartao					= formatText($_POST['SeletorContaCartao'],NULL);
	$local_QtdMesesVencimento					= getCodigoInterno(3,85);
	
	
	if($_GET['IdContaEventual']!=''){
		$local_IdContaEventual	=	$_GET['IdContaEventual'];
	}
	if($_GET['IdPessoa']!=''){
		$local_IdPessoa	=	$_GET['IdPessoa'];
	}
	
	if($local_DataPrimeiroVencimentoContrato == ''){
		if($local_QtdMesesVencimento == 0){
			$local_DataPrimeiroVencimentoContrato	=	date('m/Y');
		}else{
			$local_DataPrimeiroVencimentoContrato	=   date("m/Y", mktime(0, 0, 0, date('m') + $local_QtdMesesVencimento,	date('d'), date('Y')) );;	
		}
	}
	if($local_DataPrimeiroVencimentoIndividual == ''){
		if($local_QtdMesesVencimento == 0){
			$local_DataPrimeiroVencimentoIndividual	=	date('d/m/Y');
		}else{
			$local_DataPrimeiroVencimentoIndividual = date("d/m/Y", mktime(0, 0, 0, date('m') + $local_QtdMesesVencimento,	date('d'), date('Y')) );
   		}
	}
	
	if($local_FormaCobranca == 2){
		$local_QtdParcela	=	$local_QtdParcelaIndividual;
	}else{
		$local_QtdParcela	=	$local_QtdParcelaContrato;
	}
	
	for($i=1;$i<=$local_QtdParcela;$i++){
		$local_Valor[$i] = formatText($_POST['parcValor_'.$i],NULL); 
		$local_Data[$i]  = formatText($_POST['parcData_'.$i],NULL); 
		$local_Desp[$i]  = formatText($_POST['parcDesp_'.$i],NULL); 
	}	

	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_conta_eventual.php');
			break;		
		case 'alterar':
			include('files/editar/editar_conta_eventual.php');
			break;
		case 'confirmar':
			include('rotinas/confirmar_conta_eventual.php');
			break;
		case 'cancelar':		
			$sql = "
					select
						count(*) QTD
					from
						LancamentoFinanceiro	
					where
						LancamentoFinanceiro.IdLoja = $local_IdLoja and	
						LancamentoFinanceiro.IdContaEventual = $local_IdContaEventual and
						LancamentoFinanceiro.IdStatus != 0";							
			$res	=	mysql_query($sql,$con);
			$lin	=	mysql_fetch_array($res);
							
			if($lin[QTD] >= 1){
				header("Location: cadastro_cancelar_lancamento_financeiro.php?IdContaEventual=$local_IdContaEventual");
			}else{
				include('files/editar/editar_cancelar_conta_eventual.php');
			}			
			break;
		case 'enviar':
			header("Location: cadastro_enviar_mensagem.php?IdContaEventual=$local_IdContaEventual");
			$local_Erro=64;
			break;
		case 'imprimir':
			$sql = "select 
						LocalCobranca.IdLocalCobrancaLayout
					from 
						ContaEventual,
						LocalCobranca
					where 
						ContaEventual.IdLoja = $local_IdLoja and
						ContaEventual.IdLoja = LocalCobranca.IdLoja and
						ContaEventual.IdContaEventual = $local_IdContaEventual and
						ContaEventual.IdLocalCobranca = LocalCobranca.IdLocalCobranca";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);
			
			$file = "local_cobranca/$lin[IdLocalCobrancaLayout]/pdf_all.php";
			$fileurl = $file."?IdLoja=$local_IdLoja&IdContaEventual=$local_IdContaEventual";
			
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
						ContaEventual,
						LancamentoFinanceiro,
						LancamentoFinanceiroContaReceber,
						ContaReceberDados,
						LocalCobranca
					where
						ContaEventual.IdLoja = $local_IdLoja and
						ContaEventual.IdContaEventual = $local_IdContaEventual and
						ContaEventual.IdLoja = LancamentoFinanceiro.IdLoja and
						ContaEventual.IdContaEventual = LancamentoFinanceiro.IdContaEventual and
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
			$local_Acao		= 'inserir';
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
		<script type = 'text/javascript' src = '../../js/val_mes.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/conta_eventual.js'></script>
		<script type = 'text/javascript' src = 'js/conta_eventual_default.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		<script type = 'text/javascript' src = 'js/local_cobranca_default.js'></script>
		
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
	<body  onLoad="ativaNome('Conta Eventual')">
		<? include('filtro_conta_eventual.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_conta_eventual.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='ContaEventual'>
				<input type='hidden' name='ValorCobrancaMinima' value=''>
				<input type='hidden' name='IdStatus' value=''>
				<input type='hidden' name='IdTipoLocalCobranca' value=''>
				<input type='hidden' name='AceitarValorNegativo' value='<?=permissaoSubOperacao($localModulo,$localOperacao,"N")?>'>
				<input type='hidden' name='SeletorContaCartao' value=''>
				<input type='hidden' name='ObrigatoriedadeContaCartao' value=''>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(28)?></td>			
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdContaEventual' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_conta_eventual(this.value,true,document.formulario.Local.value); scrollWindow('top');" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='descricao' id='cpStatus' style='width: 717px;' valign='top'><B id='cp_Status'>&nbsp;</B></td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosCliente'>
					<div id='cp_tit'><?=dicionario(219)?></div>
					<?	
						$nome="	
							<div id='cp_juridica'>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B style='margin-right:36px' id='titIdPessoa'>".dicionario(26)."</B>".dicionario(85)."</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo' id='cp_RazaoSocial_Titulo'>".dicionario(172)."</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>".dicionario(220)."</td>									
									</tr>
									<tr>
										<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\"></td>
										<td class='campo'>
											<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='2'><input type='text' class='agrupador' name='Nome' value='' style='width:279px' maxlength='100' readOnly>
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
								</table>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'>".dicionario(94)."</td>	
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>".dicionario(95)."</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>".dicionario(104)."</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='NomeRepresentante' value='' style='width:355px' maxlength='100' readOnly>
										</td>									
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='InscricaoEstadual' value='' style='width:150px' maxlength='20' readOnly>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='EmailJuridica' value='' style='width:258px' maxlength='255' readOnly>
										</td>
										<td class='find' onClick='JsMail(document.formulario.EmailJuridica.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
									</tr>
								</table>
							</div>						
							";
							
						$razao="	
							<div id='cp_juridica'>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B style='margin-right:36px' id='titIdPessoa'>".dicionario(26)."</B><B style='color:#000'>".dicionario(172)."</B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>".dicionario(92)."</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>".dicionario(220)."</td>										
									</tr>
									<tr>
										<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\"></td>
										<td class='campo'>
											<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='2'><input type='text' class='agrupador' name='RazaoSocial' value='' style='width:279px' maxlength='100' readOnly>
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
								</table>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'>".dicionario(94)."</td>	
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>".dicionario(95)."</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>".dicionario(176)."</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='NomeRepresentante' value='' style='width:355px' maxlength='100' readOnly>
										</td>									
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='InscricaoEstadual' value='' style='width:150px' maxlength='20' readOnly>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='EmailJuridica' value='' style='width:258px' maxlength='255' readOnly>
										</td>
										<td class='find' onClick='JsMail(document.formulario.EmailJuridica.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
									</tr>
								</table>
							</div>													
							";
							
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
							<td class='descCampo'><B style='margin-right:36px' id='titIdPessoaF'><?=dicionario(26)?></B><?=dicionario(85)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(87)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(104)?></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(210)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(89)?></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaPessoa', true, event, null, 165);"></td>
							<td class='campo'>
								<input type='text' name='IdPessoaF' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_pessoa(this.value,'false',document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'><input type='text' class='agrupador' name='NomeF' value='' style='width:220px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataNascimento' value='' style='width:70px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Email' value='' style='width:181px' readOnly>
							</td>
							<td class='find' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CPF' value='' style='width:100px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='RG' value='' style='width:80px' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(98)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(99)?> (1)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(99)?> (2)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(100)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(101)?></td>	
							<td class='separador'>&nbsp;</td>				
							<td class='descCampo'><?=dicionario(102)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone1' value='' style='width:122px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone2' value='' style='width:122px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone3' value='' style='width:122px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Celular' value='' style='width:122px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Fax' value='' style='width:122px' readOnly>
							</td>	
							<td class='separador'>&nbsp;</td>						
							<td class='campo'>
								<input type='text' name='ComplementoTelefone' value='' style='width:121px' readOnly>
							</td>			
						</tr>
					</table>
				</div>
				<div id='cp_dadosContrato'>
					<div id='cp_tit'><?=dicionario(389)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(386)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titIdFormaCobranca'><?=dicionario(197)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titOcultarReferencia'><?=dicionario(390)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescricaoContaEventual' value='' style='width:421px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  tabindex='3'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='FormaCobranca' style='width:186.5px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="busca_forma_cobranca(this.value);" tabindex='4'>
									<option value='0'>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=50 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='OcultarReferencia' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  style='width:186.5px' tabindex='5'>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=59 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
					<table id='titFormaCobranca' style='display:block'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='titValorTotal'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titQtdParcela'><?=dicionario(225)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorTotal' value='' style='width:100px' maxlength='16' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onBlur="Foco(this,'out')"  tabindex='6'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdParcela' value='' style='width:90px' maxlength='12' onFocus="Foco(this,'in')" onkeypress="mascara(this,event,'int')" onBlur="Foco(this,'out')"  tabindex='7'>
							</td> 
						</tr>
					</table>
					<table id='titContrato' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='titIdContratoAgrupador'><?=dicionario(27)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titValorTotalContrato'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titQtdParcelaContrato'><?=dicionario(225)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='DataPrimeiroVencimentoContrato'><?=dicionario(391)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='IdContratoAgrupador' onFocus="Foco(this,'in')"  style='width:320px' onBlur="Foco(this,'out')" onChange='atualizaPrimeiraReferencia(this.value); buscar_descricao_layout(this.value);' tabindex='8'>
									<option value='0'>&nbsp;</option>
								</select>
								<div id='descricaoNotaFiscal'></div>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='ValorTotalContrato' value='' style='width:120px' maxlength='16' onFocus="Foco(this,'in')" onkeydown='backspace(this,event,2)' onBlur="Foco(this,'out')" onChange="atualizarSimulacao();"  tabindex='9'>
								<!--input type='text' name='ValorTotalContrato' value='' style='width:120px' maxlength='16' onFocus="Foco(this,'in')" onkeypress="<?= permissaoSubOperacao($localModulo,$localOperacao,"N") ? "reais(this,event,2,'neg')" : "reais(this,event,2)" ?>" onkeydown='backspace(this,event,2)' onBlur="Foco(this,'out')" onChange="atualizarSimulacao();"  tabindex='9'-->
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='QtdParcelaContrato' value='' style='width:90px' maxlength='11' onFocus="Foco(this,'in')" onkeypress="mascara(this,event,'int')" onBlur="Foco(this,'out')" onChange="atualizarSimulacao();" tabindex='10'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='DataPrimeiroVencimentoContrato' id='cpDataPrimeiroVencimento' value='<?=$local_DataPrimeiroVencimentoContrato?>' style='width:115px' maxlength='7' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'mes')" onChange="atualizarSimulacao()" tabindex='11'>
								<p id='titDataPrimeiroVencimentoContrato' style='margin:0; padding:0; display:none'>Ref. do Contrato</p>
							</td>
						</tr>
					</table> 
					<div id='titIndividual' style='display:none'>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B id='titIdLocalCobranca'><?=dicionario(40)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B id='titValorTotalIndividual'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B id='titValorDespesaLocalCobranca'><?=dicionario(392)?> (<?=getParametroSistema(5,1)?>)</B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B id='titQtdParcelaIndividual'><?=dicionario(225)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B id='DataPrimeiroVencimentoIndividual'><?=dicionario(393)?>.</B></td>
								<td class='find'>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<select name='IdLocalCobranca' onFocus="Foco(this,'in')"  style='width:291px' onBlur="Foco(this,'out')" onChange="busca_local_cobranca(this.value,false,document.formulario.Local.value); atualizarSimulacao(); verificar_local_cobranca(this.value);"  tabindex='12'>
										<option value='0'>&nbsp;</option>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='ValorTotalIndividual' value='' style='width:119px' maxlength='16' onFocus="Foco(this,'in')" onkeypress='reais(this,event)' onkeydown='backspace(this,event)' onBlur="Foco(this,'out')" onChange="atualizarSimulacao();" tabindex='13'>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo' >
									<input type='text' name='ValorDespesaLocalCobranca' value='' style='width:119px' maxlength='16' onFocus="Foco(this,'in')" onkeypress='reais(this,event)' onkeydown='backspace(this,event)' onBlur="Foco(this,'out')" onChange="atualizarSimulacao();" tabindex='14'> 
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='QtdParcelaIndividual' value='' style='width:91px' maxlength='12' onFocus="Foco(this,'in')" onkeypress="mascara(this,event,'int')" onBlur="Foco(this,'out')" onChange="atualizarSimulacao();verificaParcela(this.value);" tabindex='15'>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataPrimeiroVencimentoIndividual' id='cpDataPrimeiroVencimentoIndividual' value='<?=$local_DataPrimeiroVencimentoIndividual?>' style='width:115px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('DataPrimeiroVencimentoIndividual',this); atualizarSimulacao()"  tabindex='16'>
								</td>
								<td class='find' id='cpDataPrimeiroVencimentoIndividualIco'><img src='../../img/estrutura_sistema/ico_date.gif' alt='<?=dicionario(394)?>'></td>
								<script type="text/javascript">
								    Calendar.setup({
								        inputField     : "cpDataPrimeiroVencimentoIndividual",
								        ifFormat       : "%d/%m/%Y",
								        button         : "cpDataPrimeiroVencimentoIndividualIco"
								    });
								</script>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'>Contrato</td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B id='titIdFormatoCarne'>Formato Carnê</B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo' id='label_IdContaDebitoCartao' style='display: none;color: #c10000'><?=dicionario(244)?>(<?=getParametroSistema(5,1)?>)</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<select name='IdContrato' onFocus="Foco(this,'in')"  style='width:291px' onBlur="Foco(this,'out')" onChange="atualizarSimulacao();" tabindex='17'>
										<option value='0'>&nbsp;</option>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<select name='IdFormatoCarne' onFocus="Foco(this,'in')"  style='width:126px' onBlur="Foco(this,'out')" onChange="atualizarSimulacao();" tabindex='18'>
										<option value='0'>&nbsp;</option>
										<?
											$sql = "select
														IdParametroSistema,
														ValorParametroSistema
													from
														ParametroSistema
													where
														IdGrupoParametroSistema = 161
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
								<td class='descCampo'>
									<select name='IdContaDebitoCartao' style='display: none; width: 155px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='18'>
										<option>&nbsp;</option>
									</select>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div style='height:20px;'class='cp_botao'>
					<table style='text-align:right; width:848px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_simular' value='<?=dicionario(395)?>' class='botao' onClick='simular()'  tabindex='19'>
							</td>
						</tr>
					</table>
					<BR>
				</div>
				<div id='cp_Vencimento' style='margin-bottom:0;display:none'>
					<div id='cp_tit' style='margin-bottom:0;margin-top:10px;'><?=dicionario(396)?></div>
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
							<td class='descCampo'><B id='titIdPessoaEnderecoCobranca'><?=dicionario(249)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(250)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdPessoaEnderecoCobranca' style='width:400px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_pessoa_endereco_cobranca(document.formulario.IdPessoa.value,this.value)" tabindex='290'>
									<option value='0'></option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NomeResponsavelEnderecoCobranca' value='' style='width:405px' maxlength='100' readOnly>
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
								<input type='text' name='BairroCobranca' value='' style='width:194px' maxlength='30' readOnly>
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
								<input type='text' name='IdCidadeCobranca' value='' style='width:70px' maxlength='11' readOnly><input class='agrupador' type='text' name='CidadeCobranca' value='' style='width:233px' maxlength='100' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_observacoes'>
					<div id='cp_tit'><?=dicionario(129)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(400)?></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='ObsContaEventual' style='width: 816px;' rows='5' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');"  tabindex='291'></textarea>
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
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(786)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(787)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='UsuarioConfirmacao' value='' style='width:180px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataConfirmacao' value='' style='width:203px'  readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'> 
					<table style='width:848px; text-align:right;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:left;'>
								<input type='button' style='width:100px;' name='bt_chegar' value='<?=dicionario(266)?>?' class='botao' tabindex='309' onClick='como_chegar()'>
							</td>
							<td class='campo' style=''>
								<input type='button' style='width:110px' name='bt_enviar' value='<?=dicionario(401)?>' class='botao' tabindex='310' onClick="cadastrar('enviar')">
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' style='width:100px; display:none;' name='bt_imprimir' value='<?=dicionario(402)?>' class='botao' tabindex='311' onClick="cadastrar('imprimir')">
								<input type='button' style='width:100px; display:none;' name='bt_imprimirCarne' value='<?=dicionario(403)?>' class='botao' tabindex='312' onClick="cadastrar('imprimirCarne')">
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' style='width:80px;' name='bt_inserir' value='<?=dicionario(146)?>'  tabindex='313' class='botao' onClick="cadastrar('inserir')">
								<input type='button' style='width:60px;' name='bt_alterar' value='<?=dicionario(15)?>'  tabindex='314' class='botao' onClick="cadastrar('alterar')">
								<input type='button' style='width:60px;' name='bt_excluir' value='<?=dicionario(147)?>'  tabindex='315' class='botao' onClick="excluir(document.formulario.IdContaEventual.value)">
								<input type='button' style='width:80px;' name='bt_confirmar' value='<?=dicionario(404)?>'  tabindex='316' class='botao' onClick="cadastrar('confirmar')">
								<input type='button' style='width:80px;' name='bt_cancelar' value='<?=dicionario(405)?>'  tabindex='317' class='botao' onClick="cadastrar('cancelar')">
							</td>
						</tr>
					</table>
				</div>
				<table style='width:500px'> 
					<tr>
						<td class='find'>&nbsp;</td>
						<td><h1 style='margin-bottom:0' id='helpText' name='helpText'>&nbsp;</h1></td>
					</tr>
				</table>
			</form>
		</div>
		<div id='quadros_fluantes'>
			<?
				include("files/busca/pessoa.php");
			?>
		</div>
	</body>	
</html>
<script>
	<?
		if($local_IdContaEventual != ""){
			echo "busca_conta_eventual($local_IdContaEventual,false,document.formulario.Local.value);";
			echo "scrollWindow('bottom');";
		}else{
			if($local_IdPessoa != ""){
				echo "busca_pessoa($local_IdPessoa,false,document.formulario.Local.value);";
			}
		}	
	?>
	function status_inicial(){
		if(document.formulario.ValorTotal.value == ''){
			document.formulario.ValorTotal.value = '0,00';
		}
		document.formulario.OcultarReferencia.value = '<?=getCodigoInterno(3,44)?>';
		document.formulario.IdFormatoCarne.value 	= '<?=getCodigoInterno(3,122)?>';
		
		if(document.formulario.AceitarValorNegativo.value == 1){
			document.formulario.ValorTotalContrato.onkeypress = function (event){
				reais(this,event,2,'neg');
			};
		} else{
			document.formulario.ValorTotalContrato.onkeypress = function (event){ 
				reais(this,event,2); 
			};
		}
	}
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>