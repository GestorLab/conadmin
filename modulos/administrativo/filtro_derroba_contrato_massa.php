<?
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	if($localOrdem == ''){					$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
	
	LimitVisualizacao("filtro");
	
	$localCancelado					=	$_SESSION["filtro_contrato_cancelado"];
	$localSoma						=	$_SESSION["filtro_contrato_soma"];	
	$localParametroAproximidade		=	$_SESSION["filtro_parametro_aproximidade"];
	$localQTDCaracterColunaPessoa	=	$_SESSION["filtro_QTDCaracterColunaPessoa"];
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
		<script type = 'text/javascript' src = '../../js/prototype.js'></script>
		<script type = 'text/javascript' src = '../../js/scriptaculous.js'></script> 
		<script type = 'text/javascript' src = '../../js/event.js'></script> 
		<script type = 'text/javascript' src = 'js/contrato_derrobar_login.js'></script>
		
		<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	    <script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>
	    <link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />
	</head>
	<body  onLoad="ativaNome('<?=dicionario(682)?>');">
		<div style='margin-top: -5px'>
			<form name='formulario' method='post' action='listar_contrato.php'>
				<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
				<input type='hidden' name='filtro' 					value='s' />
				<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
				<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
				<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
				<input type='hidden' name='filtro_ordem2'			value='<?=$localOrdem2?>' />
				<input type='hidden' name='filtro_ordem_direcao2'	value='<?=$localOrdemDirecao2?>' />
				<input type='hidden' name='filtro_tipoDado2'		value='<?=$localTipoDado2?>' />
				<input type='hidden' name='IdPessoa'				value='<?=$localIdPessoa?>' />
				<input type='hidden' name='IdContrato'				value='' />
				<input type='hidden' name='Local'					value='Contrato' />
				<input type='hidden' name='IdPessoaF'				value='' />
				<input type='hidden' name='Acao'					value='inserir' />
				<input type='hidden' name='Erro'					value='' />
				<input type='hidden' name='IdServico'				value='' />
				<input type='hidden' name='IdStatusContrato'		value='' />
				<div id='carregando'></div>
				<div class=''>
					<div class='cp_tit' id='Filtros'><?=dicionario(559)?></div>
					<div id='cp_filtro_status'>
						<table id='titTabelaStatus'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(174)?></td>
								<td class='separador'>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='campo'>
									<select name='Filtro_IdStatusContrato' style='width:727px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='11'>
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
									<input type='button' name='bt_add_status' value='<?=dicionario(640)?>' class='botao' style='width:84px;' tabindex='12' onClick="addStatus(document.formulario.IdStatusContrato.value,'')">
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
					<div id='cp_filtro_servico'>
						<table style='margin:10px 0 5px 0' id='titTabelaServico'>
							<tr>
								<td class='find'>&nbsp;</td>
								<td class='descCampo'><B style='margin-right:34px; color:#000'><?=dicionario(30)?></b><?=dicionario(223)?></td>
								<td class='separador'>&nbsp;</td>
								<td class='descCampo'><?=dicionario(436)?></td>
								<td class='separador'>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='<?=dicionario(166)?>' onClick="vi_id('quadroBuscaServico', true, event, null, 350);"></td>
								<td class='campo'>
									<input type='text' name='Filtro_IdServico' style='width:70px' maxlength='11' onChange='filtro_buscar_servico(this.value,true,document.formulario.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='23'><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:455px' maxlength='100' readOnly>
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
									<input type='button' name='bt_add_servico' value='Adicionar' class='botao' style='width:84px;' tabindex='24' onClick="alert(document.formulario.Filtro_IdServico.value);busca_servico(document.formulario.Filtro_IdServico.value,false,'AdicionarServico','busca')">
								</td>
							</tr>
						</table>
						<table id='tabelaServico' class='tableListarCad' cellspacing='0' style='width: 99%;margin-left: 10px;margin-right: 25px;'>
							<tr class='tableListarTitleCad'>
								<td class='tableListarEspaco' style='width:60px;'><?=dicionario(30)?></td>
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
				</div>
			</form>
			<div id='Erro'></div>
		</div>
	</body>
</html>
<script type='text/javascript'>
	enterAsTab(document.forms.filtro);
</script>