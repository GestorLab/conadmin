<?
	$localModulo		=	1;
	$localOperacao		=	2;
	$localSuboperacao	=	"V";	

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	$local_IdContrato				= 	$_POST['IdContrato'];
	$local_IdContratoFilho			= 	$_POST['IdContratoFilho'];
	$local_DataInicioVigencia		= 	formatText($_POST['DataInicioVigencia'],NULL);	
	$local_IdContratoTipoVigencia	=	formatText($_POST['IdContratoTipoVigencia'],NULL);
	$local_DataTerminoVigencia		= 	formatText($_POST['DataTerminoVigencia'],NULL);	
	$local_DiaLimiteDesconto		= 	formatText($_POST['DiaLimiteDesconto'],NULL);		
	$local_Valor					= 	formatText($_POST['Valor'],NULL);	
	$local_ValorDesconto			= 	formatText($_POST['ValorDesconto'],NULL);	
	$local_ValorRepasseTerceiro		= 	formatText($_POST['ValorRepasseTerceiro'],NULL);	
	$local_IdTipoDesconto			= 	formatText($_POST['IdTipoDesconto'],NULL);
	$local_ObsVigencia				= 	formatText($_POST['ObsVigencia'],NULL);
	
	if($_GET['IdContrato']!=''){
		$local_IdContrato	=	$_GET['IdContrato'];
	}
	if($_GET['IdContratoFilho']!=''){
		$local_IdContratoFilho	=	$_GET['IdContratoFilho'];
	}
	if($_GET['DataInicioVigencia']!=''){
		$local_DataInicioVigencia	=	$_GET['DataInicioVigencia'];
	}
	
	switch ($local_Acao){
		case 'inserir':
			include('files/inserir/inserir_contrato_vigencia.php');
			break;		
		case 'alterar':
			include('files/editar/editar_contrato_vigencia.php');
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
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/mascara_real.js'></script>
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/vigencia.js'></script>
		<script type = 'text/javascript' src = 'js/contrato_default.js'></script>
		<script type = 'text/javascript' src = 'js/pessoa_default.js'></script>
		<script type = 'text/javascript' src = 'js/servico_default.js'></script>
		<script type = 'text/javascript' src = 'js/tipo_vigencia_default.js'></script>
		
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
	<body  onLoad="ativaNome('Contrato/Vigência')">
		<? include('filtro_contrato.php'); ?>
		<div id='carregando'>carregando</div>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_vigencia.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='Vigencia'>
				<input type='hidden' name='Isento' value=''>
				<input type='hidden' name='ServicoAutomatico' value=''>
				<input type='hidden' name='Periodicidade' value=''>
				<input type='hidden' name='MoedaAtual' value='<?=getParametroSistema(5,1)?>'>
				<input type='hidden' name='PermicaoTerceiro' value='<?=permissaoSubOperacao($localModulo,152,"U")?>'>
				<div id='cp_dadosContrato'>
					<div id='cp_tit' style='margin-top:0'><?=dicionario(221)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(27)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:36px'><?=dicionario(30)?></B><?=dicionario(223)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(224)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(516)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(226)?></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaContrato', true, event, null, 140); limpa_form_contrato(); busca_contrato_lista(); document.formularioContrato.Nome.focus();"></td>
							<td class='campo'>
								<input type='text' name='IdContrato' value='' autocomplete="off" style='width:70px' maxlength='11' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" onChange="busca_contrato(this.value,false,document.formulario.Local.value); busca_vigencia(this.value,Erro,Local,'',this.name)" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdServico' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='DescricaoServico' value='<?=$local_DescricaoServico?>' style='width:288px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DescPeriodicidade' value=''  style='width:150px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdParcela' value=''  style='width:70px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='TipoContrato' style='width:101px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" disabled>
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
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(376)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(517)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(433)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(232)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(434)?>.</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(235)?>.</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataInicio' value='<?=$local_DataInicioVigencia?>' style='width:120px' maxlength='10' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataPrimeiraCobranca' value='' style='width:120px' maxlength='10' readOnly>
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
								<select name='AssinaturaContrato' style='width:137px' disabled>
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
				</div>	
				<div id='cp_dadosCliente'>
					<div id='cp_tit' style='margin-top:0'><?=dicionario(219)?></div>
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
							<td class='descCampo'><?=dicionario(179)?></td>
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
				<div id='cp_automatico' style='display:block'>
				</div>
				<div id='cp_vigencia' style='margin-bottom:0'>
					<div id='cp_tit'><?=dicionario(518)?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(27)?></B></td>
							<td class='separador'>&nbsp;</td>	
							<td class='descCampo'><B id='DataInicioVigencia'><?=dicionario(376)?></B></td>
							<td class='find'>&nbsp;</td>	
							<td class='separador'>&nbsp;</td>	
							<td class='descCampo'><B  id='DataTerminoVigencia' style='color:#000000'><?=dicionario(433)?></td>	
							<td class='find'>&nbsp;</td>	
							<td class='separador'>&nbsp;</td>	
							<td class='descCampo'><B><?=dicionario(519)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titValor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</B></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdContratoFilho' value='' style='width:70px' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int')" onChange="busca_vigencia(this.value,false,document.formulario.Local.value,formatDate(document.formulario.DataInicioVigencia.value),this.name)" tabindex='6'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataInicioVigencia' id='cpDataInicioVigencia' value='' style='width:120px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('DataInicioVigencia',this); busca_vigencia(document.formulario.IdContratoFilho.value,false,document.formulario.Local.value,formatDate(this.value),this.name)" tabindex='7'>
							</td>
							<td class='find' id='cpDataInicioVigenciaIco'><img src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataInicioVigencia",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataInicioVigenciaIco"
							    });
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataTerminoVigencia' id='cpDataTerminoVigencia' value='' style='width:120px' maxlength='10' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onChange="validar_Data('DataTerminoVigencia',this); verificaDataFinal('DataInicioVigencia',document.formulario.DataInicioVigencia.value,this.value)" tabindex='8'>
							</td>
							<td class='find' id='cpDataTerminoVigenciaIco'><img src='../../img/estrutura_sistema/ico_date.gif' alt='Buscar data'></td>
							<script type="text/javascript">
							    Calendar.setup({
							        inputField     : "cpDataTerminoVigencia",
							        ifFormat       : "%d/%m/%Y",
							        button         : "cpDataTerminoVigenciaIco"
							    });
							</script>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='IdContratoTipoVigencia' style='width:265px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='9'>
									<option value=''>&nbsp;</option>
									<?
										$sql = "select IdContratoTipoVigencia, DescricaoContratoTipoVigencia from ContratoTipoVigencia where IdLoja=$local_IdLoja order by DescricaoContratoTipoVigencia ASC";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdContratoTipoVigencia]'>$lin[DescricaoContratoTipoVigencia]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Valor' value='' style='width:135px' maxlength='15' onFocus="Foco(this,'in')" onkeypress='reais(this,event)' onkeydown='backspace(this,event)' onBlur="Foco(this,'out'); calculaValorFinal(this.value,document.formulario.ValorDesconto.value,document.formulario.ValorPercentual.value,this)"  tabindex='10'>
							</td>
						</tr>
					</table>
					<table style="margin-left:16px;">
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B id='titValorRepasseTerceiro'><?=dicionario(520)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(312)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titValorDesconto'><?=dicionario(411)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titValorPercentual'><?=dicionario(521)?>(%)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><span id='titValorFinal'><?=dicionario(206)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B id='titDiaLimiteDesconto' style='display: none;'><?=dicionario(522)?></B></td>
							<td style="width:99%" />
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='ValorRepasseTerceiro' value='' style='width:155px' autocomplete="off" maxlength='15' onFocus="Foco(this,'in')" onkeypress='reais(this,event)' onkeydown='backspace(this,event)' onBlur="Foco(this,'out')" tabindex='11'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<select name='IdTipoDesconto' style='width:126px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="verificaLimiteDesconto(this.value)" tabindex='12'>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=73 order by ValorParametroSistema";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='ValorDesconto' id='cpValorDesconto' value='' style='width:115px' autocomplete="off" maxlength='12' onFocus="Foco(this,'in')" onkeypress="mascara(this,event,'float')" onBlur="Foco(this,'out');" onChange="calculaValorFinal(document.formulario.Valor.value,this.value,document.formulario.ValorPercentual.value,this)" tabindex='13'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='ValorPercentual' id='cpValorPercentual' value='' style='width:115px' autocomplete="off" maxlength='12' onFocus="Foco(this,'in')" onkeypress="mascara(this,event,'float')" onBlur="Foco(this,'out');" onChange="calculaValorFinal(document.formulario.Valor.value,document.formulario.ValorDesconto.value,this.value,this)" tabindex='14'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' valign='top'>
								<input type='text' name='ValorFinal' id='cpValorFinal' value='' style='width:115px' maxlength='15' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<span id='cpDiaLimiteDesconto' style='display:none'><input type='text' name='DiaLimiteDesconto' value='' autocomplete="off" style='width: 126px' maxlength='3' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'int',null,'neg')" tabindex='16'><BR><B style='font-size:9px; font-weight:normal'><?=dicionario(523)?></b></span>
							</td>						
						</tr>
					</table>
				</div>
				<div id='cp_vigencia_cadastrada' style='margin-bottom:0'>
					<div id='cp_tit' style='margin-bottom:0'><?=dicionario(524)?></div>
					<table id='tabelaVigencia' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'><?=dicionario(27)?></td>
							<td><?=dicionario(376)?></td>
							<td><?=dicionario(433)?></td>
							<td class='valor'><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
							<td><?=dicionario(312)?></td>
							<td class='valor'><?=dicionario(411)?> (<?=getParametroSistema(5,1)?>)</td>
							<td class='valor'><?=dicionario(206)?> (<?=getParametroSistema(5,1)?>)</td>
							<td><?=dicionario(525)?></td>
							<td class='valor'><?=dicionario(520)?> (<?=getParametroSistema(5,1)?>)</td>
							<td  class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' colspan='10' id='tabelaVigenciaTotal'><?=dicionario(128)?>: 0</td>
							<!--td id='tabelaVigenciaValor' class='valor'>0,00</td>
							<td>&nbsp;</td>
							<td id='tabelaVigenciaValorDesconto' class='valor'>0,00</td>
							<td id='tabelaVigenciaValorFinal' class='valor'>0,00</td>
							<td>&nbsp;</td>
							<td id='tabelaRepasse' class='valor'>0,00</td>
							<td>&nbsp;</td-->
							
						</tr>
					</table>
				</div>
				<div id='Log'>
					<div id='cp_tit'><?=dicionario(129)?></div>
					<table id='cpHistorico' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(130)?></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='HistoricoObs' style='width: 816px;' rows=5 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  readOnly></textarea>
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
								<textarea name='ObsVigencia' style='width: 816px;' rows=5 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" tabindex='17'></textarea>
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
								<input type='text' name='LoginAlteracao' value='' style='width:180px' maxlength='20' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>	
							<td class='campo'>
								<input type='text' name='DataAlteracao' value='' style='width:203px'  readOnly>
							</td>								
						</tr>
					</table>
				</div>
				<div id='cp_botao' style='height:23px;'>
					<table style='width:100%; text-align:right; padding-right:3px;'>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='Cadastrar' class='botao' tabindex='18' onClick='cadastrar()'>
								<input type='button' name='bt_alterar' value='Alterar' class='botao' tabindex='19' onClick='cadastrar()'>
								<input type='button' name='bt_excluir' value='Excluir' class='botao' tabindex='20' onClick="excluir(document.formulario.IdContratoFilho.value,formatDate(document.formulario.DataInicioVigencia.value))">
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
		<div id='quadros_fluantes'>
			<?
				include("files/busca/contrato.php");
				include("files/busca/contrato_tipo_vigencia.php");
			?>
		</div>
	</body>	
