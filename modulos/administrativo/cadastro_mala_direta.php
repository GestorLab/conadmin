<?
	$localModulo		= 1;
	$localOperacao		= 155;
	$localSuboperacao	= "V";
	$localCadComum		= true;
	$Path				=   "../../";

	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	include('../../classes/envia_mensagem/envia_mensagem.php');
	
	$local_Login	= $_SESSION["Login"];
	$local_IdLoja	= $_SESSION["IdLoja"];
	$local_Acao 	= $_POST['Acao'];
	$local_Erro		= $_GET['Erro'];
	
	$local_IdMalaDireta					= $_POST['IdMalaDireta'];
	$local_DescricaoMalaDireta			= formatText($_POST['DescricaoMalaDireta'],NULL);
	$local_ListaEmail					= formatText($_POST['ListaEmail'],NULL);
	$local_IdStatus						= formatText($_POST['IdStatus'],NULL);
	$local_IdTipoMensagem				= formatText($_POST['IdTipoMensagem'],NULL);
	$local_TextoAvulso					= formatText($_POST['TextoAvulso'],NULL);
	$local_Filtro_IdPessoa				= formatText($_POST['Filtro_IdPessoa'],NULL);
	$local_Filtro_IdGrupoPessoa			= formatText($_POST['Filtro_IdGrupoPessoa'],NULL);
	$local_Filtro_IdServico				= formatText($_POST['Filtro_IdServico'],NULL);
	$local_Filtro_IdContrato			= formatText($_POST['Filtro_IdContrato'],NULL);
	$local_Filtro_IdStatusContrato		= formatText($_POST['Filtro_IdStatusContrato'],NULL);
	$local_Filtro_IdProcessoFinanceiro	= formatText($_POST['Filtro_IdProcessoFinanceiro'],NULL);
	$local_Filtro_IdPaisEstadoCidade	= formatText($_POST['Filtro_IdPaisEstadoCidade'],NULL);
	$local_IdTipoConteudo				= $_POST['IdTipoConteudo'];
	$local_IdContaEmail					= $_POST['IdContaEmail'];
	$local_LoginCriacao					= $_POST['LoginCriacao'];
	$local_DataCriacao					= $_POST['DataCriacao'];
	$local_LoginAlteracao				= $_POST['LoginAlteracao'];
	$local_DataAlteracao				= $_POST['DataAlteracao'];
	$local_LoginProcessamento			= $_POST['LoginProcessamento'];
	$local_DataProcessamento			= $_POST['DataProcessamento'];
	$local_LoginEnvio					= $_POST['LoginEnvio'];
	$local_DataEnvio					= $_POST['DataEnvio'];
	$local_ErroEmail					= $_GET['EmailErro'];
	$local_TipoEmail					= $_GET['TipoEmail'];
	
	if($local_IdMalaDireta == '') {
		$local_IdMalaDireta = $_GET['IdMalaDireta'];
	}
	
	switch($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_mala_direta.php');
			break;		
		case 'alterar':
			include('files/editar/editar_mala_direta.php');
			break;
		case 'processar':
			include('rotinas/processar_mala_direta.php');
			break;
		case 'cancelar':
			include('rotinas/cancelar_mala_direta.php');
			break;
		case 'enviar':
			header("Location: mala_direta_confirmar_enviar_mensagem.php?IdMalaDireta=$local_IdMalaDireta&IdTipoMensagem=$local_IdTipoMensagem");	
			break;
		default:
			$local_Acao = 'inserir';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		<link rel='stylesheet' type='text/css' href='../../css/procurar.css' />
	    <link rel='stylesheet' type='text/css' href='../../classes/calendar/calendar-blue.css' />
		
		<script type='text/javascript' src='../../classes/calendar/calendar.js'></script>
	    <script type='text/javascript' src='../../classes/calendar/calendar-setup.js'></script>
	    <script type='text/javascript' src='../../classes/calendar/calendar-br.js'></script>
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/val_url.js'></script>
		<script type='text/javascript' src='../../js/val_data.js'></script>
		<script type='text/javascript' src='../../js/val_time.js'></script>
		<script type='text/javascript' src='../../js/prototype.js'></script>
		<script type='text/javascript' src='../../js/scriptaculous.js'></script> 
		<script type='text/javascript' src='../../js/event.js'></script>
		<script type='text/javascript' src='js/mala_direta.js'></script>
		<script type='text/javascript' src='js/mala_direta_default.js'></script>
		<script type='text/javascript' src='js/pessoa_default.js'></script>
		<script type='text/javascript' src='js/grupo_pessoa_default.js'></script>
		<script type='text/javascript' src='js/servico_default.js'></script>
		<script type='text/javascript' src='js/contrato_default.js'></script>
		<script type='text/javascript' src='js/processo_financeiro_default.js'></script>
		
		<style type='text/css'>
			.title_down_camp { margin:-1px 0px 4px 0px; }
			input[type=text]:readOnly { background-color: #FFF; }
			input[type=datetime]:readOnly { background-color: #FFF; }
			input[type=date]:readOnly { background-color: #FFF; }
			textarea:readOnly { background-color: #FFF; }
			select:disabled { background-color: #FFF; }
			select:disabled { color: #000; }
			iframe { border: 1px #A4A4A4 solid; }
		</style>
	</head>
	<body onLoad="ativaNome('<?=dicionario(34)?>')">
		<? 
			include('filtro_mala_direta.php'); 
		?>
		<div id='carregando'><?=dicionario(17)?></div>
			<div id='conteudo'>
				<form name='formulario' method='post' action='cadastro_mala_direta.php' enctype ='multipart/form-data' onSubmit='return validar()'>
					<input type='hidden' name='Acao' value='<?=$local_Acao?>' />
					<input type='hidden' name='Erro' value='<?=$local_Erro?>' />
					<input type='hidden' name='Local' value='MalaDireta' />
					<input type='hidden' name='MaxSize' value='<?=ini_get('upload_max_filesize')?>' />
					<input type='hidden' name='IdStatus' value='1' />
					<input type='hidden' name='IdTipoMensagem' value='' />
					<input type='hidden' name='ExtAnexoArquivo' value='html,htm,jpg' />
					<input type='hidden' name='AltAnexoArquivo' value='1' />
					<input type='hidden' name='Filtro_IdPessoa' value='' />
					<input type='hidden' name='Filtro_IdGrupoPessoa' value='' />
					<input type='hidden' name='Filtro_IdServico' value='' />
					<input type='hidden' name='Filtro_IdContrato' value='' />
					<input type='hidden' name='Filtro_IdStatusContrato' value='' />
					<input type='hidden' name='Filtro_IdProcessoFinanceiro' value='' />
					<input type='hidden' name='Filtro_IdPaisEstadoCidade' value='' />
					<div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(34)?></td>
								<td class='separador'>&nbsp;</td>
								<td />
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='IdMalaDireta' value='' autocomplete='off' style='width:70px' maxlength='11' onChange="busca_mala_direta(this.value,false,document.formulario.Local.value)" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')" onBlur="Foco(this,'out')" tabindex='1' />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='descricao' style='width:732px;'><B id='cp_Status'>&nbsp;</B></td>
							</tr>
						</table>
					</div>
					<div>
						<div id='cp_tit'><?=dicionario(790)?></div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B id='titDescricaoMalaDireta'><?=dicionario(125)?></B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DescricaoMalaDireta' value='' style='width:816px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='2' />
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(791)?></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<textarea name='ListaEmail' style='width: 816px;' rows='5' tabindex='3' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');"></textarea>
									<br />
									<?=dicionario(792)?>: '|', ',', ':', ';' <?=dicionario(793)?>. 
								</td>
							</tr>
						</table>
					</div>
					<div id='cp_tit_filtro'>
						<div id='cp_tit'><?=dicionario(559)?></div>
						<div id='cp_filtro_pessoa'>
						<?	
							$nome = "
							<table id='titTabelaPessoa'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:36px; color:#000'>".dicionario(26)."</B>".dicionario(85)."</td>
									<td class='separador'>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\" /></td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='4'><input type='text' class='agrupador' name='Nome' value='' style='width:646px' maxlength='100' readOnly />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='button' name='bt_add_pessoa' value='".dicionario(640)."' class='botao' style='width:84px;' tabindex='5' onClick=\"busca_pessoa(document.formulario.IdPessoa.value,false,'AdicionarMalaDireta')\" />
									</td>
								</tr>
							</table>";
								
							$razao = "
							<table id='titTabelaPessoa'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:36px; color:#000'>".dicionario(26)."</B><B style='color:#000'>".dicionario(172)."</B></td>
									<td class='separador'>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\" /></td>
									<td class='campo'>
										<input type='text' name='IdPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='4'><input type='text' class='agrupador' name='Nome' value='' style='width:646px' maxlength='100' readOnly />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='button' name='bt_add_pessoa' value='".dicionario(640)."' class='botao' style='width:84px;' tabindex='5' onClick=\"busca_pessoa(document.formulario.IdPessoa.value,false,'AdicionarMalaDireta')\" />
									</td>
								</tr>
							</table>";
								
							switch(getCodigoInterno(3,24)) {
								case 'Nome':
									echo "$razao";
									break;
								default:
									echo "$nome";
							}
						?>
							<table id='tabelaPessoa' class='tableListarCad' cellspacing='0'>
								<tr class='tableListarTitleCad'>
									<td class='tableListarEspaco' style='width:60px'><?=dicionario(26)?></td>
									<td><?=dicionario(172)?></td>
									<td><?=dicionario(85)?></td>
									<td><?=dicionario(213)?></td>
									<td><?=dicionario(194)?></td>
									<td><?=dicionario(186)?></td>
									<td><?=dicionario(139)?></td>
									<td class='bt_lista'>&nbsp;</td>
								</tr>
								<tr class='tableListarTitleCad'>
									<td class='tableListarEspaco' colspan='8' id='totaltabelaPessoa'><?=dicionario(128)?>: 0</td>
								</tr>
							</table>
						</div>
						<div id='cp_filtro_grupo_pessoa'>
							<table style='margin:6px 0 5px 0' id='titTabelaPessoa'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:40px; color:#000'><?=dicionario(149)?></B><?=dicionario(794)?></td>
									<td class='separador'>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaGrupoPessoa', true, event, null, 300); document.formularioGrupoPessoa.Nome.value=''; valorCampoGrupoPessoa = ''; busca_grupo_pessoa_lista(); document.formularioGrupoPessoa.Nome.focus();" /></td>
									<td class='campo'>
										<input type='text' name='IdGrupoPessoa' value='' autocomplete='off' style='width:70px' maxlength='11' onChange="busca_grupo_pessoa(this.value,'false',document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='6' /><input type='text' class='agrupador' name='DescricaoGrupoPessoa' value='' style='width:646px' maxlength='100' readOnly />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='button' name='bt_add_grupo_pessoa' value='<?=dicionario(640)?>' class='botao' style='width:84px;' tabindex='7' onClick="busca_grupo_pessoa(document.formulario.IdGrupoPessoa.value,false,'AdicionarMalaDireta')" />
									</td>
								</tr>
							</table>					
							<table id='tabelaGrupoPessoa' class='tableListarCad' cellspacing='0'>
								<tr class='tableListarTitleCad'>
									<td class='tableListarEspaco'><?=dicionario(141)?></td>
									<td><?=dicionario(106)?></td>
									<td class='bt_lista'>&nbsp;</td>
								</tr>
								<tr class='tableListarTitleCad'>
									<td class='tableListarEspaco' colspan='8' id='totaltabelaGrupoPessoa'><?=dicionario(128)?>: 0</td>
								</tr>
							</table>
						</div>
						<div id='cp_filtro_servico'>
							<table style='margin:6px 0 5px 0' id='titTabelaServico'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:34px; color:#000'><?=dicionario(30)?></b><?=dicionario(223)?></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><?=dicionario(436)?></td>
									<td class='separador'>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaServico', true, event, null, 350);" /></td>
									<td class='campo'>
										<input type='text' name='IdServico' value='' style='width:70px' maxlength='11' onChange='busca_servico(this.value,true,document.formulario.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='8' /><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:455px' maxlength='100' readOnly />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<select name='IdTipoServico' style='width:180px' disabled>
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
									<td class='campo'>
										<input type='button' name='bt_add_servico' value='<?=dicionario(640)?>' class='botao' style='width:84px;' tabindex='9' onClick="busca_servico(document.formulario.IdServico.value,false,'AdicionarMalaDireta','busca')" />
									</td>
								</tr>
							</table>
							<table id='tabelaServico' class='tableListarCad' cellspacing='0'>
								<tr class='tableListarTitleCad'>
									<td class='tableListarEspaco' style='width:60px'><?=dicionario(30)?></td>
									<td><?=dicionario(223)?></td>
									<td><?=dicionario(436)?></td>
									<td class='valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
									<td class='bt_lista'>&nbsp;</td>
								</tr>
								<tr class='tableListarTitleCad'>
									<td class='tableListarEspaco' colspan='5' id='totaltabelaServico'><?=dicionario(128)?>: 0</td>
								</tr>
							</table>
						</div>
						<div id='cp_filtro_contrato'>
							<table style='margin:6px 0 5px 0' id='titTabelaContrato'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><?=dicionario(27)?></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><B style='color:#000; margin-right:36px'><?=dicionario(30)?></B><?=dicionario(223)?></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><?=dicionario(224)?></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><?=dicionario(226)?></td>
									<td class='separador'>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaContrato', true, event, null, 285);" /></td>
									<td class='campo'>
										<input type='text' name='IdContrato' value='' autocomplete="off" style='width:70px' maxlength='11' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" onChange="busca_contrato(this.value,false,document.formulario.Local.value);" tabindex='10' />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='IdServicoContrato' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly /><input type='text' class='agrupador' name='DescricaoServicoContrato' value='' style='width:300px' maxlength='100' readOnly />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='DescPeriodicidadeContrato' value=''  style='width:145px' readOnly />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='DescTipoContrato' value=''  style='width:80px' readOnly />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='button' name='bt_add_contrato' value='<?=dicionario(640)?>' class='botao' style='width:84px;' tabindex='11' onClick="addContrato()" />
									</td>
								</tr>
							</table>
							<table id='tabelaContrato' class='tableListarCad' cellspacing='0'>
								<tr class='tableListarTitleCad'>
									<td class='tableListarEspaco' style='width:60px'><?=dicionario(27)?></td>
									<td><?=dicionario(85)?></td>
									<td><?=dicionario(223)?></td>
									<td><?=dicionario(82)?></td>
									<td><?=dicionario(283)?></td>
									<td><?=dicionario(284)?></td>
									<td><?=dicionario(285)?>.</td>
									<td class='valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
									<td class='valor'><?=dicionario(286)?>. (<?=getParametroSistema(5,1)?>)</td>
									<td class='valor'><?=dicionario(206)?> (<?=getParametroSistema(5,1)?>)</td>
									<td><?=dicionario(140)?></td>
									<td class='bt_lista'>&nbsp;</td>
								</tr>
								<tr class='tableListarTitleCad'>
									<td class='tableListarEspaco' colspan='12' id='totaltabelaContrato'><?=dicionario(128)?>: 0</td>
								</tr>
							</table>
						</div>
						<div id='cp_filtro_status'>
							<table style='margin:6px 0 5px 0' id='titTabelaStatus'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><?=dicionario(174)?></td>
									<td class='separador'>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='campo'>
										<select name='IdStatusContrato' style='width:727px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='12'>
											<option value=''>&nbsp;</option>
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=69 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='button' name='bt_add_status' value='<?=dicionario(640)?>' class='botao' style='width:84px;' tabindex='13' onClick="addStatus(document.formulario.IdStatusContrato.value,'')" />
									</td>
								</tr>
							</table>
							<table id='tabelaStatus' class='tableListarCad' cellspacing='0'>
								<tr class='tableListarTitleCad'>
									<td class='tableListarEspaco' style='width:60px'><?=dicionario(141)?></td>
									<td><?=dicionario(140)?></td>
									<td class='bt_lista'>&nbsp;</td>
								</tr>
								<tr class='tableListarTitleCad'>
									<td class='tableListarEspaco' colspan='5' id='totaltabelaStatus'><?=dicionario(128)?>: 0</td>
								</tr>
							</table>
						</div>
						<div id='cp_filtro_processo_financeiro'>
							<table style='margin:6px 0 5px 0' id='titTabelaProcessoFinanceiro'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><?=dicionario(657)?></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><?=dicionario(196)?></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><?=dicionario(777)?></td>
									<td class='separador'>&nbsp;</td>
									<td class='descCampo'><?=dicionario(362)?></td>
								</tr>
								<tr>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaProcessoFinanceiro', true, event, null, 350);" /></td>
									<td class='campo'>
										<input type='text' name='IdProcessoFinanceiro' value='' style='width:70px' maxlength='11' onChange='busca_processo_financeiro(this.value, true, document.formulario.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='14' />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='MesReferencia' value='' style='width:120px' readOnly />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='MenorVencimento' value='' style='width:120px' readOnly />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='text' name='IdLocalCobranca' value='' style='width:360px' readOnly />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='button' name='bt_add_processo_financeiro' value='<?=dicionario(640)?>' class='botao' style='width:84px;' tabindex='15' onClick="busca_processo_financeiro(document.formulario.IdProcessoFinanceiro.value,false,'AdicionarMalaDireta');" />
									</td>
								</tr>
							</table>
							<table id='tabelaProcessoFinanceiro' class='tableListarCad' cellspacing='0'>
								<tr class='tableListarTitleCad'>
									<td class='tableListarEspaco' style='width:60px'><?=dicionario(657)?></td>
									<td><?=dicionario(196)?></td>
									<td><?=dicionario(777)?></td>
									<td><?=dicionario(362)?></td>
									<td class='bt_lista'>&nbsp;</td>
								</tr>
								<tr class='tableListarTitleCad'>
									<td class='tableListarEspaco' colspan='5' id='totaltabelaProcessoFinanceiro'><?=dicionario(128)?>: 0</td>
								</tr>
							</table>
						</div>
						<div id='cp_filtro_cidade'>
							<table style='margin:6px 0 5px 0' id='titTabelaCidade'>
								<tr>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:54px; color:#000'><?=dicionario(256)?></B><?=dicionario(257)?></td>
									<td class='separador'>&nbsp;</td>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:38px; color:#000'><?=dicionario(157)?></b><?=dicionario(259)?></td>
									<td class='separador'>&nbsp;</td>
									<td class='find'>&nbsp;</td>
									<td class='descCampo'><B style='margin-right:38px; color:#000'><?=dicionario(186)?></B><?=dicionario(260)?></td>
									<td class='separador'>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaPais', true, event, null, 265);" /></td>
									<td class='campo'>
										<input type='text' name='IdPais' value='' style='width:70px' maxlength='11' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='19' onChange="busca_pais(this.value,false,document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" /><input class='agrupador' type='text' name='Pais' value='' style='width:138px' maxlength='100' readOnly />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaEstado', true, event, null, 265);" /></td>
									<td class='campo'>
										<input type='text' name='IdEstado' value='' style='width:70px' maxlength='11' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='20' onChange='busca_estado(document.formulario.IdPais.value,this.value);' onkeypress="mascara(this,event,'int')" /><input class='agrupador' type='text' name='Estado' value='' style='width:140px' maxlength='100' readOnly />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaCidade', true, event, null, 265);" /></td>
									<td class='campo'>
										<input type='text' name='IdCidade' value='' style='width:70px' maxlength='11' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='21' onChange='busca_cidade(document.formulario.IdPais.value,document.formulario.IdEstado.value,this.value);' onkeypress="mascara(this,event,'int')" /><input class='agrupador' type='text' name='Cidade' value='' style='width:140px' maxlength='100' readOnly />
									</td>
									<td class='separador'>&nbsp;</td>
									<td class='campo'>
										<input type='button' name='bt_add_cidade' value='<?=dicionario(640)?>' class='botao' style='width:84px;' tabindex='22' onClick="busca_cidade(document.formulario.IdPais.value,document.formulario.IdEstado.value,document.formulario.IdCidade.value,false,'AdicionarMalaDireta')" />
									</td>
								</tr>
							</table>
							<table id='tabelaCidade' class='tableListarCad' cellspacing='0'>
								<tr class='tableListarTitleCad'>
									<td class='tableListarEspaco' style='width:60px'>&nbsp;</td>
									<td><?=dicionario(257)?></td>
									<td><?=dicionario(259)?></td>
									<td><?=dicionario(260)?></td>
									<td class='bt_lista'>&nbsp;</td>
								</tr>
								<tr class='tableListarTitleCad'>
									<td class='tableListarEspaco' colspan='5' id='totaltabelaCidade'><?=dicionario(128)?>: 0</td>
								</tr>
							</table>
						</div>
					</div>
					<div id='bcConteudoEnvio'>
						<div id='cp_tit'><?=dicionario(795)?></div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B id='titIdTipoConteudo'><?=dicionario(796)?></B></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'>
									<B id='titArquivoAnexo' style='display:none;'><?=dicionario(797)?></B>
									<B id='titModeloMalaDireta' style='display:none; margin:0 0 0 18px;'>
										<span id='titModeloMalaDiretaBusca'><b style='margin-right:10px;'><?=dicionario(34)?></b><span style='color:#000;'><?=dicionario(125)?></span></span>
										<span id='titTipoMensagem' style='display:none; color:#000;'><?=dicionario(718)?></span>
									</b>
								</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo' style='vertical-align:top;'>
									<select name='IdTipoConteudo' style='width:215px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onchange='habilitar_campo(this.value);' tabindex='16'>
										<option value=''>&nbsp;</option>
										<?
											$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=202 order by ValorParametroSistema";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<span id='cpArquivoAnexo' style='display:none;'>
										<input type='text' name='fakeupload_ArquivoAnexo' value='' autocomplete='off' style='width:506px; margin:0px;' onFocus="Foco(this,'in','auto');" onBlur="Foco(this,'out');" onchange='document.formulario.EndArquivoAnexo.value = this.value;' tabindex='17' />
										<div id='bt_procurar' style='margin:-22px 0px 0px 506px;'></div>
										<input type='file' id='realupload' name='EndArquivoAnexo' size='1' class='realupload' onchange='document.formulario.fakeupload_ArquivoAnexo.value = this.value; verifica_extensao();' />
										<div class='title_down_camp'><?=dicionario(798)?> <?=ini_get('upload_max_filesize')?>. <?=dicionario(799)?>: <span id='title_ExtAnexoArquivo'></span></div>
									</span>
									<span id='cpButtonArquivoAnexo' style='display:none;'>
										<input type='button' name='bt_visualizar_arquivo_anexo' value='Visualizar' class='botao' />&nbsp;
										<input type='button' name='bt_alterar_arquivo_anexo' value='Alterar' class='botao' />
									</span>
									<div id='cpModeloMalaDireta' style='width:596px; display:none;'>
										<span id='cpModeloMalaDiretaBusca'>
											<img style='float:left; cursor:pointer; margin-top:5px;' src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaMalaDiretaEnviada', true, event, null, 350);" />
											<input type='text' name='IdModeloMalaDireta' value='' style='width:70px; margin-left:5px;' maxlength='11' onChange='busca_mala_direta_enviada(this.value,false,document.formulario.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='18' /><input type='text' class='agrupador' name='DescricaoModeloMalaDireta' value='' style='width:497px' maxlength='100' readOnly />
										</span>
										<span id='cpTipoMensagem' style='display:none;'>
											<input type='text' name='IdTipoMensagemMalaDiretaEnviada' value='' style='width:95px;' maxlength='11' readOnly />
											<input type='button' name='bt_visualizar_mala_direta' style='margin:0 3px 0 7px;' value='Visualizar' class='botao' />&nbsp;
											<input type='button' name='bt_alterar_mala_direta' value='Alterar' onclick='alterar_modelo_mala_direta();' class='botao' />
										</span>
									</div>
								</td>
							</tr>
						</table>
						<table id='titTextAvulso' style='display:none;'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B id='titTextoAvulso'><?=dicionario(800)?></B></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<textarea name='TextoAvulso' onkeyup='visualizacao_texto_avulso(this.value);' style='width:816px;' rows='5' tabindex='19' onFocus="Foco(this,'in')" onBlur="Foco(this,'out'); visualizacao_texto_avulso(this.value);"></textarea>
									<div class='title_down_camp'><?=dicionario(801)?>.</div>
								</td>
							</tr>
							<tr />
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(802)?></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<iframe id='VisualizacaoTextoAvulso' style='width:820px;'></iframe>
								</td>
							</tr>
						</table>
						<table id='cpVisualizarHTML' style='display:none;'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(802)?></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<iframe id='VisualizacaoHTML' style='width:820px; height:282px;'></iframe>
								</td>
							</tr>
						</table>
					</div>
					<div>
						<div id='cp_tit'><?=dicionario(803)?></div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><b id='titIdContaEmail'><?=dicionario(104)?></b></td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<select name='IdContaEmail' style='width:822px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='20'>
										<option value=''>&nbsp;</option>
										<?
											$sql = "select IdContaEmail, concat(NomeRemetente,' (',EmailRemetente,')') NomeRemetente from ContaEmail where IdLoja = $local_IdLoja order by NomeRemetente";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdContaEmail]'>$lin[NomeRemetente]</option>";
											}
										?>
									</select>
								</td>
							</tr>
						</table>
					</div>
					<div>
						<div id='cp_tit'><?=dicionario(804)?></div>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(805)?> [ <a href='javascript: atualizar_campo(document.formulario.LogEnvio);'><?=dicionario(264)?></a> ]</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<textarea name='LogEnvio' style='width:816px;' rows='5' tabindex='21' readOnly></textarea>
								</td>
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
								<td class='separador'>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LoginCriacao' value='' style='width:181px' maxlength='20' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataCriacao' value='' style='width:202px' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LoginAlteracao' value='' style='width:180px' maxlength='20' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataAlteracao' value='' style='width:202px' readOnly />
								</td>							
							</tr>
						</table>
						<table>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(784)?></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(785)?></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(806)?></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(807)?></td>
								<td class='separador'>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LoginProcessamento' value='' style='width:181px' maxlength='20' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataProcessamento' value='' style='width:202px' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='LoginEnvio' value='' style='width:180px' maxlength='20' readOnly />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='text' name='DataEnvio' value='' style='width:202px' readOnly />
								</td>							
							</tr>
						</table>
					</div>
					<div class='cp_botao'>
						<table style='width:848px;'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<input type='button' style='width:165px;' name='bt_lista_email' value='<?=dicionario(808)?>' class='botao' tabindex='22' onClick='listar_lista_email()' />
								</td>
								<td class='campo' style='text-align:right;'>
									<input type='button' style='width:74px;' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='23' onClick="cadastrar('inserir')" />
									<input type='button' style='width:57px;' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='24' onClick="cadastrar('alterar')" />
									<input type='button' style='width:57px;' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='25' onClick="excluir(document.formulario.IdMalaDireta.value,document.formulario.IdStatus.value)" />
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo' style='width:223px; text-align:right;'>
									<input type='button' style='width:80px;' name='bt_processar' value='<?=dicionario(789)?>' class='botao' tabindex='26' onClick="cadastrar('processar')" />
									<input type='button' style='width:55px;' name='bt_enviar' value='<?=dicionario(809)?>' class='botao' tabindex='27' onClick="cadastrar('enviar')" />
									<input type='button' style='width:70px;' name='bt_cancelar' value='<?=dicionario(405)?>' class='botao' tabindex='28' onClick="cadastrar('cancelar')" />
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
					<div id='cp_lista_email' style='display:none;'>
						<div id='cp_tit' style='margin:10px 0 0 0'><?=dicionario(791)?></div>
						<table id='tabelaListaEmail' class='tableListarCad' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco'><?=dicionario(148)?></td>
								<td><?=dicionario(104)?></td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='2' id='tabelaListaEmailTotal'><?=dicionario(128)?>: 0</td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>
				</form>
			</div>
			<div id='quadros_fluantes'>
				<?
					include("files/busca/pessoa.php");
					include("files/busca/grupo_pessoa.php");
					include("files/busca/servico.php");
					include("files/busca/contrato.php");
					include("files/busca/processo_financeiro_confirmado.php");
					include("files/busca/mala_direta_enviada.php");
					include("files/busca/pais.php");
					include("files/busca/estado.php");
					include("files/busca/cidade.php");
				?>
			</div>
		</div>
		<script type='text/javascript'>
		<!-- 
		<?
			if($local_IdMalaDireta != '') {
				echo "busca_mala_direta($local_IdMalaDireta, false, document.formulario.Local.value);";
				echo "scrollWindow('bottom');";	
			}
		?>
			function inicia() {
				document.getElementById("title_ExtAnexoArquivo").innerHTML = document.formulario.ExtAnexoArquivo.value.replace(/,/g, ', ') + ".";
				document.formulario.IdMalaDireta.focus();
			}
			
			verificaErro();
			verificaAcao();
			inicia();
			enterAsTab(document.forms.formulario);
			-->
		</script>
	</body>
</html>