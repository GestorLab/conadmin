<?
	$localModulo		=	1;
	$localOperacao		=	162;
	$localSuboperacao	=	"V";
	$Path				= "../../";

	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	include('../../classes/envia_mensagem/envia_mensagem.php');
	
	$local_Login						= $_SESSION["Login"];
	$local_IdLoja						= $_SESSION["IdLoja"];
	$local_Acao 						= $_POST['Acao'];
	$local_Erro							= $_GET['Erro'];
	$local_IdProtocolo					= $_POST['IdProtocolo'];
	$local_IdProtocoloTipo				= $_POST['IdProtocoloTipo'];
	$local_IdLocalAbertura				= $_POST['IdLocalAbertura'];
	$local_Concluir						= $_POST['Concluir'];
	$local_IdPessoaF					= $_POST['IdPessoaF'];
	$local_IdPessoa						= $_POST['IdPessoa'];
	$local_IdTipoPessoa					= $_POST['IdTipoPessoa'];
	$local_Nome							= formatText($_POST['Nome'],NULL);
	$local_NomeF						= formatText($_POST['NomeF'],NULL);
	$local_CPF							= formatText($_POST['CPF'],NULL);
	$local_CNPJ							= formatText($_POST['CNPJ'],NULL);
	$local_Telefone1					= formatText($_POST['Telefone1'],NULL);
	$local_Telefone2					= formatText($_POST['Telefone2'],NULL);
	$local_Telefone3					= formatText($_POST['Telefone3'],NULL);
	$local_Celular						= formatText($_POST['Celular'],NULL);
	$local_Email						= formatText($_POST['Email'],NULL);
	$local_EmailJuridica				= formatText($_POST['EmailJuridica'],NULL);
	$local_IdContrato					= $_POST['IdContrato'];
	$local_IdContaEventual				= $_POST['IdContaEventual'];
	$local_IdOrdemServico				= $_POST['IdOrdemServico'];
	$local_IdContaReceber				= $_POST['IdContaReceber'];
	$local_Assunto						= formatText($_POST['Assunto'],NULL);
	$local_Mensagem						= formatText($_POST['Mensagem'],NULL);
	$local_IdGrupoUsuarioAtendimento	= $_POST['IdGrupoUsuarioAtendimento'];
	$local_LoginAtendimento				= formatText($_POST['LoginAtendimento'],NULL);
	$local_Data							= $_POST['Data'];
	$local_Hora							= $_POST['Hora'];
	
	if($local_IdLocalAbertura == ''){
		$local_IdLocalAbertura = 2;
	}
	
	if($local_IdProtocolo == ''){
		$local_IdProtocolo = $_GET['IdProtocolo'];
	}
	
	if($local_IdProtocoloTipo == ''){
		$local_IdProtocoloTipo = $_GET['IdProtocoloTipo'];
	}
	
	switch($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_protocolo.php');
			break;
		case 'alterar':
			include('files/editar/editar_protocolo.php');
			break;
		case 'concluir':
			include('files/editar/editar_protocolo.php');
			break;
		default:
			$local_Acao = 'inserir';
	}
	
	include("../../files/header.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		<link rel='stylesheet' type='text/css' href='../../css/procurar.css' />
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/mascara_real.js'></script>
		<script type='text/javascript' src='../../js/val_cpf.js'></script>
		<script type='text/javascript' src='../../js/val_cnpj.js'></script>
		<script type='text/javascript' src='../../js/val_email.js'></script>
		<script type='text/javascript' src='../../js/val_data.js'></script>
		<script type='text/javascript' src='../../js/val_time.js'></script>
		<script type='text/javascript' src='../../js/prototype.js'></script>
		<script type='text/javascript' src='../../js/scriptaculous.js'></script> 
		<script type='text/javascript' src='../../js/event.js'></script> 
		<script type='text/javascript' src='js/protocolo.js'></script>
		<script type='text/javascript' src='js/protocolo_default.js'></script>
		<script type='text/javascript' src='js/pessoa_default.js'></script>
		<script type='text/javascript' src='js/contrato_default.js'></script>
		<script type='text/javascript' src='js/conta_eventual_default.js'></script>
		<script type='text/javascript' src='js/conta_receber_default.js'></script>
		<script type='text/javascript' src='js/ordem_servico_default.js'></script>
		
		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>
	    <link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />
	</head>
	<body onLoad="ativaNome('Protocolo')">
		<? include('filtro_protocolo.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_protocolo.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
				<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
				<input type='hidden' name='Local' value='Protocolo' />
				<input type='hidden' name='IdTipoPessoa' value='' />
				<input type='hidden' name='PessoaTodosDados' value='1' />
				<input type='hidden' name='Concluir' value='0' />
				<input type='hidden' name='IdLocalAbertura' value='<?=$local_IdLocalAbertura?>' />
				<input type='hidden' name='CPF_CNPJ_Obrigatorio' value='<?=getCodigoInterno(11,2)?>' />
				<input type='hidden' name='CPF_CNPJ_Duplicado' value='<?=getCodigoInterno(11,4)?>' />
				<input type="hidden" name="DataAtualTemp" value='<?=date("YmdHi")?>'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Protocolo</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' ><B>Tipo Protocolo</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Local de Abertura</td>
							<td class='find'></td>
							<td class='find'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdProtocolo' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_protocolo(this.value,true,document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" tabindex='1' />
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<select name='IdProtocoloTipo' style='width:150px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='2'></select>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='LocalAbertura' value='' style='width:101px' maxlength='255' readonly='readonly' />
							</td>
							<td class='find'>&nbsp;</td>
							<td class='descricao' style='width:444px; text-align:right;'><B id='cpStatusProtocolo'>&nbsp;</B></td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosCliente'>
					<div id='cp_tit'>Dados da Pessoa</div>
					<?	
						$nome="							
							<div id='cp_juridica'>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo' style='margin-right:36px'>Pessoa<B style='color:#000;margin-left:36px;'>Nome Pessoa</B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo' id='cp_RazaoSocial_Titulo'>Razão Social</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><span id='cp_CNPJ'>CNPJ</span></td>
									</tr>
									<tr>
										<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\" /></td>
										<td class='campo'>
											<input type='text' name='IdPessoa' value='$local_IdPessoa' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='3' /><input type='text' class='agrupador' name='Nome' value='' style='width:279px' maxlength='100' readonly='readonly' />
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo' id='cp_RazaoSocial'>
											<input type='text'  name='RazaoSocial' value='' style='width:279px' maxlength='100' readonly='readonly' />
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='CNPJ' value='' style='width:149px' onchange='verificar_CPF_CNPJ(this,1);' onkeypress=\"mascara(this,event,'cnpj')\" maxlength='18' readonly='readonly' />
										</td>
									</tr>
								</table>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'>Nome Representate</td>	
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>Inscrição Estadual</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>E-mail</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='NomeRepresentante' value='' style='width:355px' maxlength='100' readonly='readonly' />
										</td>									
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='InscricaoEstadual' value='' style='width:150px' maxlength='20' readonly='readonly' />
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='EmailJuridica' value='' style='width:257px' maxlength='255' readonly='readonly' />
										</td>
										<td class='find' onClick='JsMail(document.formulario.EmailJuridica.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail' /></td>
									</tr>
								</table>
							</div>
							";
							
						$razao="
							<div id='cp_juridica'>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'>Pessoa<B style='color:#000;margin-left:36px;'>Razão Social</B></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><span id='cp_NomeFantasia'>Nome Fantasia</span></td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'><span id='cp_CNPJ'>CNPJ</span></td>
									</tr>
									<tr>
										<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\" /></td>
										<td class='campo'>
											<input type='text' name='IdPessoa' value='$local_IdPessoa' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='3' /><input type='text' class='agrupador' name='RazaoSocial' value='' style='width:279px' maxlength='100' readonly='readonly' />
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='Nome' value='' style='width:279px' maxlength='100' readonly='readonly' />
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='CNPJ' value='' style='width:149px' onchange='verificar_CPF_CNPJ(this,1);' onkeypress=\"mascara(this,event,'cnpj')\" maxlength='18' readonly='readonly' />
										</td>
									</tr>
								</table>
								<table>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'>Nome Representate</td>	
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>Inscrição Estadual</td>
										<td class='separador'>&nbsp;</td>
										<td class='descCampo'>Email</td>
									</tr>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='NomeRepresentante' value='' style='width:355px' maxlength='100' readonly='readonly' />
										</td>									
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='InscricaoEstadual' value='' style='width:150px' maxlength='20' readonly='readonly' />
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='text' name='EmailJuridica' value='' style='width:257px' maxlength='255' readonly='readonly' />
										</td>
										<td class='find' onClick='JsMail(document.formulario.EmailJuridica.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail' /></td>
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
							<td class='descCampo'>Pessoa<span id='cp_NomePessoa' style='margin-left:36px'>Nome Pessoa</span></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Nasc.</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>E-mail</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><span id='cp_CPF'>CPF</span></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>RG</td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaPessoa', true, event, null, 165);" /></td>
							<td class='campo'>
								<input type='text' name='IdPessoaF' value='<?=$local_IdPessoa?>' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_pessoa(this.value,'false',document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='3' /><input type='text' class='agrupador' name='NomeF' value='' style='width:220px' maxlength='100' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataNascimento' value='' style='width:70px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Email' value='' style='width:180px' readonly='readonly' />
							</td>
							<td class='find' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail' /></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='CPF' value='' style='width:100px' onchange='verificar_CPF_CNPJ(this,2);' onkeypress="mascara(this,event,'cpf')" maxlength='14' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='RG' value='' style='width:81px' readonly='readonly' />
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Fone Residencial</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Fone Comercial (1)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Fone Comercial (2)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Celular</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Fax</td>	
							<td class='separador'>&nbsp;</td>				
							<td class='descCampo'>Complemento Fone</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone1' value='' style='width:122px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone2' value='' style='width:122px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Telefone3' value='' style='width:122px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Celular' value='' style='width:122px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Fax' value='' style='width:122px' readonly='readonly' />
							</td>	
							<td class='separador'>&nbsp;</td>						
							<td class='campo'>
								<input type='text' name='ComplementoTelefone' value='' style='width:121px' readonly='readonly' />
							</td>			
						</tr>
					</table>
				</div>
				<div id='cp_dadosContrato'>
					<div id='cp_tit'>Dados do Contrato</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Contrato</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:36px'>Serviço</B>Nome Serviço</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Periodicidade</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>QTD. Parcelas</td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaContrato', true, event, null, 285);" /></td>
							<td class='campo'>
								<input type='text' name='IdContrato' value='' autocomplete="off" style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" onChange="busca_contrato(this.value,false,document.formulario.Local.value);" tabindex='11' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdServicoContrato' value='' autocomplete="off" style='width:70px' maxlength='11' readonly='readonly' /><input type='text' class='agrupador' name='DescricaoServicoContrato' value='' style='width:380px' maxlength='100' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescPeriodicidadeContrato' value=''  style='width:150px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdParcelaContrato' value=''  style='width:90px' readonly='readonly' />
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Data Início</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Término</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Base</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Última Cob.</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Contrato Ass.</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Tipo Contrato</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataInicio' value='' style='width:120px' maxlength='10' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataTermino' value='' style='width:120px' maxlength='10' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataBaseCalculo' value='' style='width:120px' maxlength='10' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataUltimaCobranca' value='' style='width:120px' maxlength='10' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='AssinaturaContrato' style='width:123px' disabled='disabled'>
									<option value=''>&nbsp;</option>
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
								<select name='TipoContrato' style='width:140px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" disabled='disabled'>
									<option value=''>&nbsp;</option>
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
				<div id='cp_dadosContaEventual'>
					<div id='cp_tit'>Dados do Conta Eventual</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Conta Eventual</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Descrição</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Forma de Cobrança</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>QTD. Parcelas</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaContaEventual', true, event, null, 285);" /></td>
							<td class='campo'>
								<input type='text' name='IdContaEventual' value='' autocomplete="off" style='width:80px' maxlength='11' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" onChange="busca_conta_eventual(this.value,false,document.formulario.Local.value);" tabindex='12' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescricaoContaEventual' value='' style='width:371px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='FormaCobrancaContaEventual' style='width:123px' disabled='disabled'>
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
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdParcelaContaEventual' value='' style='width:90px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='ValorContaEventual' value='' style='width:90px' readonly='readonly' />
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Dados da Ordem de Serviço</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>OS</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' id='tit_ContaReceberPessoa'>Nome Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Tipo Ordem de Serviço</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Status</td>
						</tr>
						<tr>
							
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaOrdemServico', true, event, null, 165);" /></td>
							<td class='campo'>
								<input type='text' name='IdOrdemServico' value='' autocomplete="off" style='width:70px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="busca_ordem_servico(this.value);" onkeypress="mascara(this,event,'int');" tabindex='13' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='NomePessoaOrdemServico' value='' style='width:373px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='IdTipoOrdemServico' style='width:153px' disabled='disabled'>
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
								<select name='IdStatusOrdemServico' style='width:181px' disabled='disabled'>
									<option value=''>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo "<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Nome Serviço</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' id='tit_ContaReceberPessoa'>Descrição Ordem de Serviço</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Valor (<?=getParametroSistema(5,1)?>)</td>
						</tr>
						<tr>
							
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NomeServicoOrdemServico' value='' style='width:320px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='DescricaoOrdemServico' value='' style='width:372px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='ValorOdemServico' value='' style='width:90px' readonly='readonly' />
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Dados do Contas a Receber</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Conta Receber</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' id='tit_ContaReceberPessoa'>Nome Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Status</td>
						</tr>
						<tr>
							
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaContaReceber', true, event, null, 165);" /></td>
							<td class='campo'>
								<input type='text' name='IdContaReceber' value='' autocomplete="off" style='width:80px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="busca_conta_receber(this.value,false,document.formulario.Local.value);" onkeypress="mascara(this,event,'int');" tabindex='14' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='NomePessoaContaReceber' value='' style='width:527px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='IdStatusContaReceber' style='width:181px' disabled='disabled'>
									<option value=''>&nbsp;</option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=35 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo "<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Dados do Protocolo</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='descCampo'><B>Assunto</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Assunto' value='' style='width:816px' maxlength='255' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='15' />
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Mensagem</B></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='Mensagem' style='width:816px;' rows='9' tabindex='16' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');"></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'>Equipe Responsável</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Grupo Atendimento</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Usuário Atendimento</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdGrupoUsuarioAtendimento' style='width:406px' tabindex='17' onChange="busca_login_usuario(this.value,document.formulario.LoginAtendimento);" onFocus="Foco(this,'in')" onBlur="Foco(this,'out');">
									<option value='' selected>&nbsp;</option>
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
													Usuario.IdStatus = 1 
												GROUP by 
													UsuarioGrupoUsuario.IdGrupoUsuario;";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdGrupoUsuario]'>$lin[DescricaoGrupoUsuario]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='LoginAtendimento' style='width:405px' tabindex='18' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');">
									<option value='' selected>&nbsp;</option>
								</select>
							</td>
						</tr>
					</table>
				</div>
				
				
				
				
				<div>
					<div id='cp_tit'>Previsão da Etapa</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id="titData" style="color:#000000;">Data</B></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id="titHora" style="color:#000000;">Hora</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' id='cpData' name='Data' value='' autocomplete="off" style='width:110px;' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in',true)" onBlur="Foco(this,'out')" onChange="validar_Data('titData', this);" tabindex='19'>
								<input type='hidden' name='DataTemp' value=''>
							</td>
							<td class='find'><img id='cpDataIco' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpData",
							        inputFieldAux  : "cpData2",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataIco"
							    });
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' id='cpHora' name='Hora' value='' autocomplete="off" style='width:75px;' maxlength='5' onkeypress="mascara(this,event,'hora')" onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onChange="validar_Time('titHora', this);" tabindex='20'>
								<input type='hidden' name='HoraTemp' value=''>
							</td>
						</tr>
					</table>
				</div>
				<div>
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
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:202px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginAlteracao' value='' style='width:180px' maxlength='20' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataAlteracao' value='' style='width:203px' readonly='readonly' />
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Usuário Conclusão</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Conclusão</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginConclusao' value='' style='width:180px' maxlength='20' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataConclusao' value='' style='width:202px' readonly='readonly' />
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width:100%;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='text-align:left;'>
								<input type='button' style='width:141px' name='bt_visualizar_historico' value='Visualizar Histórico' class='botao' tabindex='21' onClick="protocolo_visualizar_historico();" />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' name='bt_imprimir' style='width:131px' value='Imprimir Histórico' class='botao' tabindex='22' onClick="cadastrar('imprimir')" />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' style='width:65px' name='bt_concluir' value='Concluir' class='botao' tabindex='23' onClick="cadastrar('concluir');" />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' style='text-align:right; padding-right:5px; width:250px;'>
								<input type='button' style='width:80px' name='bt_inserir' value='Cadastrar' class='botao' tabindex='24' onClick="cadastrar('inserir')" />
								<input type='button' style='width:60px' name='bt_alterar' value='Alterar' class='botao' tabindex='25' onClick="cadastrar('alterar')" />
							</td>
						</tr>
					</table>
				</div>
				<div>	
					<table style='width:100%;height:33px;' border='0'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td id="erroEmail" style="color:#CB0000;"><B><?=str_replace('$emailDesativado',$local_TipoEmail,$local_ErroEmail)?></B></td>
						</tr>
					</table>
				</div>
				<div id='cp_protocolo_historico' style='display:none; margin-top:0;'>
					<div id='cp_tit' style='margin-bottom:0; margin-top:0'>Histórico</div>
					<table id='tabelaProtocoloHistorico' class='tableListarCad' cellspacing='0'>
						<tr><td height='14px'></td></tr>
					</table>
				</div>
			</form>
		</div>
		<div id='quadros_fluantes'>
			<?
				include("files/busca/pessoa.php");
				include("files/busca/contrato2.php");
				include("files/busca/conta_eventual.php");
				include("files/busca/conta_receber.php");
				include("files/busca/ordem_servico.php");
			?>
		</div>
		<script type="text/javascript">
			<?
				if($local_IdProtocolo != ""){
					echo "busca_protocolo($local_IdProtocolo,false,document.formulario.Local.value);";
				} else{
					if($local_IdProtocoloTipo != ""){
						echo "document.formulario.IdProtocoloTipo.value = '".$local_IdProtocoloTipo."';";
					}
				}
				
				if((int) getCodigoInterno(59,1) == 2 && $local_IdProtocolo == ''){
					echo "window.onload = protocolo_codigo;";
				}
			?>
			
			function status_inicial(){
				if(document.formulario.LocalAbertura.value == ''){
					document.formulario.LocalAbertura.value = '<?=getParametroSistema(205,$local_IdLocalAbertura)?>';
				}
				
				buscar_protocolo_tipo();
			}
			
			verificaErro();
			verificaAcao();
			inicia();
			enterAsTab(document.forms.formulario);
		</script>
	</body>	
</html>