</html>
<script>
	<?
		if($local_IdContrato != ""){
			echo "busca_contrato($local_IdContrato,false,document.formulario.Local.value);";
		}
		
		if($local_IdContratoFilho != "" && $local_DataInicioVigencia != ""){
			echo "setTimeout(function (){ busca_vigencia($local_IdContratoFilho,false,document.formulario.Local.value,'$local_DataInicioVigencia'); }, 100);";
		}
	?>
	function status_inicial(){
		if(document.formulario.Acao.value == 'inserir'){
			document.formulario.IdTipoDesconto.value = '<?=getCodigoInterno(3,53)?>';
			
			verificaLimiteDesconto(document.formulario.IdTipoDesconto.value);
		}
		if(document.formulario.ValorDesconto.value == ''){
			var valorDesc = '<?=getCodigoInterno(3,12)?>';
			document.formulario.ValorDesconto.value = valorDesc.replace('.',',');
		}
		if(document.formulario.DiaLimiteDesconto.value == ''){
			document.formulario.DiaLimiteDesconto.value =	'<?=getCodigoInterno(3,43)?>';
		}
		if(document.formulario.ValorRepasseTerceiro.value == ""){
			document.formulario.ValorRepasseTerceiro.value = '0,00';
		}
		if(document.formulario.IdContrato.value != "" && document.formulario.DataInicioVigencia.value == ""){
			document.formulario.Valor.value	= "";		
		}
		
		if(document.formulario.PermicaoTerceiro.value == '1'){
			document.getElementById("titValorRepasseTerceiro").style.color = "#c10000";
			document.formulario.ValorRepasseTerceiro.readOnly = false;
		} else{
			document.getElementById("titValorRepasseTerceiro").style.color = "#000";
			document.formulario.ValorRepasseTerceiro.readOnly = true;
		}
	}
	verificaErro();
	verificaAcao();
	inicia();
	enterAsTab(document.forms.formulario);
</script>
