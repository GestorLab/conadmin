<?
	$localModulo		= 1;
	$localOperacao		= 26;
	$localSuboperacao	= "V";
	$Path				= "../../";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	include('../../classes/envia_mensagem/envia_mensagem.php');
	
	$local_Login						= $_SESSION["Login"];
	$local_IdLoja						= $_SESSION["IdLoja"];
	$local_Acao 						= $_POST['Acao'];
	$local_Erro							= $_GET['Erro'];
	$local_IdServico					= $_POST['IdServico'];
	$local_IdOrdemServico				= $_POST['IdOrdemServico'];
	$local_IdTipoOrdemServico			= $_POST['IdTipoOrdemServico'];
	$local_IdSubTipoOrdemServico		= $_POST['IdSubTipoOrdemServico'];
	$local_IdSubTipoOrdemServicoTemp	= $_POST['IdSubTipoOrdemServicoTemp'];
	$local_IdPessoaEnderecoTemp			= $_POST['IdPessoaEnderecoTemp'];
	$local_IdContrato					= $_POST['IdContrato'];
	$local_IdServicoContrato			= $_POST['IdServicoContrato'];
	$local_IdPessoa						= $_POST['IdPessoa'];
	$local_IdStatus						= $_POST['IdStatus'];
	$local_IdStatusNovo					= $_POST['IdStatusNovo'];
	$local_DescricaoOS					= formatText($_POST['DescricaoOS'],NULL);
	$local_DescricaoOSInterna			= formatText($_POST['DescricaoOSInterna'],NULL);
	$local_Valor						= formatText($_POST['Valor'],NULL);
	$local_ValorFinal					= formatText($_POST['ValorFinal'],NULL);
	$local_Obs_OS						= formatText($_POST['Obs'],NULL);
	$local_Data							= formatText($_POST['Data'],NULL);
	$local_Hora							= formatText($_POST['Hora'],NULL);
	$local_IdGrupoUsuarioAtendimento	= formatText($_POST['IdGrupoUsuarioAtendimento'],NULL);
	$local_DescricaoCDA					= formatText($_POST['DescricaoCDA'],NULL);
	$local_NovaDescricaoOsCDA			= formatText($_POST['NovaDescricaoOsCDA'],NULL);
	$local_ValorOutros					= formatText($_POST['ValorOutros'],NULL);
	$local_ValorTotal					= formatText($_POST['ValorTotal'],NULL);
	$local_Justificativa				= formatText($_POST['Justificativa'],NULL);
	$local_LoginAtendimento				= trim($_POST['LoginAtendimento']);
	$local_FormaCobranca				= $_POST['FormaCobranca'];
	$local_FormaCobrancaTemp			= $_POST['FormaCobrancaTemp'];
	$local_LoginCriacao					= $_POST['LoginCriacao'];
	$local_IdTipoOrdemServicoTemp		= $_POST['IdTipoOrdemServicoTemp'];
	$local_IdPessoaEndereco				= $_POST['IdPessoaEndereco'];
	$local_IdMarcador					= $_POST['IdMarcador'];
	$local_EcluirAnexos					= $_POST['EcluirAnexos'];
	$local_IdAgenteAutorizado			= $_POST['IdAgenteAutorizado'];
	$local_IdCarteira					= $_POST['IdCarteira'];
	$local_DataConclusao				= $_POST['DataConclusao'];
	$local_LoginConclusao				= $_POST['LoginConclusao'];
	$local_LoginSupervisor				= $_POST['LoginSupervisor'];
	$local_DataFaturamento				= $_POST['DataFaturamento'];
	$local_LoginFaturamento				= $_POST['LoginFaturamento'];
	$local_UsuarioAtendimentoAntigo		= $_POST['UsuarioAtendimentoAntigo'];
	$local_PermissaoFatura				= "true";
	
	if($_GET['IdOrdemServico']!=''){
		$local_IdOrdemServico	= $_GET['IdOrdemServico'];	
	}else{
		if($_GET['IdPessoa']!=''){
			$local_IdPessoa			= $_GET['IdPessoa'];	
		}
		if($_GET['IdContrato']!=''){
			$local_IdContrato		= $_GET['IdContrato'];	
		}
	}

	switch($local_Acao) {
		case 'inserir':
			include('files/inserir/inserir_ordem_servico.php');
			break;		
		case 'alterar':
			include('files/editar/editar_ordem_servico.php');
			break;
		default:
			$local_Acao = 'inserir';
	}
	
	##################### permissão cancelar OS ####################	
	
	$local_PermissaoCancelarOS = false;
	if(permissaoSubOperacao($localModulo,$localOperacao,'C') == true){
		$local_PermissaoCancelarOS = true;			
	}
	echo $LogPrograma ;
	include("../../files/header.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/procurar.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>		
		<script type = 'text/javascript' src = '../../js/mascara_real.js'></script>
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/val_time.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/ordem_servico.js'></script>
		<script type = 'text/javascript' src = 'js/ordem_servico_busca_pessoa_aproximada.js'></script>
		<script type = 'text/javascript' src = 'js/ordem_servico_default.js'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_default.js'></script>
		<script type = 'text/javascript' src = 'js/usuario_default.js'></script>
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
	<body  onLoad="ativaNome('<?=dicionario(29)?>')">
		<? include('filtro_ordem_servico.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_ordem_servico.php' onSubmit='return validar()' enctype='multipart/form-data'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='OrdemServico'>
				<input type='hidden' name='LocalResponsavel' value=''>
				<input type='hidden' name='Login' value='<?=$local_Login?>'>
				<input type='hidden' name='IdStatus' value=''>
				<input type='hidden' name='FormaCobrancaTemp' value=''>
				<input type='hidden' name='Visualizar' value=''>
				<input type='hidden' name='ServicoAutomatico' value=''>
				<input type='hidden' name='MoedaAtual' value='<?=getParametroSistema(5,1)?>'>
				<input type='hidden' name='Periodicidade' value=''>
				<input type='hidden' name='IdPessoaEnderecoTemp' value=''>
				<input type='hidden' name='IdTipoOrdemServicoTemp' value=''>
				<input type='hidden' name='IdSubTipoOrdemServicoTemp' value=''>
				<input type='hidden' name='IdTipoOrdemServicoDefault' value='<?=getCodigoInterno(3,73)?>'>
				<input type='hidden' name='IdSubTipoOrdemServicoDefault' value='<?=getCodigoInterno(3,84)?>'>
				<input type='hidden' name='SiglaEstado' value=''>
				<input type='hidden' name='IdMarcador' value=''>
				<input type='hidden' name='IdMarcadorDefault' value='<?=getCodigoInterno(3,98)?>'>			
				<input type='hidden' name='LoginAtendente' value=''>
				<input type='hidden' name='IdOrdemServicoLayout' value=''>
				<input type='hidden' name='IdOrdemServicoLayoutImprimir' value=''>
				<input type='hidden' name='PermissaoGeralOsTipoAtendimento' value=''>
				<input type='hidden' name='PermissaoGeralOsTipoInterno' value=''>
				<input type='hidden' name='PermissaoFatura' value=''>
				<input type='hidden' name='Faturado' value=''>
				<input type='hidden' name='PermissaoCancelarOS' value='<?=$local_PermissaoCancelarOS?>'>
				<input type='hidden' name='UsuarioIDefault' value='<?=getCodigoInterno(3,109)?>'>
				<input type='hidden' name='UsuarioADefault' value='<?=getCodigoInterno(3,103)?>'>
				<input type='hidden' name='ResponsavelIDefault' value='<?=getCodigoInterno(3,120)?>'>
				<input type='hidden' name='ResponsavelADefault' value='<?=getCodigoInterno(3,119)?>'>
				<input type='hidden' name='CountArquivo' value='0'>
				<input type='hidden' name='ExtensaoAnexo' value='<?=implode(',', $extensao_anexo)?>'>
				<input type='hidden' name='MaxUploads' value='<?=ini_get('max_file_uploads')?>'>
				<input type='hidden' name='MaxSize' value='<?=ini_get('upload_max_filesize')?>'>
				<input type='hidden' name='IdPublicaDefault' value='<?=getCodigoInterno(3,129)?>'>
				<input type='hidden' name='EcluirAnexos' value=''>
				<input type='hidden' name='IdAgenteAutorizadoLogin' value='<?=$local_IdAgenteAutorizadoLogin?>'>
				<input type='hidden' name='MD5' value=''>
				<input type='hidden' name='IdCarteiraTemp' value=''>
				<input type='hidden' name='IdContratoTemp' value='<?=$local_IdContrato?>'>
				<input type='hidden' name='PermisaoConcluirOS' value='<?=permissaoSubOperacao($localModulo,$localOperacao,"T")?>'>
				<input type='hidden' name='UsuarioAtendimentoAntigo' value=''>
				<input type='hidden' name='EmAtendimentoStatus' value='2'>
				<input type='hidden' name='DescricaoOrdemServicoAtendimento' value='<?=getCodigoInterno(63,2)?>'>
				<input type='hidden' name='DescricaoOrdemServicoInterno' value='<?=getCodigoInterno(63,1)?>'>
				<input type='hidden' name='ObrigatoriedadeDataHora' value='<?=getCodigoInterno(11,17)?>'>
				<input type='hidden' name='ObrigatoriedadeDescricaoOrdemServico' value='<?=$ObrigatoriedadeDescricaoOrdemServico;?>'>
				<input type='hidden' name='PermissaoVisualizar' value='<?=permissaoSubOperacao(1,201,'V')?>'>
				<input type='hidden' name='PermissaoEditar' value='<?=permissaoSubOperacao(1,201,'U')?>'>
				<input type='hidden' name='PermissaoInserir' value='<?=permissaoSubOperacao(1,201,'I')?>'>
				<div>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo' style='width:70px;'><?=dicionario(427)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' style='width:153px;'><B><?=dicionario(428)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(429)?></B></td>
							<td class='descCampo'><B id='titTableMarcador' style='color:#000; display:none; padding-left:8px;'><?=dicionario(430)?></B></td>
							<td class='descCampo'><B id='titTempoAbertura' style='color:#000; display:none'><?=dicionario(431)?>.</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='IdOrdemServico' value='' autocomplete="off" style='width:70px' maxlength='11' onChange='busca_ordem_servico(this.value,true,document.formulario.Local.value);subTipoOrdemServicoDefault(this.value,<?=getCodigoInterno(3,84)?>);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='IdTipoOrdemServico' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width: 153px' onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" onChange="return atualiza_tipo_servico(this.value)" tabindex='2'>
									<option value=''></option>
									<?
										$sql = "select IdTipoOrdemServico, DescricaoTipoOrdemServico from TipoOrdemServico where IdLoja = $local_IdLoja order by DescricaoTipoOrdemServico";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdTipoOrdemServico]'>$lin[DescricaoTipoOrdemServico]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='IdSubTipoOrdemServico' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width: 153px' onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" onChange="atualizar_Sessao()" tabindex='3'>
									<option value=''></option>
								</select>
							</td>
							<td class='campo' id='cpMarcador' style='padding-left:8px; padding-right:8px;' valign='top'>
								<table id='tableMarcador' style='border:1px #A4A4A4 solid; display:none; width:90px;' cellpadding='0' cellspacing='0'>
									<tr>
										<td class='separador'>&nbsp;</td>
										<td><img id='mVermelho' style='background-color:<?=getParametroSistema(156,1);?>;' alt='<?=getParametroSistema(120,1)?>' title='<?=getParametroSistema(120,1)?>' onClick="addMarcador('1')" src='../../img/estrutura_sistema/marcador.gif'></td>
										<td class='separador'>&nbsp;</td>
										<td><img id='mAmarelo' style='background-color:<?=getParametroSistema(156,2);?>;' alt='<?=getParametroSistema(120,2)?>' title='<?=getParametroSistema(120,2)?>' onClick="addMarcador('2')" src='../../img/estrutura_sistema/marcador.gif'></td>
										<td class='separador'>&nbsp;</td>
										<td ><img id='mVerde' style='background-color:<?=getParametroSistema(156,3);?>;' alt='<?=getParametroSistema(120,3)?>' title='<?=getParametroSistema(120,3)?>' onClick="addMarcador('3')" src='../../img/estrutura_sistema/marcador.gif'></td>
										<td class='separador'>&nbsp;</td>
									</tr>
								</table>
							</td>
							<td class='campo' style='width:85px;' valign='top'>
								<input type='text' id='cp_TempoAbertura' name='TempoAbertura' value='' style='width:70px;height:18px; display:none' maxlength='11' readOnly>
							</td>
							<td class='campo' id='cpEmAndamento' style='padding-left:8px; padding-right:8px;'>
								<table id='tableEmAndamento' cellpadding='0' cellspacing='0' style='display:none'>
									<tr>
										<td>
											<img id='atendimento' alt='Aguardando Atendimento' title='Aguardando Atendimento' onClick="emAtendimento(document.formulario.EmAtendimentoStatus.value)" src='../../img/estrutura_sistema/atendimento_c.gif' value='2'>
										</td>
									</tr>
								</table>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='descricao' id='cpStatus' style='width: 260px' valign='top'><B id='cp_Status'>&nbsp;</B></td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosCliente'>
					<div id='cp_tit'><?=dicionario(177)?></div>
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
										<td class='descCampo'>".dicionario(179)."</td>
									</tr>
									<tr>
										<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\"></td>
										<td class='campo'>
											<input type='text' name='IdPessoa' value='$local_IdPessoa' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='4'><input type='text' class='agrupador' name='Nome' value='' style='width:279px' maxlength='100' readOnly>
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
										<td class='descCampo'><B style='margin-right:36px'>".dicionario(26)."</B><B style='color:#000'>".dicionario(172)."</B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>".dicionario(92)."</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>".dicionario(179)."</td>
									</tr>
									<tr>
										<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\"></td>
										<td class='campo'>
											<input type='text' name='IdPessoa' value='$local_IdPessoa' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='4'><input type='text' class='agrupador' name='RazaoSocial' value='' style='width:279px' maxlength='100' readOnly>
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
					<table id='cp_fisica' style='display:none;padding-bottom:6px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:36px'><?=dicionario(26)?></B><?=dicionario(85)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(432)?>.</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(104)?></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(210)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(89)?></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaPessoa', true, event, null, 165);"></td>
							<td class='campo'>
								<input type='text' name='IdPessoaF' value='<?=$local_IdPessoa?>' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_pessoa(this.value,'false',document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='4'><input type='text' class='agrupador' name='NomeF' value='' style='width:220px' maxlength='100' readOnly>
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
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaContrato', true, event, null, 285);"></td>
							<td class='campo'>
								<input type='text' name='IdContrato' value='' autocomplete="off" style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" onChange="busca_contrato(this.value,false,document.formulario.Local.value); visualiza_campo(document.formulario.IdStatusNovo.value)" tabindex='5'>
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
								<input type='text' name='QtdParcelaContrato' value=''  style='width:90px' readOnly>
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
										$sql = "select IdCodigoInterno, ValorCodigoInterno from CodigoInterno where IdLoja = $local_IdLoja and  IdGrupoCodigoInterno=9 order by ValorCodigoInterno";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdCodigoInterno]'>$lin[ValorCodigoInterno]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='TipoContrato' style='width:140px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" disabled>
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
							<td class='descCampo'><B style='margin-right:34px'><?=dicionario(30)?></b><?=dicionario(223)?></td>
							<td class='separador'>&nbsp;</td>						
							<td class='descCampo'><?=dicionario(367)?></td>
							<td class='separador'>&nbsp;</td>
							
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaServico', true, event, null, 350);"></td>
							<td class='campo'>
								<input type='text' name='IdServico' value=''  style='width:70px' maxlength='11' onChange='busca_servico(this.value,true,document.formulario.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='6'><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:327px' maxlength='100' readOnly>
							</td>							
							<td class='separador'>&nbsp;</td>
							<td class='campo' id='cpValor'>
								<input type='text' name='DescricaoServicoGrupo' value='' style='width:397px' maxlength='16' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onBlur="Foco(this,'out');"  readonly='readonly'>
							</td>
							<td class='separador'>&nbsp;</td>
							
						</tr>
					</table>
					<table>
						<tr>
							<td colspan="2">
								<table >
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><?=dicionario(436)?></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><B><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><?=dicionario(437)?> (<?=getParametroSistema(5,1)?>)</td>
										<td class='separador'>&nbsp;</td>	
										<td class='descCampo'><?=dicionario(398)?> (<?=getParametroSistema(5,1)?>)</td>
									</tr>
									<tr>
										
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<select name='IdTipoServico' style='width:193px' disabled>
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
											<input type='text' name='Valor' value='' style='width:192px' maxlength='16' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onBlur="Foco(this,'out'); calcula_total()"  tabindex='7'>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='ValorOutros' value='' style='width:192px' maxlength='16' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onBlur="Foco(this,'out'); calcula_total()"  tabindex='8'>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='ValorTotal' value='' style='width:192px' maxlength='16' readOnly>
										</td>
										
									</tr>
								</table>
							</td>
							
						</tr>
						<tr>
							<td>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><?=dicionario(438)?></td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<textarea name='DetalheServico' style='width: 400px; height: 88px;' readOnly></textarea>
										</td>
									</tr>
								</table>
							</td>
							<td>
								<table>
									<tr>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><B id='titJustificativa' style='color:#000;'><?=dicionario(439)?> (<?=dicionario(437)?>)</B></td>
									</tr>
									<tr>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<textarea name='Justificativa' style='width: 393px; height: 88px;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='9' readOnly></textarea>
										</td>
									</tr>
								</table>
								
							</td>
						</tr>
					</table>
				</div>
				<div id='cpDescricaoOrdemServico' style="display: none">
					<div id='cp_tit' style='margin-top:0'><?=dicionario(986)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo' id="DescricaoOrdemServico"><?=dicionario(440);?></td>
						</tr>
						<tr>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<textarea name='DescricaoOS' style='width: 816px; height: 52px;' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='10' <?=$local_readOnlyDesOrdServ;?>><?=getCodigoInterno(63,1)?></textarea>
							</td>
						</tr>
					</table>
					
				</div>
				<div id='cpAgenteAutorizado'>
					<div id='cp_tit' style='margin-top:0'><?=dicionario(32)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000' id='cpAgenteAutorizado'><?=dicionario(32)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000' id='cpCarteira'><?=dicionario(117)?></B></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaAgente', true, event, null, 425);"></td>
							<td class='campo'>
								<input type='text' name='IdAgenteAutorizado' value='' style='width:70px' maxlength='11' onChange='busca_agente(this.value,true,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='11'><input type='text' class='agrupador' name='NomeAgenteAutorizado' value='' style='width:370px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select type='text' name='IdCarteira' value='' style='width:360px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='12' disabled>
									<option value=''></option>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div id='cpDescricaoOSInterna' style='display:none'>
					<div id='cp_tit' style='margin-top:0'><?=dicionario(441)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(440)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='DescricaoOSInterna' style='width: 816px;' rows=5 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='13'></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_ordemServicoCliente' style='margin-bottom:0; display:none;'>
					<div id='cp_tit' style='margin-bottom:0'><?=dicionario(442)?></div>
					<table id='tabelaOrdemServicoCliente' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' style='width: 40px'><?=dicionario(141)?></td>
							<td><?=dicionario(113)?></td>
							<td><?=dicionario(125)?></td>
							<td><?=dicionario(443)?></td>
							<td><?=dicionario(444)?>.</td>
							<td><?=dicionario(445)?></td>
							<td><?=dicionario(431)?>.</td>
							<td><?=dicionario(140)?></td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='8' id='tabelaOrdemServicoClienteTotal'></td>
						</tr>
					</table>
				</div>
				<div id='cp_parametrosContrato' style='margin-bottom:0; display:none'>
					<div id='cp_tit'><?=dicionario(262)?></div>
					<table id='tabelaParametroContrato'>
					</table>
				</div>
				<div  id='cpEndereco'>
					<div class='cp_tit'><?=dicionario(446)?></div>
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
								<select name='IdPessoaEndereco' style='width:400px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_pessoa_endereco(document.formulario.IdPessoa.value,this.value); document.formulario.IdPessoaEnderecoTemp.value = this.value;" tabindex='14'>
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
							<td class='descCampo'>Nº</td>
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
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa_c.gif' alt='<?=dicionario(166)?>'></td>
							<td class='campo'>
								<input type='text' name='IdPais' value='' style='width:70px' maxlength='11' readOnly><input  class='agrupador' type='text' name='Pais' value='' style='width:140px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa_c.gif' alt='<?=dicionario(166)?>'></td>
							<td class='campo'>
								<input type='text' name='IdEstado' value='' style='width:70px' maxlength='11' readOnly><input class='agrupador' type='text' name='Estado' value='' style='width:140px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa_c.gif' alt='<?=dicionario(166)?>'></td>
							<td class='campo'>
								<input type='text' name='IdCidade' value='' style='width:70px' maxlength='11' readOnly><input class='agrupador' type='text' name='Cidade' value='' style='width:233px' maxlength='100' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_parametrosServico' style='margin-bottom:0; display:none'>
					<div id='cp_tit'><?=dicionario(447)?></div>
					<table id='tabelaParametro'>
					</table>
				</div>
				<div id='cp_automatico' style='display:none'>
				</div>
				<div id='cpHistoricoAtual' style='display:none'>
					<div id='cp_tit'><?=dicionario(448)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(448)?></td>
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
							<td class='descCampo'><?=dicionario(449)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(450)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(451)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(452)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdGrupoUsuarioAtendimentoAtual' style='width:220px' disabled>
									<option value='' selected></option>
									<?
										$sql = "select UsuarioGrupoUsuario.IdGrupoUsuario, GrupoUsuario.DescricaoGrupoUsuario from UsuarioGrupoUsuario, GrupoUsuario, Usuario, Pessoa where UsuarioGrupoUsuario.IdLoja = $local_IdLoja and UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and UsuarioGrupoUsuario.Login = Usuario.Login and UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and Usuario.IdPessoa = Pessoa.IdPessoa and Pessoa.TipoUsuario = 1 and Usuario.IdStatus = 1 GROUP by UsuarioGrupoUsuario.IdGrupoUsuario";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdGrupoUsuario]'>$lin[DescricaoGrupoUsuario]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='LoginAtendimentoAtual'style='width:337px' disabled>
									<option value='' selected></option>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataAtual' value='' autocomplete="off" style='width:110px' maxlength='10' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='HoraAtual' value='' autocomplete="off" style='width:110px' maxlength='5' readOnly>
							</td>
						</tr>
					</table>
					<table cellspacing='0' cellpadding='0'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td>
								<table cellspacing='0' cellpadding='0' valign='bottom'>
									<tr>
										<td class='descCampo' id='titLoginSupervisor' style='line-height:01%;padding-left:04px'><?=dicionario(861)?></B></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class='find' style=''>&nbsp;</td>
							<td  style='line-height:01%;' valign='top'>
								<table cellpadding='0' cellspacing='0'>
									<tr>
										<td class='campo' style='line-height:100%;padding-left:4px;'>
											<select name='LoginSupervisorAtual' style='width:220px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
												<option value=''></option>
												
											</select>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'><?=dicionario(453)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='titObs' style='color:#000'><?=dicionario(159)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='Obs' style='width: 816px;' rows=5 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='102'></textarea>
								<div style='text-align:rigth;'>Preenchimento obrigatório para envio de SMS.</div>
							</td>
						</tr>
					</table>
					<table cellspacing='0' cellpadding='0' valign='bottom'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td>
								<table cellspacing='0' cellpadding='0' valign='bottom'>
									<tr>
										<td class='descCampo' style='line-height:01%;padding-left:04px'><B id='titStatus'><?=dicionario(454)?></B></td>
									</tr>
								</table>
							</td>
							<td class='separador'>&nbsp;</td>
							<td id='titNovaObsCDA' style='display:none;width:700px;'>
								<table cellspacing='0' cellpadding='0' valign='bottom'>
									<tr>
										<td class='descCampo' style='line-height:70%;' id='titNovaObsCDAt'><?=dicionario(455)?> (<?=dicionario(8)?>)</td>
										<td class='separador' style='line-height:70%;'>&nbsp;</td>
										<td class='descCampo' style='line-height:70%;'><B id='titObsCDA'><?=dicionario(456)?> (<?=dicionario(8)?>)</B></td>
									</tr>
								</table>
							</td>
							<td id='titGrupoAtendimento' style='display:none; width:714px'>
								<table cellspacing='0' cellpadding='0' valign='bottom'>
									<tr>
										<td class='descCampo' id='titGrupoAtendimentot' style='line-height:70%;'><B><?=dicionario(457)?></B></td>
										<td class='separador' style='line-height:70%;'>&nbsp;</td>
										<td class='descCampo' style='line-height:70%;'><B id='titUsuarioAtendimento' style='display:none;color:#000'><?=dicionario(458)?></B></td>
										<td class='separador' style='line-height:70%;'>&nbsp;</td>
										<td class='descCampo' style='line-height:70%;'><B id='titDataAgendamento' style='display:none;color:#000'>
										<?
											if(getCodigoInterno(11,17) == 1){
												echo "<B>".dicionario(451)."</B>";
											}else{
												echo dicionario(451);
											}
										?>
										</td>
										<td class='find' style='line-height:70%;'>&nbsp;</td>
										<td class='separador' style='line-height:70%;'>&nbsp;</td>
										<td class='descCampo' style='line-height:70%;'><B id='titHoraAgendamento' style='display:none;color:#000'>
										<?
											if(getCodigoInterno(11,17) == 1){
												echo "<B>".dicionario(977)."</B>";
											}else{
												echo dicionario(977);
											}	
										?>.</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td  style='line-height:01%;' valign='top'>
								<table cellpadding='0' cellspacing='0'>
									<tr>
										<td class='campo' style='line-height:100%;padding-left:4px;'>
											<select name='IdStatusNovo' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="visualiza_campo(this.value);limpa_campo(this.value)" tabindex='103' >
												<option value='' selected></option>
											</select>
										</td>
									</tr>
								</table>
							</td>
							<td class='separador'>&nbsp;</td>
							<td id='cpNovaObsCDA' style='display:none' valign='top'>
								<table cellpadding='0' cellspacing='0'>
									<tr>
										<td class='campo' valign	='top'>
											<select name='NovaDescricaoOsCDA' style='width:150px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='104' onchange="verificaNovaObsCDA(this.selectedIndex)" >
												<?
													$sql = "select 
																IdParametroSistema,
																ValorParametroSistema 
															from
																ParametroSistema 
															where
																IdGrupoParametroSistema = 246
															order by ValorParametroSistema";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
													}
												?>
											</select>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo' valign='top'>
											<textarea rows='2' id='cpObsCDA' name='DescricaoCDA' value='' style='width:552px; outline:medium none; outline-offset:0px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='105'></textarea>
										</td>
									</tr>
								</table>
							</td>
							<td id='cpGrupoAtendimento' style='display:none'>
								<table cellpadding='0' cellspacing='0'>
									<tr>
										<td class='campo' style='line-height:100%;'>
											<select id='cpGrupoAtendimentot'name='IdGrupoUsuarioAtendimento' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:180px;' tabindex='104' onChange="busca_login_usuario(this.value,document.formulario.LoginAtendimento);busca_login_supervisor(this.value,document.formulario.LoginSupervisor);">
												<option value='' selected></option>
												<?
													$sql = "select 
																UsuarioGrupoUsuario.IdGrupoUsuario, 
																GrupoUsuario.DescricaoGrupoUsuario 
															from 
																UsuarioGrupoUsuario, 
																GrupoUsuario, 
																Usuario, 
																Pessoa 
															where 
																UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
																UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
																UsuarioGrupoUsuario.Login = Usuario.Login and 
																UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
																Usuario.IdPessoa = Pessoa.IdPessoa and 
																Pessoa.TipoUsuario = 1 and 
																Usuario.IdStatus = 1 and
																GrupoUsuario.OrdemServico = 1
															GROUP by 
																UsuarioGrupoUsuario.IdGrupoUsuario";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
														echo"<option value='$lin[IdGrupoUsuario]'>$lin[DescricaoGrupoUsuario]</option>";
													}
												?>
											</select>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<select  id='cpUsuarioAtendimento' name='LoginAtendimento' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:298px; display:none' tabindex='105' onChange='novo_supervisor()'>
												<option value='' selected></option>
											</select>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo' style='width:114px;'>
											<input type='text' name='Data' id='cpData' value='' autocomplete="off" style='width:110px; display:none' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onChange="validar_Data('titDataAgendamento',this);" tabindex='106'>
										</td>
										<td class='find'>
											<img id='cpDataIco' style='display:none; padding-left:4px' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'>
										</td>
										<script type="text/javascript">
											Calendar.setup({
												inputField     : "cpData",
												ifFormat       : "%d/%m/%Y",
												button         : "cpDataIco"
											});
										</script>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' id='cpHora' name='Hora' value='' autocomplete="off" style='width:74px; display:none' maxlength='5' onChange="validar_Time('titHoraAgendamento',this);" onkeypress="mascara(this,event,'hora')" onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" tabindex='107'>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table cellspacing='0' cellpadding='0' valign='bottom' id='tblSupervisor' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td>
								<table cellspacing='0' cellpadding='0' valign='bottom'>
									<tr>
										<td class='descCampo' id='titLoginSupervisor' style='line-height:01%;padding-left:04px'><?=dicionario(861)?></B></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class='find' style=''>&nbsp;</td>
							<td  style='line-height:01%;' valign='top'>
								<table cellpadding='0' cellspacing='0'>
									<tr>
										<td class='campo' style='line-height:100%;padding-left:4px;'>
											<select name='LoginSupervisor' style='width:287px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)' tabindex='108'>
												<option value=''></option>
												
											</select>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
				<div id='cpFaturamento' style='display:none'>
					<div id='cp_tit'><?=dicionario(459)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(206)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(460)?></B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorFinal' value='' style='width:163px' maxlength='16' onFocus="Foco(this,'in')" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onBlur="Foco(this,'out');"  tabindex='109'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='FormaCobranca' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='110' onChange="document.formulario.FormaCobrancaTemp.value = document.formulario.FormaCobranca.value;">
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=50 and IdParametroSistema != 4 order by ValorParametroSistema";
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
					<div id='cp_tit'><?=dicionario(122)?></div>
					<table id='tituloTabelaArquivo'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(123)?>&nbsp;[<a style='cursor:pointer;' onClick='addArquivo()'>+</a>]</td>
							<td class='descCampo'>&nbsp;</td>
						</tr>
					</table>
					<table id='tabelaArquivos' style='width:850px;'></table>
					<div id='cpArquivosAnexados' style='margin-top:10px; display:none;'>
						<table class='tableListarCad' id='tabelaArquivosAnexados' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' style='width: 18px'>&nbsp;</td>
								<td><?=dicionario(124)?></td>
								<td><?=dicionario(125)?></td>
								<td><?=dicionario(126)?></td>
								<td><?=dicionario(127)?></td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='5' id='tabelaValorTotal'><?=dicionario(128)?>: 0</td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>
				</div>
				<div id='cp_log'>
					<div id='cp_tit'>Log</div>
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
							<td class='descCampo'><?=dicionario(771)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(478)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(461)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(462)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginFaturamento' value='' style='width:180px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataFaturamento' value='' style='width:202px' readOnly>
							</td>							
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginConclusao' value='' style='width:180px' maxlength='20'  readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataConclusao' value='' style='width:202px' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width:100%'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:left'>
								<input type='button' name='bt_chegar' value='<?=dicionario(266)?>?' class='botao' tabindex='1100' onClick='como_chegar()'>
							</td>
							<td class='campo' style='text-align:right; width:140px'>
								<input type='button' name='bt_imprimir' style='width:100px' value='<?=dicionario(463)?>' class='botao' tabindex='1110' onClick="cadastrar('imprimir')">
							</td>
							<td class='campo' style='text-align:right; width:140px'>
								<input type='button' name='bt_fatura' style='width:120px' value='<?=dicionario(464)?>' class='botao' tabindex='1120' onClick="faturamento()">
							</td>
							<td class='campo' style='text-align:right; padding-right:6px;  width:250px;'>
								<input type='button' name='bt_inserir' style='width:80px' value='<?=dicionario(146)?>' class='botao' tabindex='1130' onClick="cadastrar()">
								<input type='button' name='bt_alterar' style='width:60px' value='<?=dicionario(15)?>' class='botao' tabindex='1140' onClick="cadastrar()">
								<input type='button' name='bt_excluir' style='width:60px' value='<?=dicionario(147)?>' class='botao' tabindex='1150' onClick="excluir(document.formulario.IdOrdemServico.value,document.formulario.IdStatus.value)">
							</td>
						</tr>
					</table>
				</div>	
				<div>		
					<table border=0> 
						<tr>
							<td class='find'>&nbsp;</td>
							<td>
								<h1 id='helpText' name='helpText' style='margin-bottom:0'>&nbsp;</h1>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_historico_os' style='display:none'>
					<div id='cp_tit' style='margin-bottom:0'><?=dicionario(465)?></div>
					<table id='tabelaHistorico' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'>&nbsp;</td>
							<td><?=dicionario(466)?></td>
							<td><?=dicionario(467)?></td>
							<td><?=dicionario(468)?></td>
							<td><?=dicionario(469)?></td>
							<td><?=dicionario(470)?></td>
							<td><?=dicionario(451)?></td>
							<td><?=dicionario(452)?></td>
							<td><?=dicionario(471)?></td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='9' id='tabelaTotal'>&nbsp;</td>
						</tr>
					</table>
				</div>
			</form>
		</div>
		<div id='quadros_fluantes'>
			<?
				include("files/busca/pessoa.php");
				include("files/busca/contrato2.php");
				include("files/busca/servico_ordem_servico.php");
				include("files/busca/agente.php");
			?>
		</div>
	</body>	
