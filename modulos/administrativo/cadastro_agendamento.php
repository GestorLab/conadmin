<?
	$localModulo		=	1;
	$localOperacao		=	28;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdServico					= $_POST['IdServico'];
	$local_IdOrdemServico				= $_POST['IdOrdemServico'];
	$local_IdContrato					= $_POST['IdContrato'];
	$local_IdPessoa						= $_POST['IdPessoa'];
	$local_Data							= formatText($_POST['Data'],NULL);
	$local_Hora							= formatText($_POST['Hora'],NULL);
	$local_LoginResponsavel				= trim($_POST['LoginResponsavel']);
	
	if($_GET['Data']!='' && $_GET['Hora']!=''){
		$local_Data		= $_GET['Data'];	
		$local_Hora		= $_GET['Hora'];	
	}
	if($_GET['IdOrdemServico']!=''){
		$local_IdOrdemServico	= $_GET['IdOrdemServico'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_agendamento.php');
			break;	
		default:
			$local_Acao 	 		= 'inserir';
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
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/val_time.js'></script>
		<script type = 'text/javascript' src = 'js/agendamento.js'></script>
		<script type = 'text/javascript' src = 'js/agendamento_default.js'></script>
		<script type = 'text/javascript' src = 'js/ordem_servico_default.js'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_default.js'></script>
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
	<body  onLoad="ativaNome('Agendamento')">
		<? include('filtro_agendamento.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_agendamento.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='Agendamento'>
				<input type='hidden' name='LoginResponsavel' value=''>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Ordem de Serviço</B></td>
						</tr>
						<tr>
							<td class='find' onClick="janela_ordem_servico();"><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar'></td>
							<td class='campo'>
								<input type='text' name='IdOrdemServico' value='' autocomplete="off" style='width:70px' maxlength='11' onChange='busca_ordem_servico(this.value,false,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='cpDataAgendamento'>Data Agendamento</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cpHoraAgendamento'>Hora Agendamento</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Responsável</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Data' value='' autocomplete="off" style='width:120px' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onChange="validar_Data('cpDataAgendamento',this); busca_agendamento(document.formulario.IdOrdemServico.value,this.value,document.formulario.Hora.value,false,document.formulario.Local.value)" tabindex='2'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Hora' value='' autocomplete="off" style='width:120px' maxlength='5' onChange="validar_Time('cpHoraAgendamento',this); busca_agendamento(document.formulario.IdOrdemServico.value,document.formulario.Data.value,this.value,false,document.formulario.Local.value)" onkeypress="mascara(this,event,'hora')" onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" tabindex='3'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Login' value='' style='width:170px' maxlength='20' readOnly><input type='text' class='agrupador' name='NomeUsuario' value='' style='width:367px' maxlength='30' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div id='cp_dadosCliente'>
					<div id='cp_tit'>Dados da Pessoa</div>
					<table id='cp_juridica'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:36px; color:#000'>Pessoa</B>Nome Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' id='cp_RazaoSocial_Titulo'>Razão Social</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Fone</td>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdPessoa' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='Nome' value='<?=$local_Nome?>' style='width:306px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' id='cp_RazaoSocial'>
								<input type='text'  name='RazaoSocial' value='' style='width:300px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' id='cp_Fone' name='Telefone1' value='' style='width:101px' maxlength='18' readOnly>
							</td>
						</tr>
					</table>
					<table id='cp_fisica' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:36px; color:#000'>Pessoa</B>Nome Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Sexo</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Estado Civil</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Fone</td>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdPessoaF' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='NomeF' value='' style='width:306px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='Sexo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width: 90px' disabled>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=8 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='EstadoCivil'  style='width:150px' disabled>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=9 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' id='cp_Fone' name='Telefone1F' value='' style='width:156px' maxlength='18' readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo' id='cp_CPF_CNPJ_Titulo'>CNPJ</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:165px'>Nome Cidade</B>UF</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Email</td>
							<td class='find'>&nbsp;</td>
						<tr>
							<td class='find'></td>
							<td class='campo'>
								<input type='text' id='cp_CPF_CNPJ_Input' name='CPF_CNPJ' value='' style='width:170px' maxlength='18' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
								<td class='campo'>
								<input type='text' name='Cidade' value='' autocomplete="off" style='width:233px' maxlength='100' readOnly><input type='text' class='agrupador' name='SiglaEstado' value='' style='width:70px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Email' value='' style='width:283px' maxlength='255' readOnly>
							</td>
							<td class='find' onClick='JsMail(document.formulario.Email.value)'><img src='../../img/estrutura_sistema/ico_outlook.gif' alt='Enviar E-mail'></td>
						</tr>
					</table>
				</div>
				<div id='cpDadosServico'>
					<div id='cp_tit' style='margin-top:0'>Dados do Serviço</div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B  style='margin-right:35px; color:#000' id='IdServico'>Serviço</B>Nome Serviço</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Periodicidade</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdServico' value=''  style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:590px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescPeriodicidade' value=''  style='width:134px' readOnly>
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
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdContrato' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdServicoContrato' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='DescricaoServicoContrato' value='' style='width:500px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescPeriodicidadeContrato' value=''  style='width:137px' readOnly>
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
										$sql = "select IdCodigoInterno, ValorCodigoInterno from CodigoInterno where IdGrupoCodigoInterno=9 order by ValorCodigoInterno";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdCodigoInterno]'>$lin[ValorCodigoInterno]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='TipoContrato' style='width:141px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" disabled><DIV ALIGN="justify"></DIV>
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
								<input type='text' name='DataCriacao' value='' style='width:202px' readOnly>
							</td>								
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='10' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='11' onClick="excluir(document.formulario.IdOrdemServico.value,document.formulario.Data.value,document.formulario.Hora.value)">
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
				<div id='cpAgendamento' style='display:none; margin-top:0'>
					<div id='cp_tit' style='margin-bottom:0'>Ordem de Serviço Agendados</div>
					<table id='tabelaAgendamento' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='bt_lista'>&nbsp;</td>
							<td>Responsável</td>
							<td>Data Agendamento</td>
							<td>Hora Agendamento</td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td colspan='3' id='tabelaAgendamentoTotal'></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
					<table id='helpText2table' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText2' name='helpText2'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>
			</form>
		</div>
	</body>	
</html>
<script>
	<?
		if($local_IdOrdemServico != ""){
			echo "busca_ordem_servico($local_IdOrdemServico,false);";
		}
	?>
	verificaAcao();
	verificaErro();
	inicia();
</script>
