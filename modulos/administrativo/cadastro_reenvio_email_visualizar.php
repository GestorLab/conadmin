<?
	$localModulo		=	1;
	$localOperacao		=	58;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	
	if($_GET['IdEmail']!=''){
		$local_IdEmail	=	$_GET['IdEmail'];
	}
	if($_GET['IdPessoa']!=''){
		$local_IdPessoa	=	$_GET['IdPessoa'];
	}
	if($_GET['IdContaReceber']!=''){
		$local_IdContaReceber	=	$_GET['IdContaReceber'];
	}
	
	if($localOrdem == ''){							$localOrdem = "DataEnvio";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localLimit == '' && $localFiltro == ''){	$localLimit = getCodigoInterno(7,5);	}
	if($localTipoDado == ''){						$localTipoDado = 'number';	}

	
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
		<script type = 'text/javascript' src = 'js/reenvio_email.js'></script>
		<script type = 'text/javascript' src = 'js/reenvio_email_default.js'></script>
	    <style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body onkeydown="code(event);" onkeyup="code(null);">
		<div id='filtroBuscar'>
			<form name='filtro' method='post'>
				<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
				<input type='hidden' name='filtro' 					value='s' />
				<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
				<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
				<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
				<input type='hidden' name='IdEmail'					value='<?=$local_IdEmail?>' />
				<input type='hidden' name='IdPessoa'				value='<?=$local_IdPessoa?>' />
				<input type='hidden' name='IdContaReceber'			value='<?=$local_IdContaReceber?>' />
				<input type='hidden' name='keyCode'					value='' />
				<table>
					<tr>
						<td><?=dicionario(148)?></td>
						<td><?=dicionario(891)?></td>
						<td><?=dicionario(141)?></td>
						<td><?=dicionario(150)?></td>
						<td><?=dicionario(204)?></td>
						<td><?=dicionario(152)?></td>
						<td />
					</tr>
					<tr>
					<td>
						<input type='text' value='<?=$localNome?>' name='filtro_nome' style='width:170px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar_visualizar(event)'/></td>
						<td>
							<select name='filtro_tipo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar_visualizar(event)' style='width:150px'>
								<option value=''><?=dicionario(153)?></option>
								<?
									$sql = "select distinct IdTipoEmail, DescricaoTipoEmail from TipoEmail order by DescricaoTipoEmail DESC";
									$res = mysql_query($sql,$con);
									while($lin = mysql_fetch_array($res)){
										echo "<option value='$lin[IdTipoEmail]' ".compara($localIdTipoEmail,$lin[IdTipoEmail],"selected='selected'","").">$lin[DescricaoTipoEmail]</option>";
									}
								?>
							</select>
						</td>
						<td>
							<select name='filtro_idstatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar_visualizar(event)' style='width:150px'>
								<option value=''><?=dicionario(153)?></option>
								<?
									$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=37";
									$res = mysql_query($sql,$con);
									while($lin = mysql_fetch_array($res)){
										echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
									}
								?>
							</select>
						</td>
						<td>
							<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:120px'  onKeyDown="listar_visualizar(event)">
								<option value=''><?=dicionario(153)?></option>
								<option value='AssuntoEmail' <?=compara($localCampo,"AssuntoEmail","selected='selected'","")?>><?=dicionario(892)?></option>
								<option value='IdContrato' <?=compara($localCampo,"IdContrato","selected='selected'","")?>><?=dicionario(27)?></option>
								<option value='IdContaReceber' <?=compara($localCampo,"IdContaReceber","selected='selected'","")?>><?=dicionario(889)?></option>
								<option value='IdContaEventual' <?=compara($localCampo,"IdContaEventual","selected='selected'","")?>><?=dicionario(28)?></option>
								<option value='DataEnvio' <?=compara($localCampo,"DataEnvio","selected='selected'","")?>><?=dicionario(720)?></option>
								<option value='Email' <?=compara($localCampo,"Email","selected='selected'","")?>><?=dicionario(104)?></option>
								<option value='IdEmail' <?=compara($localCampo,"IdEmail","selected='selected'","")?>><?=dicionario(893)?></option>
								<option value='IdLancamentoFinanceiro' <?=compara($localCampo,"IdLancamentoFinanceiro","selected='selected'","")?>><?=dicionario(57)?></option>
								<option value='MesEnvio' <?=compara($localCampo,"MesEnvio","selected='selected'","")?>><?=dicionario(722)?></option>
								<option value='IdOrdemServico' <?=compara($localCampo,"IdOrdemServico","selected='selected'","")?>><?=dicionario(427)?></option>
								<option value='IdProcessoFinanceiro' <?=compara($localCampo,"IdProcessoFinanceiro","selected='selected'","")?>><?=dicionario(41)?></option>
								<option value='IdPessoa' <?=compara($localCampo,"IdPessoa","selected='selected'","")?>><?=dicionario(26)?></option>
							
								<!--option value='AssuntoEmail' <?=compara($localCampo,"AssuntoEmail","selected='selected'","")?>>Assunto E-mail</option>
								<option value='IdContaReceber' <?=compara($localCampo,"IdContaReceber","selected='selected'","")?>>Contas Receber</option>
								<option value='DataEnvio' <?=compara($localCampo,"DataEnvio","selected='selected'","")?>>Data Envio</option>
								<option value='MesEnvio' <?=compara($localCampo,"MesEnvio","selected='selected'","")?>>Mês Envio</option>
								<option value='Email' <?=compara($localCampo,"Email","selected='selected'","")?>>E-mail</option>
								<option value='IdEmail' <?=compara($localCampo,"IdEmail","selected='selected'","")?>>Id E-mail</option>
								<option value='IdPessoa' <?=compara($localCampo,"IdPessoa","selected='selected'","")?>>Pessoa</option>
								<option value='IdProcessoFinanceiro' <?=compara($localCampo,"IdProcessoFinanceiro","selected='selected'","")?>>Proc. Financeiro</option-->
							</select>
						</td>
						<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:130px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="chama_mascara(this,event)" onKeyDown="listar_visualizar(event);" /></td>
						<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' size='1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar_visualizar(event)"/></td>
						<td><input type='button' value='Buscar' class='botao' onClick="buscar(this, true, true)"/></td>
					</tr>
				</table>
			</form>
		</div>
		<div id='menu_ar'>
			<ul>
				<li class='color1'><a onClick="buscar(this, true, false);" href="listar_pessoa.php" id='marPessoa'><?=dicionario(26)?></a><a onClick="buscar(this, true, false);" href="cadastro_pessoa.php" style='margin-left: 3px' id='marPessoaNovo' >[+]</a></li>
				<li class='color2'><a onClick="buscar(this, true, false);" href="listar_contrato.php" id='marContrato'><?=dicionario(27)?></a><a onClick="buscar(this, true, false);" href="cadastro_contrato.php" style='margin-left: 3px' id='marContratoNovo'>[+]</a></li>
				<li class='color1'><a onClick="buscar(this, true, false);" href="listar_lancamento_financeiro.php" id='marLancamentoFinanceiro'><?=dicionario(57)?></a><a href='' style='margin-left: 3px' id='marLancamentoFinanceiroNovo'></a></li>
				<li class='color2'><a onClick="buscar(this, true, false);" href="listar_conta_receber.php" id='marContasReceber'><?=dicionario(56)?></a><a href='' style='margin-left: 3px' id='marContasReceberNovo'></a></li>
				<li class='color1'><a onClick="buscar(this, true, false);" href="listar_conta_eventual.php" id='marContaEventual'><?=dicionario(28)?></a><a onClick="buscar(this, true, false);" href="cadastro_conta_eventual.php" style='margin-left: 3px' id='marContaEventualNovo'>[+]</a></li>
				<li class='color2'><a onClick="buscar(this, true, true);" onConTextMenu="buscar(this, false, true);" href="#" id='marReenvioEmail'><?=dicionario(894)?></a><a style='margin-left: 3px' href='#' id='marReenvioEmailNovo'></a></li>
				<li class='color1'><a onClick="buscar(this, true, false);" href="listar_processo_financeiro.php" id='marProcessoFinanceiro'><?=dicionario(41)?></a><a onClick="buscar(this, true, false);" href="cadastro_processo_financeiro.php" style='margin-left: 3px' id='marProcessoFinanceiroNovo'>[+]</a></li>
				<li class='color2'><a onClick="buscar(this, true, false);" href="listar_ordem_servico.php" id='marOrdemServico'><?=dicionario(427)?></a><a onClick="buscar(this, true, false);" href="cadastro_ordem_servico.php" style='margin-left: 3px' id='marOrdemServicoNovo'>[+]</a></li>
			</ul>
		</div>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo' style='margin:0'>
			<form name='formulario' method='post'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='ReenvioEmailVisualizar'>
				<table>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'><?=dicionario(893)?></td>
					</tr>
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='IdEmail' value='' autocomplete="off" style='width:70px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_reenvio_email(this.value,false,document.formulario.Local.value)" onkeypress="mascara(this,event,'int')" tabindex='1'>
						</td>
					</tr>
				</table>
				<div id='cp_tit'><?=dicionario(895)?></div>
					<table id='cp_juridica'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:36px'><?=dicionario(26)?></B><?=dicionario(85)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(670)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(891)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdPessoa' value='' autocomplete="off" style='width:70px' maxlength='11' readOnly><input type='text' class='agrupador' name='Nome' value='' style='width:306px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdContaReceber' value='' autocomplete="off" style='width:90px' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='IdTipoEmail' style='width:318px'  onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" disabled>
									<option value='' selected></option>
									<?
										$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=37 order by IdParametroSistema";
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
							<td class='descCampo'>E-mail</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Envio</td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Hora Envio</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Re-Envio E-mail</td>
							<td class='separador' colspan='2'>&nbsp;</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Email' value='' style='width:504px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataEnvio' style='width:80px' value='' readOnly>
							</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='HoraEnvio' style='width:70px' value='' autocomplete="off" maxlength='5' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdEmailReEnvio' style='width:90px' value='' autocomplete="off" maxlength='11' readOnly>
							</td>						
						</tr>
					</table>
					<HR style='color:#004492'>
				</div>
			</form>
		</div>
	</body>
</html>
<script>
<?
	if($local_IdEmail!=''){
		echo "busca_reenvio_email($local_IdEmail,false);";		
	}
?>
	inicia();
	function inicia(){
		document.formulario.IdEmail.focus();
	}
	
	enterAsTab(document.forms.formulario);
</script>
