<?
	$localModulo		=	1;
	$localOperacao		=	27;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login_Sistema	= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdOrdemServico				= $_POST['IdOrdemServico'];
	$local_IdEncaminhamento				= $_POST['IdEncaminhamento'];
	$local_Login						= formatText($_POST['Login'],NULL);
	$local_DataAgendamento				= formatText($_POST['DataAgendamento'],NULL);
	$local_HoraAgendamento				= formatText($_POST['HoraAgendamento'],NULL);
	$local_DataEncaminhamento			= formatText($_POST['DataEncaminhamento'],NULL);
	$local_HoraEncaminhamento			= formatText($_POST['HoraEncaminhamento'],NULL);
	$local_DescricaoSolucao				= formatText($_POST['DescricaoSolucao'],NULL);
	$local_IdStatus						= $_POST['IdStatus'];
	
	if($_GET['IdOrdemServico']!=''){
		$local_IdOrdemServico	= $_GET['IdOrdemServico'];	
	}
	if($_GET['IdEncaminhamento']!=''){
		$local_IdEncaminhamento	= $_GET['IdEncaminhamento'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_historico_os.php');
			break;		
		case 'alterar':
			include('files/editar/editar_historico_os.php');
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
		<script type = 'text/javascript' src = '../../js/event.js'></script>
		<script type = 'text/javascript' src = 'js/historico_os.js'></script>
		<script type = 'text/javascript' src = 'js/historico_os_defaul.js'></script>
		<script type = 'text/javascript' src = 'js/ordem_servico_default.js'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_default.js'></script>
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('Histórico Ordem de Serviço')">
		<? include('filtro_historico_os.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_historico_os.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='HistoricoOS'>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'>Ordem de Serviço</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdOrdemServico' value='' autocomplete="off" style='width:70px' maxlength='11' onChange='busca_ordem_servico(this.value,false,document.formulario.Local.value)' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
						</tr>
					</table>
				</div>
				
				<div id='cp_dadosCliente'>
					<div id='cp_tit'>Dados da Pessoa</div>
					<table id='cp_juridica'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:36px'>Pessoa</B>Nome Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo' id='cp_RazaoSocial_Titulo'>Razão Social</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Fone</td>
						<tr>
							<td class='find' onClick="janela_busca_pessoa();"><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar'></td>
							<td class='campo'>
								<input type='text' name='IdPessoa' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_pessoa(this.value,'false',document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'><input type='text' class='agrupador' name='Nome' value='<?=$local_Nome?>' style='width:306px' maxlength='100' readOnly>
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
							<td class='descCampo'><B style='margin-right:36px'>Pessoa</B>Nome Pessoa</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Sexo</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Estado Civil</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Fone</td>
						<tr>
							<td class='find' onClick="janela_busca_pessoa();"><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar'></td>
							<td class='campo'>
								<input type='text' name='IdPessoaF' value='' autocomplete="off" style='width:70px' maxlength='11' onChange="busca_pessoa(this.value,'false',document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'><input type='text' class='agrupador' name='NomeF' value='' style='width:306px' maxlength='100' readOnly>
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
								<select name='EstadoCivil'  style='width:150px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  disabled>
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
								<input type='text' name='Cidade' value='' autocomplete="off" style='width:233px' maxlength='100' readOnly><input type='text' class='agrupador' name='SiglaEstado' value='' style='width:70px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" readOnly>
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
							<td class='descCampo'><B  style='margin-right:35px;' id='IdServico'>Serviço</B>Nome Serviço</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Periodicidade</td>
						</tr>
						<tr>
							<td class='find' onClick="janela_busca_servico();"><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar'></td>
							<td class='campo'>
								<input type='text' name='IdServico' value=''  style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onkeypress="mascara(this,event,'int')" onChange='busca_servico(this.value,false,document.formulario.Local.value)' tabindex='3'><input type='text' class='agrupador' name='DescricaoServico' value='<?=$local_DescricaoServico?>' style='width:590px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
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
							<td class='find' onClick="janela_busca_contrato();"><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar'></td>
							<td class='campo'>
								<input type='text' name='IdContrato' value='' autocomplete="off" style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" onChange="busca_contrato(this.value,false,document.formulario.Local.value)" tabindex='4'>
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
								<select name='TipoContrato' style='width:141px' disabled><DIV ALIGN="justify"></DIV>
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
					<div id='cp_tit'>Dados Ordem de  Serviço</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Descrição Ordem de Serviço</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='DescricaoOS' style='width: 816px;' rows=5 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='5'></textarea>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B>Valor (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B>Status</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='find'>&nbsp;</td>
							<td class='descCampo' id='cpTitResponsabel'><B>Responsável</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Valor' value='' style='width:151px' maxlength='12' onFocus="Foco(this,'in')" onkeypress="mascara(this,event,'float')" onBlur="Foco(this,'out');"  tabindex='6'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdStatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:140px' tabindex='7'>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='find' onClick="janela_busca_usuario();" id='cpBuscaResponsavel'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar'></td>
							<td class='campo' id='cpResponsavel'>
								<input type='text' name='Login' value='' style='width:170px' maxlength='20' onChange='busca_usuario(this.value)' tabindex='8' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"><input type='text' class='agrupador' name='NomeUsuario' value='<?=$local_NomeUsuario?>' style='width:300px' maxlength='30' readOnly>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='float:right; margin-right:6px'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='15' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='16' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='17' onClick="excluir(document.formulario.IdServico.value)">
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
			</form>
		</div>
	</body>	
</html>
<script>
	<?
		if($local_IdOrdemServico != ""){
			echo "busca_servico($local_IdServico,false);";
			if($local_IdEncaminhamento != ""){
				echo "busca_historico_os($local_IdEncaminhamento,false);";
			}
		}
	?>
	function status_inicial(){
		if(document.formulario.ContratoImpresso.value == '' || document.formulario.ContratoImpresso.value == 0){
			document.formulario.ContratoImpresso.value = '<?=getCodigoInterno(3,14)?>';
		}
		if(document.formulario.Periodicidade.value == '' || document.formulario.Periodicidade.value == 0){
			document.formulario.Periodicidade.value = '<?=getCodigoInterno(3,15)?>';
		}
		if(document.formulario.AtivacaoAutomatica.value == '' || document.formulario.AtivacaoAutomatica.value == 0){
			document.formulario.AtivacaoAutomatica.value = '<?=getCodigoInterno(3,16)?>';
		}
		if(document.formulario.IdStatus.value == '' || document.formulario.IdStatus.value == 0){
			document.formulario.IdStatus.value = '<?=getCodigoInterno(3,4)?>';
		}
				
	}
	verificaAcao();
	verificaErro();
	inicia();
	enterAsTab(document.forms.formulario);
</script>
