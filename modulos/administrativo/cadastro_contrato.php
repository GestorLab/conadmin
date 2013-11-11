<?
	$localModulo		= 1;
	$localOperacao		= 2;
	$localSuboperacao	= "V";	
	$Path				= "../../";

	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	include('../../classes/envia_mensagem/envia_mensagem.php');
	
	$local_Login						= $_SESSION["Login"];
	$local_IdLoja						= $_SESSION["IdLoja"];
	$local_IdAgenteAutorizadoLogin		= $_SESSION["IdAgenteAutorizado"];
	$local_IdPessoaLogin				= $_SESSION["IdPessoa"];
	$local_Erro							= $_GET['Erro'];
	$local_Redirecionar					= $_GET['Redirecionar'];
	$local_Acao 						= $_POST['Acao'];
	$local_QuantParametros				= $_POST['QuantParametros'];
	$local_IdPessoa						= formatText($_POST['IdPessoa'],NULL);
	$local_IdServico					= formatText($_POST['IdServico'],NULL);
	$local_IdContrato					= formatText($_POST['IdContrato'],NULL);
	$local_DataInicio					= formatText($_POST['DataInicio'],NULL);
	$local_DataTermino					= formatText($_POST['DataTermino'],NULL);
	$local_DataBaseCalculo				= formatText($_POST['DataBaseCalculo'],NULL);
	$local_DataPrimeiraCobranca			= formatText($_POST['DataPrimeiraCobranca'],NULL);
	$local_DataUltimaCobranca			= formatText($_POST['DataUltimaCobranca'],NULL);
	$local_AssinaturaContrato			= formatText($_POST['AssinaturaContrato'],NULL);
	$local_IdLocalCobranca				= formatText($_POST['IdLocalCobranca'],NULL);
	$local_IdTipoLocalCobranca			= formatText($_POST['IdTipoLocalCobranca'],NULL);
	$local_IdAgenteAutorizado			= formatText($_POST['IdAgenteAutorizado'],NULL);
	$local_IdCarteira					= formatText($_POST['IdCarteira'],NULL);
	$local_IdContratoAgrupador			= formatText($_POST['IdContratoAgrupador'],NULL);
	$local_MesVencido					= formatText($_POST['MesVencido'],NULL);
	$local_ValorServico					= formatText($_POST['ValorServico'],NULL);
	$local_ValorRepasseTerceiro			= formatText($_POST['ValorRepasseTerceiro'],NULL);
	$local_TipoContrato					= formatText($_POST['TipoContrato'],NULL);
	$local_Obs							= formatText($_POST['Obs'],NULL);
	$local_AdequarLeisOrgaoPublico		= formatText($_POST['AdequarLeisOrgaoPublico'],NULL);
	$local_NotaFiscalCDA				= formatText($_POST['NotaFiscalCDA'],NULL);
	$local_NotaFiscalCDAAutomatico		= formatText($_POST['NotaFiscalCDAAutomatico'],NULL);
	$local_UrlContratoImpresso			= formatText($_POST['UrlContratoImpresso'],NULL);
	$local_IdPeriodicidade				= formatText($_POST['IdPeriodicidade'],NULL);
	$local_QtdParcela					= formatText($_POST['QtdParcela'],NULL);
	$local_MesFechado					= formatText($_POST['MesFechado'],NULL);
	$local_QtdMesesFidelidade			= formatText($_POST['QtdMesesFidelidade'],NULL);
	$local_MultaFidelidade				= formatText($_POST['MultaFidelidade'],NULL);
	$local_ServicoAutomatico			= formatText($_POST['ServicoAutomatico'],NULL);
	$local_DiaCobranca					= formatText($_POST['DiaCobranca'],NULL);
	$local_DiaCobrancaTemp				= formatText($_POST['DiaCobrancaTemp'],NULL);
	$local_IdContratoTipoVigencia		= formatText($_POST['IdContratoTipoVigencia'],NULL);
	$local_IdCarteiraTemp				= formatText($_POST['IdCarteiraTemp'],NULL);
	$local_IdPessoaEndereco				= formatText($_POST['IdPessoaEndereco'],NULL);
	$local_IdPessoaEnderecoCobranca		= formatText($_POST['IdPessoaEnderecoCobranca'],NULL);
	$local_CFOPServico					= formatText($_POST['CFOPServico'],NULL);
	$local_IdTerceiro					= formatText($_POST['IdTerceiro'],NULL);
	$local_TipoMonitor					= formatText($_POST['TipoMonitor'],NULL);
	$local_IdSNMP						= formatText($_POST['IdSNMP'],NULL);
	$local_AlterarVigencia				= formatText($_POST['AlterarVigencia'],NULL);	
	$local_IdContaDebitoCartao			= formatText($_POST['IdContaDebitoCartao'],NULL);	
	$local_SeletorContaCartao			= formatText($_POST['SeletorContaCartao'],NULL);	
	$local_NumeroContaDebitoCartao		= formatText($_POST['NumeroContaDebitoCartao'],NULL);	
	$local_ObsMsnErroParametro			= formatText($_POST['ObsMsnErroParametro'],NULL);
	
	if($_GET['IdContrato']!=''){
		$local_IdContrato	=	$_GET['IdContrato'];
	}

	if($_GET['ErroPersonalizado']!=''){
		$msg_erro_rotina = $_GET['ErroPersonalizado'];
	}

	if($_GET['IdPessoa']!=''){
		$local_IdPessoa	=	$_GET['IdPessoa'];
	}
	switch($local_Acao) {
		case 'inserir':
			include('files/inserir/inserir_contrato.php');
			break;		
		case 'alterar':
			include('files/editar/editar_contrato.php');
			break;
		/* Leonardo 09-11-12 -- Foi conversado com o douglas e não achamos a utilização dessas duas ações! 
		case 'Cancelar':
			header("Location: cadastro_cancelar_contrato.php?IdContrato=$local_IdContrato&Acao=Cancelar");
			break;
		case 'Ativar':
			header("Location: cadastro_cancelar_contrato.php?IdContrato=$local_IdContrato&Acao=Ativar");
			break;
		*/
		case 'fatura':
			header("Location: cadastro_contrato_ativar.php?IdContrato=$local_IdContrato");
			break;
		case 'carta':
			break;
		default:
			$local_Acao = 'inserir';
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/procurar.css' />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/mascara_real.js'></script>
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/contrato.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_busca_pessoa_aproximada.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		<script type = 'text/javascript' src = 'js/agente_default.js'></script>
		<script type = 'text/javascript' src = 'js/carteira_default.js'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_agrupador_default.js'></script>
		
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
			caption	{ text-align: left; font-weight: bold; color: #c10000; padding-bottom: 2px; }
		</style>
	</head>
	<body  onLoad="ativaNome('Contrato')">
		<? include('filtro_contrato.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_contrato.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Erro2' value='<?=$local_Erro2?>'>
				<input type='hidden' name='DescricaoParametroServico' value='<?=$local_desc_param?>'>
				<input type='hidden' name='IdAgenteAutorizadoLogin' value='<?=$local_IdAgenteAutorizadoLogin?>'>
				<input type='hidden' name='IdPessoaLogin' value='<?=$local_IdPessoaLogin?>'>
				<input type='hidden' name='Local' value='Contrato'>
				<input type='hidden' name='Periodicidade' value=''>
				<input type='hidden' name='UrlContratoImpresso' value=''>
				<input type='hidden' name='UrlDistratoImpresso' value=''>
				<input type='hidden' name='QuantParcela' value=''>
				<input type='hidden' name='IdLocalCobrancaTemp' value=''>
				<input type='hidden' name='TipoContratoTemp' value=''>
				<input type='hidden' name='QtdMesesFidelidadeTemp' value=''>
				<input type='hidden' name='MesFechadoTemp' value=''>
				<input type='hidden' name='Redirecionar' value='<?=$local_Redirecionar?>'>
				<input type='hidden' name='MultaFidelidade' value=''>
				<input type='hidden' name='IdStatus' value=''>
				<input type='hidden' name='ServicoAutomatico' value=''>
				<input type='hidden' name='IdTipoLocalCobranca' value=''>
				<input type='hidden' name='MoedaAtual' value='<?=getParametroSistema(5,1)?>'>
				<input type='hidden' name='DiaCobrancaDefault' value='<?=getCodigoInterno(3,6)?>'>
				<input type='hidden' name='DiaCobrancaTemp' value=''>
				<input type='hidden' name='IdContratoTipoVigencia' value=''>
				<input type='hidden' name='IdCarteiraTemp' value=''>
				<input type='hidden' name='AvisoContrato' value='0'>
				<input type='hidden' name='AvisoServico' value='<?=getCodigoInterno(3,79)?>'>
				<input type='hidden' name='SiglaEstado' value=''>
				<input type='hidden' name='VarStatus' value=''>
				<input type='hidden' name='NotaFiscalCDADefault' value=''>
				<input type='hidden' name='StatusTempoAlteracao' value=''>
				<input type='hidden' name='PreenchimentoAutomaticoEndereco' value='<?=getCodigoInterno(3,127)?>'>
				<input type='hidden' name='MonitorSinalIdGrafico' value='<?=getCodigoInterno(3,151)?>'>
				<input type='hidden' name='MonitorSinalAtualizacaoAutomatica' value='<?=$_SESSION["filtro_MonitorAtualizacaoAutomatica"]?>'>
				<input type='hidden' name='PercentualRepasseTerceiroTemp' value=''>
				<input type='hidden' name='Terceiros' value=''>
				<input type='hidden' name='ObrigatorioTerceiro' value='<?=getCodigoInterno(11,5)?>'>
				<input type='hidden' name='IdLoja' value='<?=$local_IdLoja?>'>
				<input type='hidden' name='AlterarVigencia' value='0'>
				<input type='hidden' name='AtivarResumoConexao' value='<?=(getCodigoInterno(10000,1) != '' ? 1 : 2)?>'>
				<input type='hidden' name='ResumoConexaoAbertoDefault' value='<?=getCodigoInterno(3,184)?>'>
				<input type='hidden' name='MonitorAbertoDefault' value='<?=getCodigoInterno(3,185)?>'>
				<input type='hidden' name='Filtro_IdPaisEstadoCidade' value=''>
				<input type='hidden' name='IdEnderecoDefault' value=''>
				<input type='hidden' name='SeletorContaCartao' value=''>
				<input type='hidden' name='ObrigatoriedadeContaCartao' value=''>
				<input type='hidden' name='PermissaoEditarContrato' value='<?=permissaoSubOperacao($localModulo,$localOperacao,"U")?>'>
				<input type='hidden' name='PermissaoEditarParametroContrato' value='<?=permissaoSubOperacao($localModulo,$localOperacao,"U")?>'>
				<input type='hidden' name='NumeroContaDebitoCartao' value=''>
				<input type='hidden' name='SelecionarCamposUmaOpcao' value='<?=getCodigoInterno(3,237)?>'>
				
				<div>
					<table style='width:845px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(27)?></td>
							<td class='find'></td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdContrato' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_contrato(this.value,true,document.formulario.Local.value); scrollWindow('top');" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='find'>&nbsp;</td>
							<td class='descricao' style='width:500px;'><B id='cpStatusContrato'>&nbsp;</B></td>
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
										<td class='descCampo'><B style='margin-right:36px'>".dicionario(26)."</B>".dicionario(85)."</td>
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
											<input type='text' name='EmailJuridica' value='' style='width:257px' maxlength='255' readOnly>
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
										<td class='descCampo'><B style='margin-right:36px'>Pessoa</B><B style='color:#000'>".dicionario(172)."</B></td>
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
											<input type='text' name='EmailJuridica' value='' style='width:257px' maxlength='255' readOnly>
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
					<table id='cp_fisica' style='display:none;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:36px'><?=dicionario(26)?></B><?=dicionario(85)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(87)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(104)?></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>CPF</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>RG</td>
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
								<input type='text' name='Email' value='' style='width:180px' readOnly>
							</td>
							<td class='find' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CPF' value='' style='width:100px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='RG' value='' style='width:81px' readOnly>
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
					<div id='cp_tit' style='margin-top:6px'><?=dicionario(221)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'> 
								<table style='padding:0; margin:-2px;' cellpascing='0' cellpading='0'>
									<tr>
										<td><B id='cpServico'><?=dicionario(222)?></B></td>
										<td style='padding-left:34px'><?=dicionario(223)?></td>
										<td style='color:#000; padding-left:3px; display:none;' id='titMudarServico'>[<a href='#' id='mudarServico' onClick="mudar_servico()">Mudar Serviço</a>]</font></td>
									</tr>
								</table>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(367)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(224)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpQtdParcela'><?=dicionario(225)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpTipoContrato'><?=dicionario(226)?></B></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaServico', true, event, null, 285);"></td>
							<td class='campo'>
								<input type='text' name='IdServico' value=''  style='width:70px' maxlength='11' onChange="busca_servico(this.value,true,document.formulario.Local.value,'busca');busca_dia_cobranca(document.formulario.IdPessoa.value,document.formulario.DiaCobrancaDefault.value,document.formulario.Acao.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='3'><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:288px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescricaoServicoGrupo' style='width: 135px' readonly='readonly'/>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdPeriodicidade' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:100px' tabindex='4' onChange="servico_periodicidade_parcelas(document.formulario.IdServico.value,this.value,'busca'); calculaPeriodicidade(this.value,document.formulario.ValorServico.value); calculaPeriodicidadeTerceiro(document.formulario.IdPeriodicidade.value,document.formulario.ValorRepasseTerceiro.value,document.formulario.ValorPeriodicidadeTerceiro); calculaServicoAutomatico(this.value); verificar_local_cobranca(document.formulario.IdLocalCobranca.value);selecionaCampos()">
									<option value='' selected></option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='QtdParcela' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:80px' tabindex='5' OnChange="document.formulario.QuantParcela.value=document.formulario.QtdParcela.value; busca_servico_tipo_contrato(document.formulario.IdServico.value,document.formulario.IdPeriodicidade.value,this.value,'busca')">
									<option value='' selected></option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='TipoContrato' style='width:88px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='6' OnChange="document.formulario.TipoContratoTemp.value=document.formulario.TipoContrato.value; busca_servico_local_cobranca(document.formulario.IdServico.value,document.formulario.IdPeriodicidade.value,document.formulario.QtdParcela.value,this.value,'busca');">
									<option value=''>&nbsp;</option>
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
							<td class='descCampo'><?=dicionario(228)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(229)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdLocalCobranca' onFocus="Foco(this,'in')"  style='width:268px' onBlur="Foco(this,'out')" tabindex='7' onChange="document.formulario.IdLocalCobrancaTemp.value=document.formulario.IdLocalCobranca.value; busca_servico_mes_fechado(document.formulario.IdServico.value,document.formulario.IdPeriodicidade.value,document.formulario.QtdParcela.value,document.formulario.TipoContrato.value,this.value,'busca'); listarLocalCobrancaParametro(this.value,true);verificar_local_cobranca(this.value);">
									<option value=''>&nbsp;</option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='MesFechado' onFocus="Foco(this,'in')"  style='width:80px' onBlur="Foco(this,'out')" tabindex='8' onChange="document.formulario.MesFechadoTemp.value=document.formulario.MesFechado.value;">
									<option value=''>&nbsp;</option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select type='text' name='IdContratoAgrupador' value='' style='width:371px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="selecionaVencimento(this.value)" tabindex='9'>
									<option value='0'>&nbsp;</option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td>
								<select name='DiaCobranca' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='10' style='width: 70px'>
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
							<td class='descCampo'><B id='DataInicio'><?=dicionario(230)?></B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='DataPrimeiraCobranca'><?=dicionario(231)?></B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(232)?></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000000' id='DataTermino'><?=dicionario(233)?></B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B  id='DataUltimaCobranca' style='color:#000000'><?=dicionario(234)?></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(235)?>.</B></td>
						</tr>
						<tr> 
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataInicio' id='cpDataInicio' value='' style='width:105px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('DataInicio',this);" tabindex='11'>
							</td>
							<td class='find'><img id='cpDataInicioIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataInicio",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataInicioIco"
							    });
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataPrimeiraCobranca' id='cpDataPrimeiraCobranca' value='' style='width:105px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('DataPrimeiraCobranca',this);verificar_data_primeira_cobranca(document.formulario.IdContrato.value, this.value, document.formulario.Acao.value)" tabindex='12'>
							</td>
							<td class='find'><img id='cpDataPrimeiraCobrancaIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataPrimeiraCobranca",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataPrimeiraCobrancaIco"
							    });
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataBaseCalculo' value='' style='width:105px' maxlength='10' readOnly>
							</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataTermino' id='cpDataTermino' value='' style='width:102px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')"  onChange="validar_Data('DataTermino',this); /*verificaDataFinal('DataInicio',document.formulario.DataInicio.value,this.value);*/" tabindex='13'>
							</td>
							<td class='find'>
								<span id='imgDataTermino'><img id='cpDataTerminoIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></span>
								<span id='imgDataTerminoDisab'style='display:none'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></span>
							</td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataTermino",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataTerminoIco"
							    });
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataUltimaCobranca' id='cpDataUltimaCobranca' value='' style='width:105px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('DataUltimaCobranca',this)" tabindex='14'>
							</td>
							<td class='find'>
								<span id='imgDataUltimaCobranca'><img id='cpDataUltimaCobrancaIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></span>
								<span id='imgDataUltimaCobrancaDisab' style='display:none'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></span>
							</td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataUltimaCobranca",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataUltimaCobrancaIco"
							    });
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='AssinaturaContrato' style='width:103px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='15'>
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
					<table id='cpAgenteCarteira'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B <?if(getCodigoInterno(11,1) != 2){ echo "style='color:#000'";}?>  id='cpAgenteAutorizado'><?=dicionario(116)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B <?if(getCodigoInterno(11,1) != 1){ echo "style='color:#000'";}?>  id='cpCarteira'><?=dicionario(117)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B <?if(getCodigoInterno(11,5) != 1){ echo "style='color:#000'";}?> id='cpTerceiro'><?=dicionario(33)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdAgenteAutorizado' style='width:280px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="listar_carteira(this.value);" tabindex='16'>
									<option value=''>&nbsp;</option>
									<?
										$sql = "SELECT
													AgenteAutorizado.IdAgenteAutorizado,
													if(Pessoa.Nome != '', Pessoa.Nome, Pessoa.RazaoSocial) Nome
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
								<select type='text' name='IdCarteira' value='' style='width:260px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='17' disabled>
									<option value=''>&nbsp;</option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select type='text' name='IdTerceiro' value='' style='width:260px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onchange="obter_valor_repasse_terceiro(this.value);" tabindex='18' disabled>
									<option value=''>&nbsp;</option>
								</select>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='titValorServico'><?=dicionario(237)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000' id='titDesconto'><?=dicionario(238)?></B> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(239)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(240)?> (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorServico' value='' style='width:191px' maxlength='15' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onBlur="Foco(this,'out'); calculaPeriodicidadeTerceiro(document.formulario.IdPeriodicidade.value,document.formulario.ValorRepasseTerceiro.value,document.formulario.ValorPeriodicidadeTerceiro); calculaPeriodicidade(document.formulario.IdPeriodicidade.value,this.value);"  tabindex='19'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorDesconto' value='' style='width:201px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorFinal' value='' style='width:191px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorPeriodicidade' value='' style='width:182px' maxlength='12' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(241)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(242)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(243)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(244)?>(<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' id='label_IdContaDebitoCartao' style='display: none;color: #c10000'><?=dicionario(244)?>(<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorRepasseTerceiro' value='' style='width:191px' maxlength='15' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorPeriodicidadeTerceiro' value='' style='width:201px' maxlength='12' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdMesesFidelidade' value='' style='width:191px' maxlength='12' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" tabindex='20'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorMultaFidelidade' value='' style='width:182px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>
								<select name='IdContaDebitoCartao' style='display: none; width: 155px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='22' onchange='atualizar_NumeroContaDebito()'>
									<option>&nbsp;</option>
								</select>
							</td>
						</tr>
					</table>
					<table style='margin:0;' cellpadding='0' cellspacing='0'>
						<tr>
							<td>
								<table id='cpAdequarLeis' style='display:none'>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B><?=dicionario(245)?></B></td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<select name='AdequarLeisOrgaoPublico' style='width:197px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='21'>
												<option value='0'>&nbsp;</option>
												<?
													$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=43 order by ValorParametroSistema";
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
								<table id='cpNotaFiscalCDA' style='display:none;' >
									<tr>		
										<td id='cpNotaFiscalCDAMudarClass' class='find'>&nbsp;</td>
										<td class='descCampo'><?=dicionario(246)?></td>
									</tr>
									<tr>	
										<td class='separador'>&nbsp;</td>										
										<td>											
											<select name='NotaFiscalCDA' style='width:205px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='22'>
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
											<select name='CFOPServico' style='width:605px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='23'>
												<option value='0'>&nbsp;</option>
											</select>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>				
				</div>	
				<div>
					<div class='cp_tit'><?=dicionario(248)?></div>
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
								<select name='IdPessoaEndereco' style='width:400px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_pessoa_endereco(document.formulario.IdPessoa.value,this.value)" tabindex='24'>
									<option value='0'></option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NomeResponsavelEndereco' value='' style='width:405px' maxlength='100' readOnly>
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
							<td class='descCampo'><?=dicionario(254)?>º</td>
							<td class='separador'>&nbsp;</td>					
							<td class='descCampo'><?=dicionario(255)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(160)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CEP' value='' style='width:70px' maxlength='9' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Endereco' value='' style='width:268px' maxlength='60' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Numero' value='' style='width:55px' maxlength='5' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Complemento' value='' style='width:161px' maxlength='30' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Bairro' value='' style='width:194px' maxlength='30' readOnly>
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
								<input type='text' name='IdPais' value='' style='width:70px' maxlength='11' readOnly><input  class='agrupador' type='text' name='Pais' value='' style='width:140px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa_c.gif' alt='Buscar'></td>
							<td class='campo'>
								<input type='text' name='IdEstado' value='' style='width:70px' maxlength='11' readOnly><input class='agrupador' type='text' name='Estado' value='' style='width:140px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa_c.gif' alt='Buscar'></td>
							<td class='campo'>
								<input type='text' name='IdCidade' value='' style='width:70px' maxlength='11' readOnly><input class='agrupador' type='text' name='Cidade' value='' style='width:233px' maxlength='100' readOnly>
							</td>
						</tr>
					</table>
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
								<select name='IdPessoaEnderecoCobranca' style='width:400px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_pessoa_endereco_cobranca(document.formulario.IdPessoa.value,this.value)" tabindex='24'>
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
							<td class='descCampo'><?=dicionario(254)?>º</td>
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
				<div id='cp_parametrosServico' style='margin-bottom:0; display:none'>
					<div id='cp_tit'><?=dicionario(262)?></div>
					<table id='tabelaParametro' style='margin:0;'><tr><td></td></tr></table>
				</div>
				<div id='cp_parametrosLocalCobranca' style='margin-bottom:0; display:none'>
					<div id='cp_tit'><?=dicionario(263)?></div>
					<table id='tabelaParametroLocalCobranca'>
						<tr><td></td></tr>
					</table>
				</div>
				<div id='cp_automatico' style='display:none'></div>
				<div id='cp_observacoes'>
					<div id='cp_tit'><?=dicionario(129)?></div>
					<table id='cpHistorico' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(130)?> [<a style='cursor:pointer' onClick="atualizarHistorico(document.formulario.IdContrato.value)"><?=dicionario(264)?></a>]</td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='HistoricoObs' style='width:816px;' rows='5' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" readOnly></textarea>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(265)?></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='Obs' style='width:816px;' rows='5' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='1000'></textarea>
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
							<td class='descCampo'><?=dicionario(134)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(135)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAlteracao' value='' style='width:180px' maxlength='20' readOnly>
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
							<td class='campo' style='text-align:left'>
								<input type='button' name='bt_chegar' value='<?=dicionario(266)?>?' class='botao' style='width:111px;' tabindex='1002' onClick='como_chegar()'>
							</td>
							<td class='campo'>
								<input type='button' name='bt_relatorio' value='<?=dicionario(142)?>' class='botao' style='width:100px' tabindex='1003' onClick="cadastrar('Relatorio')" disabled>
							</td>
							<td class='campo'>&nbsp;</td>
							<td class='campo'>
								<input type='button' name='bt_fatura' value='<?=dicionario(267)?>' class='botao' style='width:100px' tabindex='1003' onClick="cadastrar('fatura')" disabled>
							</td>
							<td class='campo'>&nbsp;</td>
							<td class='campo'>&nbsp;</td>
							<td class='campo' style='padding-right:2px'>
								<input type='button' name='bt_carta' value='<?=dicionario(268)?>' class='botao' style='display:none; width:140px' tabindex='1004' onClick="cadastrar('carta')">
							</td>
							<td class='campo'>
								<input type='button' id='BtImprimirDistrato' name='bt_imprimir_distrato' value='<?=dicionario(269)?>' style='width:120px' class='botao' tabindex='1005' onClick="cadastrar('imprimirDistrato')" disabled>
							</td>
							<td class='campo'>&nbsp;</td>
							<td class='campo'>
								<input type='button' id='BtImprimirContrato' name='bt_imprimir_contrato' value='<?=dicionario(270)?>' style='width:120px' class='botao' tabindex='1006' onClick="cadastrar('imprimirContrato')" disabled>
							</td>
							<td class='campo'>&nbsp;</td>
							<td class='campo'>&nbsp;</td>
							<td class='campo' style='float:right;'>
								<input type='button' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' style='width:70px;' tabindex='1007' onClick="cadastrar('inserir')">
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' style='width:50px;' tabindex='1008' onClick="cadastrar('alterar')">
								<input type='button' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' style='width:50px;' tabindex='1009' onClick="excluir(document.formulario.IdContrato.value,document.formulario.IdStatus.value)">
							</td>
						</tr>
					</table>
				</div>
				<div>	
					<table style='width:100%;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 style='margin-bottom:0' id='helpText' name='helpText'>&nbsp;</h1></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 style='margin-bottom:0' id='helpText2' name='helpText2'>&nbsp;</h1></td>
						</tr>						
					</table>
				</div>
				<div id='cp_resumoConexao' style='display:none;'>
					<div id='cp_tit'>
						<div style='width:848px;'><div style='float:left;'><?=dicionario(271)?></div><div style='float:right; width:22px;'><img id='botaoQuadroConexao' style='float:right; cursor:pointer; margin-right:4px;' onClick="ocultarQuadroConexao(document.getElementById('botaoQuadroConexao'), 'tableQuadroConexao');" title='Minimizar' alt='Minimizar' src='../../img/estrutura_sistema/ico_seta_down.gif' /></div>&nbsp;</div>
					</div>
					<table id="tableQuadroConexao" style="display:none; line-height:12px;">
						<tr>
							<td class='find'>&nbsp;</td>
							<td style='height:160px;'>
								<table cellpadding='0' cellspacing='0'>
									<tr>
										<td>
											<div id="tbResumoUltimaConexaoAtiva" style="display:none; margin-right:8px;">
												<b style="color:#c10000;"><?=dicionario(272)?></b>
												<div id="carregando_ResumoUltimaConexaoAtiva" style="margin-top:2px;">
													<table cellpadding="0" cellspacing="0" style="border:1px solid #a4a4a4; width:212px; background-color:#c7efc7;">
														<tbody>
															<tr>
																<td style="height:116px; padding:1px 2px; vertical-align:top;">
																	<table id="tabelaResumoUltimaConexaoAtiva"></table>
																</td>
															</tr>
														</tbody>
														<tfoot>
															<tr>
																<td style="text-align:center; padding:0 4px 4px;">
																	<input type="button" name="bt_atualizar_ultima_conexao_ativa" value="Atualizar" class="botao" onClick="busca_ultima_conexao_ativa(document.formulario.IdContrato.value);" />
																	<input type="button" name="bt_derrubar_ultima_conexao_ativa" value="Derrubar Conexão" class='botao' onClick="derrubar_conexao(document.formulario.IdContrato.value,'busca_ultima_conexao_ativa','carregando_ResumoUltimaConexaoAtiva');" />
																</td>
															</tr>
														</tfoot>
													</table>
												</div>
											</div>
										</td>
										<td>
											<div id="tbResumoUltimaConexaoEncerrada" style="display:none; margin-right:8px;">
												<b style="color:#c10000;"><?=dicionario(273)?></b>
												<div id="carregando_ResumoUltimaConexaoEncerrada" style="margin-top:2px;">
													<table cellpadding="0" cellspacing="0" style="border:1px solid #a4a4a4; width:252px;">
														<tbody>
															<tr>
																<td style="height:116px; padding:1px 2px; vertical-align:top;">
																	<table id="tabelaResumoUltimaConexaoEncerrada"></table>
																</td>
															</tr>
														</tbody>
														<tfoot>
															<tr>
																<td style="text-align:center; padding:0 4px 4px;">
																	<input type="button" name="bt_atualizar_ultima_conexao_encerrada" value="Atualizar" class="botao" onClick="busca_ultima_conexao_encerrada(document.formulario.IdContrato.value);" />
																</td>
															</tr>
														</tfoot>
													</table>
												</div>
											</div>
										</td>
										<td>
											<div id="tbResumoInformacaoGeral" style="display:none;">
												<b style="color:#c10000;"><?=dicionario(274)?></b>
												<div id="carregando_ResumoInformacaoGeral" style="margin-top:2px;">
													<table cellpadding="0" cellspacing="0" style="border:1px solid #a4a4a4; width:342px;">
														<tbody>
															<tr>
																<td style="height:140px; padding:1px 2px; vertical-align:top;">
																	<table id="tabelaResumoInformacaoGeral" cellspacing="0" style="width:100%;"></table>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_monitor' style='display:none;'>
					<div id='cp_tit'>
						<div style='width:848px;'><div style='float:left;'><?=dicionario(68)?></div><div style='float:right; width:22px;'><img id='botaoQuadroMonitor' style='float:right; cursor:pointer; margin-right:4px;' onClick="ocultarQuadroMonitor(document.getElementById('botaoQuadroMonitor'), 'tableQuadroMonitor');" title='Minimizar' alt='Minimizar' src='../../img/estrutura_sistema/ico_seta_down.gif' /></div>&nbsp;</div>
					</div>
					<table id='tableQuadroMonitor' style='display:none;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td style='vertical-align:top;'>
								<div id='cp_MonitorSinal' style='display:none;'>
									<table>
										<tr>
											<td></td>
											<td style='color: #C10000'><b>Log Radius</b></td>
										</tr>
										<tr>
											<td><img src='##' name='img_MonitorSinal' /></td>
											<td style='border: 1px solid #A4A4A4; width: 666px; vertical-align: top;'>
												<div id='carregando_ResumoLogRadius'>
													<table id="tabelaResumoLogRadius" cellspacing="0" style="width:100%;">
														<tr>
															<td id='td_logRadiusconteudo' style='height: 228px'></td>
														</tr>
													</table>
													<table id="tabelaResumoLogRadiusAtualizar" cellspacing="0" style="width:100%;">
														<tr>
															<td>
																<center><input type='button' value='Atualizar' onclick='buscarLogRadius()' /></center>
															</td>
														</tr>
													</table>
												</div>
											</td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<?
				/*<div id='cp_MonitorSinal' style='display:none;'>
					<div class='cp_tit'>Monitor</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td style='height:294px;'><img src='##' name='img_MonitorTrafego' /></td>
						</tr>
						<tr><td class='find'>&nbsp;</td><td /></tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td style='height:294px;'><img src='##' name='img_MonitorSinal' /></td>
						</tr>
					</table>
					<table style='margin:-620px 0 0 687px;'>
						<tr>
							<td class='descCampo' colspan='2'>Tipo Gráfico</td>
						</tr>
						<tr>
							<td class='campo' colspan='2'>
								<select name='IdTipoGrafico' style='width:140px;' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onchange='atualizar_grafico()' style='display:none;'>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=199 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td><input style='margin-left:0;' type='checkbox' class='checkbox' name='AtualizacaoAutomatica' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onclick="atualizar_grafico()"></td>
							<td>Atualização automática.</td>
						</tr>
					</table>
				</div>*/
				?>
			</form>
			<div id='cp_ordemServicoCliente' style='margin-bottom:0; display:none;'>
				<div id='cp_tit' style='margin-bottom:0'><?=dicionario(442)?></div>
				<table id='tabelaOrdemServicoCliente' class='tableListarCad' cellspacing='0' style='width: 100%'>
					<tr class='tableListarTitleCad'>
						<td class='tableListarEspaco' style='width: 40px;padding:0 0 0 5px'><b><?=dicionario(141)?></b></td>
						<td><b><?=dicionario(113)?></b></td>
						<td><b><?=dicionario(125)?></b></td>
						<td><b><?=dicionario(443)?></b></td>
						<td><b><?=dicionario(444)?>.</b></td>
						<td><b><?=dicionario(445)?></b></td>
						<td><b><?=dicionario(431)?>.</b></td>
						<td><b><?=dicionario(140)?></b></td>
					</tr>
					<tr class='tableListarTitleCad'>
						<td class='tableListarEspaco' colspan='8' id='tabelaOrdemServicoClienteTotal'></td>
					</tr>
				</table>
			</div>
			<div id='cp_protocoloCliente' style='margin-bottom:0; display:block;'>
				<div id='cp_tit' style='margin-bottom:0'>Protocolo Cliente</div>
				<table id='tabelaprotocoloCliente' class='tableListarCad' cellspacing='0' style='width: 100%'>
					<tr class='tableListarTitleCad'>
						<td class='tableListarEspaco' style='width: 40px;padding:0 0 0 5px'><b><?=dicionario(141)?></b></td>
						<td><b>Assunto</b></td>
						<td><b>Tipo Protocolo</b></td>
						<td><b>Responsavél</b></td>
						<td><b>Usuário de Abertura</b></td>
						<td><b>Data de Abertura</b></td>
						<td><b>Status</b></td>
					</tr>
					<tr class='tableListarTitleCad'>
						<td class='tableListarEspaco' colspan='8' id='tabelaprotocoloClienteTotal'></td>
					</tr>
				</table>
			</div>
		</div>
		
		<div id='quadros_fluantes'>
			<?
				include("files/busca/pessoa.php");
				include("files/busca/servico_contrato.php");
				include("files/busca/agente.php");
			?>
		</div>
	</body>	
</html>
<script>
	<?
		if($local_IdPessoa != ""){
			echo "busca_pessoa($local_IdPessoa,false,document.formulario.Local.value);";
		}
		
		if($local_IdContrato != ""){
			echo "busca_contrato($local_IdContrato,false,document.formulario.Local.value);";
			echo "scrollWindow('bottom');";
		}
		
		if($msg_erro_rotina != ""){
			echo "help2('$msg_erro_rotina','atencao');";
		}
	?>
	
	function status_inicial(){
		if(document.formulario.DiaCobranca.value == 0){
			document.formulario.DiaCobranca.value	=	'<?=getCodigoInterno(3,6)?>';
		}
		
		if(document.formulario.AssinaturaContrato.value == '' || document.formulario.AssinaturaContrato.value == 0){
			document.formulario.AssinaturaContrato.value = '<?=getCodigoInterno(3,10)?>';
		}
		
		if('<?=getCodigoInterno(13,1)?>' == '2'){
			document.getElementById('cpAgenteCarteira').style.display	=	'block';	
		}else{
			document.getElementById('cpAgenteCarteira').style.display	=	'none';	
		}
		
		if('<?=getCodigoInterno(11,1)?>' == '1'){
			document.getElementById('cpAgenteAutorizado').style.color	=	'#C10000';	
			document.getElementById('cpCarteira').style.color			=	'#C10000';	
		}else{
			document.getElementById('cpAgenteAutorizado').style.color	=	'#000';	
			document.getElementById('cpCarteira').style.color	=	'#000';	
		}
		
		if('<?=getCodigoInterno(11,5)?>' == '1'){
			document.getElementById('cpTerceiro').style.color	=	'#C10000';		
		}else{
			document.getElementById('cpTerceiro').style.color	=	'#000';	
		}
		
		if('<?=getCodigoInterno(3,52)?>' == '1'){
			document.getElementById('cpAdequarLeis').style.display			=	'block';
			document.getElementById("cpNotaFiscalCDAMudarClass").className	=	'separador';
			
			if(document.formulario.AdequarLeisOrgaoPublico.value == 0){
				document.formulario.AdequarLeisOrgaoPublico.value = '<?=getCodigoInterno(3,28)?>';
			}		
		}else{
			document.getElementById('cpAdequarLeis').style.display			=	'none';
			document.getElementById("cpNotaFiscalCDAMudarClass").className	=	'find';
		}
		
		/*Sim*/
		if('<?=getCodigoInterno(3,54)?>' == '1'){
			document.getElementById('imgDataTermino').style.display				=	'block';
			document.getElementById('imgDataUltimaCobranca').style.display		=	'block';
			
			document.getElementById('imgDataTerminoDisab').style.display		=	'none';
			document.getElementById('imgDataUltimaCobrancaDisab').style.display	=	'none';

			document.formulario.DataTermino.readOnly			=	false;
			document.formulario.DataUltimaCobranca.readOnly		=	false;					
		}else{
			document.getElementById('imgDataTermino').style.display				=	'none';
			document.getElementById('imgDataUltimaCobranca').style.display		=	'none';
			
			document.getElementById('imgDataTerminoDisab').style.display		=	'block';
			document.getElementById('imgDataUltimaCobrancaDisab').style.display	=	'block';
	
			document.formulario.DataTermino.readOnly			=	true;
			document.formulario.DataUltimaCobranca.readOnly		=	true;					
		}
		
		if(document.formulario.NotaFiscalCDA.value == 0 && document.formulario.IdContrato.value == ""){
			document.formulario.NotaFiscalCDA.value	= '';
			document.formulario.NotaFiscalCDADefault.value 	= '<?=getCodigoInterno(3,110)?>';				
		}		
	}
	function validar(){
		var qtdMin="";
		if(document.formulario.IdPessoa.value==''){
			mensagens(1);
			document.formulario.IdPessoa.focus();
			return false;
		}
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
		if(document.formulario.DiaCobranca.value == 0 || document.formulario.DiaCobranca.value == ''){
			mensagens(1);
			document.formulario.DiaCobranca.focus();
			return false;
		}
		if(document.formulario.DataInicio.value==""){
			mensagens(1);
			document.formulario.DataInicio.focus();
			return false;
		}else{
			if(isData(document.formulario.DataInicio.value) == false){		
				document.getElementById('DataInicio').style.backgroundColor = '#C10000';
				document.getElementById('DataInicio').style.color='#FFFFFF';
				mensagens(27);
				return false;
			}
			else{
				document.getElementById('DataInicio').style.backgroundColor='#FFFFFF';
				document.getElementById('DataInicio').style.color='#C10000';
				mensagens(0);
			}
		}
		if(document.formulario.DataPrimeiraCobranca.value==""){
			mensagens(1);
			document.formulario.DataPrimeiraCobranca.focus();
			return false;
		}else{
			if(isData(document.formulario.DataPrimeiraCobranca.value) == false){		
				document.getElementById('DataPrimeiraCobranca').style.backgroundColor = '#C10000';
				document.getElementById('DataPrimeiraCobranca').style.color='#FFFFFF';
				mensagens(27);
				return false;
			}
			else{
				document.getElementById('DataPrimeiraCobranca').style.backgroundColor='#FFFFFF';
				document.getElementById('DataPrimeiraCobranca').style.color='#C10000';
				mensagens(0);
			}
		}
		if(document.formulario.Local.value == 'inserir'){
			if(document.formulario.DataTermino.value != ""){
				if(isData(document.formulario.DataTermino.value) == false){		
					document.getElementById('DataTermino').style.backgroundColor = '#C10000';
					document.getElementById('DataTermino').style.color='#FFFFFF';
					document.formulario.DataTermino.focus();
					mensagens(27);
					return false;
				}
				else{
					if(verificaDataFinal('DataInicio',document.formulario.DataInicio.value,document.formulario.DataTermino.value)== false){
						document.formulario.DataInicio.focus();
						mensagens(39);
						return false;	
					}
					document.getElementById('DataInicio').style.backgroundColor='#FFFFFF';
					document.getElementById('DataInicio').style.color='#C10000';
					mensagens(0);
				}
			}
			if(document.formulario.DataUltimaCobranca.value != ""){
				if(isData(document.formulario.DataUltimaCobranca.value) == false){		
					document.getElementById('DataUltimaCobranca').style.backgroundColor = '#C10000';
					document.getElementById('DataUltimaCobranca').style.color='#FFFFFF';
					mensagens(27);
					return false;
				}
				else{
					document.getElementById('DataUltimaCobranca').style.backgroundColor='#FFFFFF';
					document.getElementById('DataUltimaCobranca').style.color='#000000';
					mensagens(0);
				}
			}
		}
		if(document.formulario.AssinaturaContrato.value==0){
			mensagens(1);
			document.formulario.AssinaturaContrato.focus();
			return false;
		}
		if('<?=getCodigoInterno(13,1)?>' == '2'){
			if('<?=getCodigoInterno(11,1)?>' == '1'){
				if(document.formulario.IdAgenteAutorizado.value==''){
					mensagens(1);
					document.formulario.IdAgenteAutorizado.focus();
					return false;
				}
				if(document.formulario.IdCarteira.value=='' || document.formulario.IdCarteira.value=='0'){
					mensagens(1);
					if(document.formulario.IdCarteira.options.length > 0){
						document.formulario.IdCarteira.focus();
					}
					return false;
				}
			}
		}
		if(Number(document.formulario.ObrigatorioTerceiro.value) == 1 && !document.formulario.IdTerceiro.disabled && document.formulario.IdTerceiro.value == '') {
			mensagens(1);
			document.formulario.IdTerceiro.focus();
			return false;
		}
		if(document.formulario.Acao.value == 'inserir'){
			if(document.formulario.ValorServico.value==''){
				mensagens(1);
				document.formulario.ValorServico.focus();
				return false;
			}
			if(document.formulario.ValorRepasseTerceiro.value==''){
				document.formulario.ValorRepasseTerceiro.value = '0,00';
			}
		}
		
		if(document.formulario.IdContaDebitoCartao.value == "" && document.formulario.ObrigatoriedadeContaCartao.value == 1){
			mensagens(1);
			document.formulario.IdContaDebitoCartao.focus();
			return false;
		}
		
		if(document.getElementById("cpServicoCFOP").style.display == "block" && document.formulario.CFOPServico.value == ''){
			mensagens(1);
			document.formulario.CFOPServico.focus();
			return false;
		}
		if('<?=getCodigoInterno(3,52)?>' == '1'){
			if(document.formulario.AdequarLeisOrgaoPublico.value==0){
				mensagens(1);
				document.formulario.AdequarLeisOrgaoPublico.focus();
				return false;
			}
		}
		if(document.formulario.IdPessoaEndereco.value=='0'){
			mensagens(1);
			document.formulario.IdPessoaEndereco.focus();
			return false;
		}
		if(document.formulario.IdPessoaEnderecoCobranca.value=='0'){
			mensagens(1);
			document.formulario.IdPessoaEnderecoCobranca.focus();
			return false;
		}
		var posInicial=0,posFinal=0,temp=0;
		if(document.formulario.IdServico.value!=''){
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
				for(i = posInicial; i<=posFinal; i=i+4){
					if(document.formulario[i+1].value == '1'){
						if(document.formulario[i].type == 'text'){
						 	if(document.formulario[i+2].value == '1'){
								if(document.formulario[i].value == ''){
									mensagens(1);
									document.formulario[i].focus();
									return false;
								} else{
									if(document.formulario[i+3].value!="" && parseInt(document.formulario[i].value.length) < parseInt(document.formulario[i+3].value)){
										qtdMin	=	document.formulario[i+3].value+' ('+conta(document.formulario[i+3].value)+')';
										mensagens(130,'',qtdMin);
										document.formulario[i].focus();
										return false;
									}									
								}
							}
						} else if(document.formulario[i].type == 'password'){
						 	if(document.formulario[i+2].value == '1'){
								 if(document.formulario[i].value == ''){
									mensagens(1);
									document.formulario[i].focus();
									return false;
								} else{
									if(document.formulario[i+3].value!="" && parseInt(document.formulario[i].value.length) < parseInt(document.formulario[i+3].value)){
										qtdMin	=	document.formulario[i+3].value+' ('+conta(document.formulario[i+3].value)+')';
										mensagens(130,'',qtdMin);
										document.formulario[i].focus();
										return false;
									}
								}
							}
						} else if(document.formulario[i].type == 'select-one'){
							var cont = 0;
							
							for(j=0;j<document.formulario[i].options.length;j++){
							 	if(document.formulario[i][j].selected == true && document.formulario[i][j].value != ""){
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
					} else{
						if(document.formulario[i+2].value == '1' && (document.formulario[i].type == 'password' || document.formulario[i].type == 'text') && document.formulario[i].value != '' && document.formulario[i+3].value != "" && parseInt(document.formulario[i].value.length) < parseInt(document.formulario[i+3].value)){
							qtdMin	=	document.formulario[i+3].value+' ('+conta(document.formulario[i+3].value)+')';
							mensagens(130,'',qtdMin);
							document.formulario[i].focus();
							return false;
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
						 	if(document.formulario[i].value == ''&& document.formulario[i+2].value == '1'){
								mensagens(1);
								document.formulario[i].focus();
								return false;
							}
						}
						if(document.formulario[i].type == 'select-one'){
							var cont = 0;
							 for(j=0;j<document.formulario[i].options.length;j++){
							 	if(document.formulario[i][j].selected == true && document.formulario[i][j].value != ""){
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
								if(document.formulario[ii+2].value == '1'){
								 	if(document.formulario[ii].value == ''){
										mensagens(1);
										document.formulario[ii].focus();
										return false;
									}else{
										if(document.formulario[ii+3].value!="" && parseInt(document.formulario[ii].value.length) < parseInt(document.formulario[ii+3].value)){
											qtdMin	=	document.formulario[ii+3].value+' ('+conta(document.formulario[ii+3].value)+')';
											mensagens(130,'',qtdMin);
											document.formulario[ii].focus();
											return false;
										}
									}
								}
							}
							if(document.formulario[ii].type == 'password'){
								if(document.formulario[ii+2].value == '1'){
							 		if(document.formulario[ii].value == ''){
										mensagens(1);
										document.formulario[ii].focus();
										return false;
									}else{
										if(document.formulario[ii+3].value!="" && parseInt(document.formulario[ii].value.length) < parseInt(document.formulario[ii+3].value)){
											qtdMin	=	document.formulario[ii+3].value+' ('+conta(document.formulario[ii+3].value)+')';
											mensagens(130,'',qtdMin);
											document.formulario[ii].focus();
											return false;
										}
									}
								}
							}
							if(document.formulario[ii].type == 'select-one'){
								var cont = 0;
								 for(j=0;j<document.formulario[ii].options.length;j++){
								 	if(document.formulario[ii][j].selected == true && document.formulario[ii][j].value != ""){
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
						} else{
							if(document.formulario[ii+2].value == '1' && (document.formulario[ii].type == 'password' || document.formulario[ii].type == 'text') && document.formulario[ii].value != '' && document.formulario[ii+3].value!="" && parseInt(document.formulario[ii].value.length) < parseInt(document.formulario[ii+3].value)){
								qtdMin	=	document.formulario[ii+3].value+' ('+conta(document.formulario[ii+3].value)+')';
								mensagens(130,'',qtdMin);
								document.formulario[ii].focus();
								return false;
							}
						}
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
	function verificaDistrato(){
		if(document.formulario.DataTermino.value!="" && document.formulario.DataUltimaCobranca.value !="")
			document.formulario.bt_imprimir_distrato.disabled= false;
			
	}               
	verificaErro();	
	mensagens2(document.formulario.Erro2.value,document.formulario.DescricaoParametroServico.value);
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
	verificarPermissaoEdicao(document.formulario.PermissaoEditarContrato.value,document.formulario.PermissaoEditarParametroContrato.value);
	verificaDistrato();
</script>
