<?
	$localModulo		=	1;
	$localOperacao		=	42;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdLoteRepasse						= $_POST['IdLoteRepasse'];
	$local_IdPessoa								= $_POST['IdPessoa'];
	$local_ObsRepasse							= $_POST['ObsRepasse'];
	$local_Filtro_MesReferencia					= $_POST['Filtro_MesReferencia'];
	$local_Filtro_MenorVencimento				= $_POST['Filtro_MenorVencimento'];
	$local_Filtro_IdServico						= $_POST['Filtro_IdServico'];
	$local_Filtro_IdPessoa						= $_POST['Filtro_IdPessoa'];
	$local_Filtro_IdLocalRecebimento			= $_POST['Filtro_IdLocalRecebimento'];
	$local_Filtro_IdAgenteAutorizadoCarteira	= $_POST['Filtro_IdAgenteAutorizadoCarteira'];
	$local_Filtro_IdPaisEstadoCidade			= $_POST['Filtro_IdPaisEstadoCidade'];
	
	if($_GET['IdLoteRepasse']!=''){
		$local_IdLoteRepasse	= $_GET['IdLoteRepasse'];	
	}
	if($_GET['IdPessoa']!=''){
		$local_IdPessoa			= $_GET['IdPessoa'];	
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_lote_repasse.php');
			break;		
		case 'alterar':
			include('files/editar/editar_lote_repasse.php');
			break;
		case 'processar':
			include('rotinas/processar_lote_repasse.php');
			$local_Acao =	'processar';
			break;
		case 'confirmar':
			include('rotinas/confirmar_lote_repasse.php');
			$local_Acao =	'confirmar';
			break;
		case 'cancelar':
			include('rotinas/cancelar_lote_repasse.php');
			$local_Acao =	'cancelar';
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
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js' charset='iso-8859-1'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js' charset='iso-8859-1'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js' charset='iso-8859-1'></script>
		<script type = 'text/javascript' src = '../../js/val_data.js' charset='iso-8859-1'></script>
		<script type = 'text/javascript' src = '../../js/val_mes.js' charset='iso-8859-1'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js' charset='iso-8859-1'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js' charset='iso-8859-1'></script> 
		<script type = 'text/javascript' src = '../../js/event.js' charset='iso-8859-1'></script> 
		<script type = 'text/javascript' src = 'js/lote_repasse.js' charset='iso-8859-1'></script>
		<script type = 'text/javascript' src = 'js/lote_repasse_default.js' charset='iso-8859-1'></script>
		<script type = 'text/javascript' src = 'js/terceiro_default.js' charset='iso-8859-1'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js' charset='iso-8859-1'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js' charset='iso-8859-1'></script>
		<script type = 'text/javascript' src = 'js/local_cobranca_default.js' charset='iso-8859-1'></script>
		<script type = 'text/javascript' src = 'js/agente_default.js' charset='iso-8859-1'></script>
		
		<script type="text/javascript" src="../../classes/calendar/calendar.js" charset='iso-8859-1'></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js" charset='iso-8859-1'></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js" charset='iso-8859-1'></script>
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
	<body  onLoad="ativaNome('<?=dicionario(47)?>')">
		<? include('filtro_lote_repasse.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_lote_repasse.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='LoteRepasse'>
				<input type='hidden' name='TotalRepasse' value=''>
				<input type='hidden' name='TotalValor' value=''>
				<input type='hidden' name='UltimoLote' value=''>
				<input type='hidden' name='IdStatus' value=''>
				<input type='hidden' name='Filtro_IdPessoa' value=''>
				<input type='hidden' name='Filtro_IdPaisEstadoCidade' value=''>
				<input type='hidden' name='Filtro_IdServico' value=''>
				<input type='hidden' name='Filtro_IdLocalRecebimento' value=''>
				<input type='hidden' name='Filtro_IdAgenteAutorizadoCarteira' value=''>
				
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(47)?></td>
							<td class='separador'>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdLoteRepasse' value='' autocomplete="off" style='width:70px' maxlength='11' onChange='busca_lote_repasse(this.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='descricao' style='width: 732px;'><B id='cp_Status'>&nbsp;</B></td>
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit'><?=dicionario(776)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(33)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='cp_MesReferencia'><?=dicionario(196)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titFiltro_MenorVencimento' style='color:#000;'><?=dicionario(777)?></B></td>
						</tr>
						<tr>
							<td class='find' style='vertical-align:top;'><img style='margin-top:6px;' src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaTerceiro', true, event, null, 170);"></td>
							<td class='campo' style='vertical-align:top;'>
								<input type='text' name='IdPessoa' value='' autocomplete="off" style='width:70px' maxlength='11' onChange='busca_terceiro(this.value,false,document.formulario.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'><input class='agrupador' type='text' name='Nome' value='' style='width:398px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' style='vertical-align:top;'>
								<input type='text' name='Filtro_MesReferencia' value='' style='width:90px' maxlength='7' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out');" onkeypress="mascara(this,event,'mes')" onChange="verifica_mes('cp_MesReferencia',this)"  tabindex='3'>
								<br />
								<?=dicionario(425)?>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' id='cpFiltro_MenorVencimento' name='Filtro_MenorVencimento' value='' autocomplete="off" style='width:110px;' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onChange="validar_Data('titFiltro_MenorVencimento',this);" tabindex='4'>
								<br />
								<?=dicionario(709)?>
							</td>
							<td class='find' style='vertical-align:top;'><img id='cpFiltro_MenorVencimentoIco' style='margin-top:6px;' src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpFiltro_MenorVencimento",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpFiltro_MenorVencimentoIco"
							    });
							</script>
						</tr>
					</table>
				</div>
				<div>
					<div class='cp_tit' id='cpFiltro'><?=dicionario(559)?></div>
					<div id='cp_filtro_pessoa'>
						<?	
							$nome="	<table id='titTabelaPessoa' style='margin:10px 0 5px 0'>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B style='margin-right:36px; color:#000'>".dicionario(26)."</B>".dicionario(85)."</td>
										<td class='separador'>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\"></td>
										<td class='campo'>
											<input type='text' name='IdPessoaFiltro' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='5'><input type='text' class='agrupador' name='NomeFiltro' value='' style='width:646px' maxlength='100' readOnly>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='button' name='bt_add' value='Adicionar' class='botao' style='width:84px;' tabindex='6' onClick=\"busca_pessoa(document.formulario.IdPessoaFiltro.value,false,'AdicionarLoteRepasse')\">
										</td>
									</tr>
								</table>";
								
							$razao="	<table id='titTabelaPessoa'style='margin:10px 0 5px 0'>
									<tr>
										<td class='find'>&nbsp;</td>
										<td class='descCampo'><B style='margin-right:36px; color:#000'>".dicionario(26)."</B><B style='color:#000'>".dicionario(172)."</B></td>
										<td class='separador'>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick=\"vi_id('quadroBuscaPessoa', true, event, null, 165);\"></td>
										<td class='campo'>
											<input type='text' name='IdPessoaFiltro' value='' autocomplete='off' style='width:70px' maxlength='11' onChange=\"busca_pessoa(this.value,'false',document.formulario.Local.value);\" onkeypress=\"mascara(this,event,'int')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex='5'><input type='text' class='agrupador' name='NomeFiltro' value='' style='width:646px' maxlength='100' readOnly>
										</td>
										<td class='separador'>&nbsp;</td>
										<td class='campo'>
											<input type='button' name='bt_add' value='Adicionar' class='botao' style='width:84px;' tabindex='6' onClick=\"busca_pessoa(document.formulario.IdPessoaFiltro.value,false,'AdicionarLoteRepasse')\">
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
					<div id='cp_filtro_cidade'>
						<table style='margin:10px 0 5px 0' id='titTabelaCidade'>
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
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaPais', true, event, null, 265); document.formularioPais.NomePais.value=''; valorCampoPais = ''; busca_pais_lista(); document.formularioPais.NomePais.focus();"></td>
								<td class='campo'>
									<input type='text' name='IdPais' value='' style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='7' onChange="busca_pais(this.value,false,document.formulario.Local.value)" onkeypress="mascara(this,event,'int')"><input  class='agrupador' type='text' name='Pais' value='<?=$local_Pais?>' style='width:138px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaEstado', true, event, null, 265); document.formularioEstado.NomeEstado.value=''; valorCampoEstado = ''; busca_estado_lista(); document.formularioEstado.NomeEstado.focus();"></td>
								<td class='campo'>
									<input type='text' name='IdEstado' value='' style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='8' onChange='busca_estado(document.formulario.IdPais.value,this.value)' onkeypress="mascara(this,event,'int')"><input class='agrupador' type='text' name='Estado' value='<?=$local_Estado?>' style='width:140px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaCidade', true, event, null, 265); document.formularioCidade.NomeCidade.value=''; valorCampoCidade = ''; busca_cidade_lista(); document.formularioCidade.NomeCidade.focus();"></td>
								<td class='campo'>
									<input type='text' name='IdCidade' value='' style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='9' onChange='busca_cidade(document.formulario.IdPais.value,document.formulario.IdEstado.value,this.value)' onkeypress="mascara(this,event,'int')"><input class='agrupador' type='text' name='Cidade' value='<?=$local_Cidade?>' style='width:140px' maxlength='100' readOnly>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='button' name='bt_add_cidade' value='<?=dicionario(640)?>' class='botao' style='width:84px;' tabindex='10' onClick="busca_cidade(document.formulario.IdPais.value,document.formulario.IdEstado.value,document.formulario.IdCidade.value,false,'AdicionarLoteRepasse');">
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
					<div id='cp_filtro_servico'>
						<table style='margin:10px 0 5px 0;' id='titTabelaServico'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B style='margin-right:34px; color:#000'><?=dicionario(30)?></b><?=dicionario(223)?></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(436)?></td>
								<td class='separador'>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaServico', true, event, null, 350);"></td>
								<td class='campo'>
									<input type='text' name='IdServico' value=''  style='width:70px' maxlength='11' onChange='busca_servico(this.value,true,document.formulario.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='11'><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:455px' maxlength='100' readOnly>
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
									<input type='button' name='bt_add' value='<?=dicionario(640)?>' class='botao' style='width:84px;' tabindex='12' onClick="busca_servico(document.formulario.IdServico.value,false,'AdicionarLoteRepasse','busca')">
								</td>
							</tr>
						</table>
						<table id='tabelaServico' class='tableListarCad' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco'><?=dicionario(30)?></td>
								<td><?=dicionario(223)?></td>
								<td class='valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
								<td class='valor'><?=dicionario(520)?> (<?=getParametroSistema(5,1)?>)</td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='2' id='totaltabelaServico'><?=dicionario(128)?>: 0</td>
								<td id='cptotalValor' class='valor'>0,00</td>
								<td id='cptotalRepasse' class='valor'>0,00</td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
						</table>
					</div>
					<div id='cp_filtro_local_recebimento'>
						<table style='margin:10px 0 5px 0;' id='titTabelaLocalRecebimento'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(696)?></td>
								<td class='separador'>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<select name='IdLocalRecebimento' style='width:727px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='13'>
										<option value=''>&nbsp;</option>
										<?
											$sql = "select IdLocalCobranca, DescricaoLocalCobranca from LocalCobranca where IdLoja=$local_IdLoja and IdArquivoRetornoTipo is null order by DescricaoLocalCobranca";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdLocalCobranca]'>$lin[DescricaoLocalCobranca]</option>";
											}
										?>
									</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='button' name='bt_add' value='<?=dicionario(640)?>' class='botao' style='width:84px;' tabindex='14' onClick="busca_local_cobranca(document.formulario.IdLocalRecebimento.value,false,'AdicionarLoteRepasse')">
								</td>
							</tr>
						</table>
						<table id='tabelaLocalRecebimento' class='tableListarCad' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' style='width:60px'><?=dicionario(141)?></td>
								<td><?=dicionario(696)?></td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='5' id='totaltabelaLocalRecebimento'><?=dicionario(128)?>: 0</td>
							</tr>
						</table>
					</div>
					<div id='cp_filtro_agente_autorizado_carteira'>
						<table id='titTabelaAgente' style='margin:10px 0 5px 0'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(32)?></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><B style='color:#000' id='cpCarteira'><?=dicionario(117)?></B></td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaAgente', true, event, null, 200);"></td>
								<td class='campo'>
									<input type='text' name='IdAgenteAutorizado' value='' autocomplete='off' style='width:70px' maxlength='11' onChange="busca_agente(this.value,'false',document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='15'><input type='text' class='agrupador' name='NomeAgenteAutorizado' value='' style='width:305px' maxlength='100' readOnly onChange="listar_carteira(this.value);">
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
								<select type='text' name='IdCarteira' value='' style='width:330px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='16'>
									<option value='0'>&nbsp;</option>
								</select>
								</td>
								<td class='separador'>&nbsp;</td>
								<td class='campo'>
									<input type='button' name='bt_add' value='<?=dicionario(640)?>' class='botao' style='width:84px;' tabindex='17' onClick="adicionar_agente_autorizado_carteira(document.formulario.IdAgenteAutorizado.value,document.formulario.IdCarteira.value)">
								</td>
							</tr>
						</table>
						<table id='tabelaAgenteAutorizadoCarteira' class='tableListarCad' cellspacing='0'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' style='width:60px'><?=dicionario(778)?></td>
								<td><?=dicionario(779)?></td>
								<td><?=dicionario(194)?></td>
								<td><?=dicionario(186)?></td>
								<td><?=dicionario(139)?></td>
								<td><?=dicionario(117)?></td>
								<td class='bt_lista'>&nbsp;</td>
							</tr>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' colspan='8' id='totaltabelaAgenteAutorizadoCarteira'><?=dicionario(128)?>: 0</td>
							</tr>
						</table>
					</div>
				</div>
				<div id='cp_LancFinanceiro' style='display:none'>
					<div id='cp_tit' style='margin-bottom:0'><?=dicionario(649)?></div>
					<table id='tabelaLancFinanceiro' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'><?=dicionario(141)?></td>
							<td><?=dicionario(178)?></td>
							<td><?=dicionario(202)?></td>
							<td><?=dicionario(410)?>.</td>
							<td><?=dicionario(780)?>.</td>
							<td class='valor'><?=dicionario(781)?>(<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'>Valor Terceiro(<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'><?=dicionario(782)?>(<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'><?=dicionario(783)?>(<?=getParametroSistema(5,1)?>)</td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='5' id='tabelaTotal'></td>
							<td id='tabelaTotalItem' class='valor'>0,00</td>
							<td id='tabelaTotalValorRepasseTerceiro' class='valor'>0,00</td>
							<td id='tabelaTotalDescontoItem' class='valor'>0,00</td>
							<td id='tabelaTotalRepasse' class='valor'>0,00</td>
							<td class='bt_lista'>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div id='cp_log'>
					<div id='cp_tit'><?=dicionario(129)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(159)?></B></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='ObsRepasse' style='width: 816px;' rows=5 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='18'></textarea>
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
								<input type='text' name='LoginAlteracao' value='' style='width:180px'  readOnly>
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
							<td class='descCampo'><?=dicionario(784)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(785)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(786)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(787)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginProcessamento' value='' style='width:180px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataProcessamento' value='' style='width:202px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginConfirmacao' value='' style='width:180px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataConfirmacao' value='' style='width:203px' readOnly>
							</td>								
						</tr>
					</table>
				</div>
				<div>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' style='width:200px' />
							<td class='campo'>
								<input type='button' style='width:120px' name='bt_imprimir' value='<?=dicionario(788)?>' class='botao' tabindex='101' onClick="imprimirEspelho(document.formulario.IdLoteRepasse.value)">
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' style='width:80px' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='102' onClick="cadastrar('inserir')">
								<input type='button' style='width:65px' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='103' onClick="cadastrar('alterar')">
								<input type='button' style='width:65px' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='104' onClick="excluir(document.formulario.IdLoteRepasse.value,document.formulario.IdStatus.value)">
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' style='text-align:right;'>
								<input type='button' style='width:80px' name='bt_processar' value='<?=dicionario(789)?>' class='botao' tabindex='105' onClick="cadastrar('processar')">
								<input type='button' style='width:70px' name='bt_confirmar' value='<?=dicionario(404)?>' class='botao' tabindex='106' onClick="confirmar_processo()">
								<input type='button' style='width:70px' name='bt_cancelar' value='<?=dicionario(405)?>' class='botao' tabindex='107' onClick="cancelar_processo()">
							</td>
						</tr>
					</table>
				</div>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 id='helpText' name='helpText'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>
			</form>
		</div>
		<div id='quadros_fluantes'>
			<?
				include("files/busca/pessoa.php");
				include("files/busca/terceiro.php");
				include("files/busca/servico.php");
				include("files/busca/agente.php");
				include("files/busca/pais.php");
				include("files/busca/estado.php");
				include("files/busca/cidade.php");
			?>
		</div>
	</body>	
</html>	
<script>
<?
	if($local_IdLoteRepasse != ''){
		echo "busca_lote_repasse($local_IdLoteRepasse,false,document.formulario.Local.value);";				
	}else{
		if($local_IdPessoa!=''){
			echo "busca_terceiro($local_IdPessoa,false,document.formulario.Local.value);";		
		}
	}
?>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>