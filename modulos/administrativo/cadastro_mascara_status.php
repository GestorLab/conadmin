<?
	$localModulo		= 1;
	$localOperacao		= 158;
	$localSuboperacao	= "V";	
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_Login				= $_SESSION["Login"];
	$local_IdServico	 		= $_REQUEST["IdServico"];
	$local_IdStatus		 		= $_REQUEST["IdStatus"];
	$local_Acao 				= $_POST["Acao"];
	$local_Erro					= $_GET["Erro"];
	$local_PercentualDesconto	= formatText($_POST["PercentualDesconto"],NULL);
	$local_TaxaMudancaStatus	= formatText($_POST["TaxaMudancaStatus"],NULL);
	$local_QtdMinimaDia			= formatText($_POST["QtdMinimaDia"],NULL);
	
	switch($local_Acao) {
		case 'inserir':
			include('files/inserir/inserir_mascara_status.php');
			break;
		case 'alterar':
			include('files/editar/editar_mascara_status.php');
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
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css' />
		<link rel='stylesheet' type='text/css' href='../../css/default.css' />
		
		<script type='text/javascript' src='../../js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/incremental_search.js'></script>
		<script type='text/javascript' src='../../js/mensagens.js'></script>
		<script type='text/javascript' src='../../js/mascara_real.js'></script>
		<script type='text/javascript' src='../../js/prototype.js'></script>
		<script type='text/javascript' src='../../js/scriptaculous.js'></script> 
		<script type='text/javascript' src='../../js/val_data.js'></script> 
		<script type='text/javascript' src='../../js/event.js'></script> 
		<script type='text/javascript' src='js/mascara_status.js'></script>
		<script type='text/javascript' src='js/mascara_status_default.js'></script>
		<script type='text/javascript' src='js/servico_default.js'></script>
		
    	<style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body  onLoad="ativaNome('<?=dicionario(583)?>')">
		<? include('filtro_servico.php'); ?>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo'>	
			<form name='formulario' method='post' action='cadastro_mascara_status.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value='<?=$local_Erro?>'>
				<input type='hidden' name='Local' value='MascaraStatus'>
				<div id='cpDadosServico'>
					<div id='cp_tit' style='margin-top:0'><?=dicionario(435)?></div>	
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='margin-right:34px'><?=dicionario(30)?></b><?=dicionario(223)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(436)?></td>
						</tr>
						<tr>
							<td class='find'><img src='../../img/estrutura_sistema/ico_lupa.gif' alt='Buscar' onClick="vi_id('quadroBuscaServico', true, event, null, 118); busca_servico_lista();"></td>
							<td class='campo'>
								<input type='text' name='IdServico' value=''  style='width:70px' maxlength='11' onChange='busca_servico(this.value,true,document.formulario.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1' /><input type='text' class='agrupador' name='DescricaoServico' value='' style='width:530px' maxlength='100' readOnly />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoServico' style='width:200px' disabled>
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
						</tr>
					</table>
				</div>
				<div>
					<div id='cp_tit' style='margin-top:0'><?=dicionario(583)?> (<?=dicionario(204)?>)</div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(140)?></B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(584)?> (%)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><B><?=dicionario(585)?> (<?=getParametroSistema(5,1)?>)</B></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(586)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<select name='IdStatus' style='width:351px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onchange="busca_mascara_status(document.formulario.IdServico.value,this.value, false);" tabindex='2'>
									<option value=''>&nbsp;</option>
									<?
										$sql="	select
													IdParametroSistema,
													ValorParametroSistema
												from
													ParametroSistema
												where
													IdGrupoParametroSistema = 69 and
													IdParametroSistema != 202
												order by
													ValorParametroSistema;";
										$res = @mysql_query($sql, $con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='PercentualDesconto' value='' style='width:140px' onkeypress='reais(this,event)' onkeydown='backspace(this,event)' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='11' tabindex='3' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='TaxaMudancaStatus' value='' style='width:140px' onkeypress='reais(this,event)' onkeydown='backspace(this,event)' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='15' tabindex='4' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='QtdMinimaDia' value='' style='width:140px' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" maxlength='11' tabindex='5' />
							</td>
						</tr>
					</table>
				</div>
				<div id='Log'>
					<div id='cp_tit'><?=dicionario(571)?></div>
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
								<input type='text' name='LoginCriacao' value='' style='width:180px' maxlength='20' readOnly />
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
								<input type='text' name='DataAlteracao' value='' style='width:203px' readOnly />
							</td>								
						</tr>
					</table>
				</div>
				<div id='cp_botao' style='height:23px'>
					<table style='float:right; margin-right:6px; '>
						<tr>
							<td class='campo'>
								<input type='button' name='bt_inserir' value='<?=dicionario(146)?>' class='botao' tabindex='6' onClick='cadastrar();' />
								<input type='button' name='bt_alterar' value='<?=dicionario(15)?>' class='botao' tabindex='7' onClick='cadastrar();' />
								<input type='button' name='bt_excluir' value='<?=dicionario(147)?>' class='botao' tabindex='8' onClick="excluir(document.formulario.IdServico.value, document.formulario.IdStatus.value);" />
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
				<div id='cp_status_cadastrada'>
					<div id='cp_tit' style='margin-bottom:0'><?=dicionario(587)?></div>
					<table id='tabelaMascaraStatus' class='tableListarCad' cellspacing='0'>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco'><?=dicionario(140)?></td>
							<td class='valor'><?=dicionario(584)?> (%)</td>
							<td class='valor'><?=dicionario(585)?> (<?=getParametroSistema(5,1)?>)</td>
							<td  class='bt_lista'>&nbsp;</td>
						</tr>
						<tr class='tableListarTitleCad'>
							<td class='tableListarEspaco' id='tabelaStatusTotal'><?=dicionario(128)?>: 0</td>
							<td id='tabelaStatusDesconto' class='valor'>0,00</td>
							<td id='tabelaStatusTaxa' class='valor'>0,00</td>
							<td />
						</tr>
					</table>
				</div>
				<table style='display:none' id='tabelahelpText2'>
					<tr>
						<td class='find'>&nbsp;</td>
						<td><h1 id='helpText2' name='helpText2'>&nbsp;</h1></td>
					</tr>
				</table>
			</form>
		</div>
		<div id='quadros_fluantes'>
			<?
				include("files/busca/servico.php");
			?>
		</div>
	</body>	
</html>
<script>
<?
	if($local_IdServico != '' && $local_IdStatus != '') {
		echo "busca_mascara_status($local_IdServico, $local_IdStatus, false, document.formulario.Local.value);";
	} elseif($local_IdServico != '') {
		$sql = "select IdTipoServico from Servico where IdLoja = $local_IdLoja and IdServico = $local_IdServico;";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		if($lin[IdTipoServico] == '1' || $lin[IdTipoServico] == '4'){
			echo "busca_servico($local_IdServico, false, document.formulario.Local.value);";
		}
	}
?>
	verificaAcao();
	inicia();
	verificaErro();
	enterAsTab(document.forms.formulario);
</script>