</html>
<script>
	<?
		if($local_IdOrdemServico != ""){
			echo "busca_ordem_servico($local_IdOrdemServico,false);";
			echo "scrollWindow('bottom');";
		}else{
			if($local_IdPessoa != ""){
				echo "busca_pessoa($local_IdPessoa,false);";
			}
			if($local_IdContrato != ""){
				echo "busca_contrato($local_IdContrato,false);";
			}
			
			echo "busca_status_novo(40,document.formulario.IdStatusNovo,'','');";
			echo"verificaAcao();";
		}
	?>
	function atualizar_Sessao(){
		if(document.formulario.IdSubTipoOrdemServicoTemp.value != ""){
			document.formulario.IdSubTipoOrdemServicoTemp.value = document.formulario.IdSubTipoOrdemServico.value;
		}
	}
	function status_inicial(){
		if('<?=getCodigoInterno(13,3)?>' == '2'){
			document.getElementById('cpAgenteAutorizado').style.display	=	'block';	
		}else{
			document.getElementById('cpAgenteAutorizado').style.display	=	'none';	
		}
		
		if(document.formulario.IdTipoOrdemServico.value == ""){
			document.formulario.IdTipoOrdemServico.value = document.formulario.IdTipoOrdemServicoDefault.value;
			
			atualiza_tipo_servico(document.formulario.IdTipoOrdemServico.value, document.formulario.IdTipoOrdemServicoDefault.value);
		}
		if(document.formulario.IdSubTipoOrdemServico.value == ""){
			document.formulario.IdSubTipoOrdemServico.value = document.formulario.IdSubTipoOrdemServicoDefault.value;
			
			busca_subtipo_ordem_servico(document.formulario.IdTipoOrdemServico.value, document.formulario.IdSubTipoOrdemServicoDefault.value);
		}
		if(document.formulario.Valor.value == '' || document.formulario.Valor.value == 0){
			document.formulario.Valor.value = '0,00';
		}
		if(document.formulario.ValorOutros.value == '' || document.formulario.ValorOutros.value == 0){
			document.formulario.ValorOutros.value = '0,00';
		}
		
		while(document.getElementById('tabelaArquivos').rows.length > 0){
			document.getElementById('tabelaArquivos').deleteRow(0);
		}
		
		if(document.formulario.IdOrdemServico.value == ""){
			document.formulario.DescricaoOS.value 		 = document.formulario.DescricaoOrdemServicoAtendimento.value;
			document.formulario.DescricaoOSInterna.value = document.formulario.DescricaoOrdemServicoInterno.value;
		}
		
		document.formulario.CountArquivo.value = 0;
		addArquivo();
	}
	function inicia(){
		status_inicial();
		calcula_total();
		document.formulario.IdOrdemServico.focus();
	}
	verificaErro();
	inicia();
	enterAsTab(document.forms.formulario);
	permissaoQuadroDescricaoOs();
	subTipoOrdemServicoDefault(<?=getCodigoInterno(3,84)?>,'<?=$local_IdOrdemServico?>');
</script>