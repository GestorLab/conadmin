<?
	$localModulo		=	1;
	$localOperacao		=	27;
	$localSuboperacao	=	"V";	
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_Acao 			= $_POST['Acao'];
	$local_Erro				= $_GET['Erro'];
	
	
	if($_GET['IdHistoricoMensagem']!=''){
		$local_IdHistoricoMensagem	=	$_GET['IdHistoricoMensagem'];
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
	
	switch($local_Acao){
		case 'alterar':
			include('files/editar/editar_enviar_mensagem.php');
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
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = 'js/reenvio_mensagem.js'></script>
		<script type = 'text/javascript' src = 'js/reenvio_mensagem_default.js'></script>
		
	    <style type="text/css">
			input[type=text]:readOnly  		{ background-color: #FFF; }
			input[type=datetime]:readOnly  	{ background-color: #FFF; }
			input[type=date]:readOnly  		{ background-color: #FFF; }
			textarea:readOnly  				{ background-color: #FFF; }
			select:disabled  { background-color: #FFF; }
			select:disabled  { color: #000; }
		</style>
	</head>
	<body>
		<div id='filtroBuscar'>
			<form name='filtro' method='post' action='listar_reenvio_mensagem.php' target='_parent'>
				<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
				<input type='hidden' name='filtro' 					value='s' />
				<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
				<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
				<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
				<input type='hidden' name='IdHistoricoMensagem'		value='<?=$local_IdHistoricoMensagem?>' />
				<input type='hidden' name='IdPessoa'				value='<?=$local_IdPessoa?>' />
				<input type='hidden' name='IdContaReceber'			value='<?=$local_IdContaReceber?>' />
				<input type='hidden' name='keyCode'					value='' />					
				<table>	
					<tr>
						<td><?=dicionario(148)?></td>
						<td><?=dicionario(718)?></td>
						<td><?=dicionario(150)?></td>
						<td><?=dicionario(151)?></td>
						<td><?=dicionario(140)?></td>
						<td><?=dicionario(152)?></td>
						<td />
					</tr>
					<tr>
						<td><input type='text' value='<?=$localNome?>' name='filtro_nome' style='width:170px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)" onkeyup="busca_pessoa_aproximada(this,event);"/></td>
						<td>
							<select name='filtro_tipo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' style='width:130px'>
								<option value=''><?=dicionario(153)?></option>
								<?
									$sql = "select 
												IdTipoMensagem, 
												Titulo
											from 
												TipoMensagem 
											where
												IdLoja = $local_IdLoja and
												Titulo is not null 
											order by 
												Titulo ASC";
									$res = mysql_query($sql,$con);
									while($lin = mysql_fetch_array($res)){
										eval("\$lin[Titulo] = \"".$lin[Titulo]."\";");
										
										$lin[Titulo] = url_string_xsl($lin[Titulo],'convert');
										$lin[Titulo] = preg_replace("/\([ ]*\)/",'',$lin[Titulo]);
										
										if($lin[IdTipoMensagem] == 3){ // modificar depois, temporario
											$lin[Titulo] = 'Log Processo Financeiro';	
										}
										
										echo "<option value='$lin[IdTipoMensagem]' ".compara($localIdTipoMensagem,$lin[IdTipoMensagem],"selected='selected'","").">$lin[Titulo]</option>";
									}
								?>
							</select>
						</td>
						<td>
							<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:120px'  onKeyDown="listar(event)">
								<option value=''><?=dicionario(153)?></option>
								<option value='Assunto' <?=compara($localCampo,"Assunto","selected='selected'","")?>><?=dicionario(719)?></option>
								<option value='IdContrato' <?=compara($localCampo,"IdContrato","selected='selected'","")?>><?=dicionario(27)?></option>
								<option value='IdContaReceber' <?=compara($localCampo,"IdContaReceber","selected='selected'","")?>><?=dicionario(56)?></option>
								<option value='IdContaEventual' <?=compara($localCampo,"IdContaEventual","selected='selected'","")?>><?=dicionario(28)?></option>
								<option value='DataEnvio' <?=compara($localCampo,"DataEnvio","selected='selected'","")?>><?=dicionario(720)?></option>
								<option value='DataCriacao' <?=compara($localCampo,"DataCriacao","selected='selected'","")?>><?=dicionario(133)?></option>
								<option value='Email' <?=compara($localCampo,"Email","selected='selected'","")?>><?=dicionario(104)?></option>
								<option value='IdHistoricoMensagem' <?=compara($localCampo,"IdHistoricoMensagem","selected='selected'","")?>><?=dicionario(721)?></option>
								<option value='IdLancamentoFinanceiro' <?=compara($localCampo,"IdLancamentoFinanceiro","selected='selected'","")?>><?=dicionario(57)?></option>
								<option value='MesEnvio' <?=compara($localCampo,"MesEnvio","selected='selected'","")?>><?=dicionario(722)?></option>
								<option value='IdOrdemServico' <?=compara($localCampo,"IdOrdemServico","selected='selected'","")?>><?=dicionario(427)?></option>
								<option value='IdProcessoFinanceiro' <?=compara($localCampo,"IdProcessoFinanceiro","selected='selected'","")?>><?=dicionario(41)?></option>
								<option value='IdPessoa' <?=compara($localCampo,"IdPessoa","selected='selected'","")?>><?=dicionario(26)?></option>
							</select>
						</td>
						<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:130px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="chama_mascara(this,event)"  onKeyDown="listar(event);" /></td>
						<td>
							<select name='filtro_idstatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)" style='width:150px'>
								<option value=''><?=dicionario(153)?></option>
								<?
									$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=193";
									$res = mysql_query($sql,$con);
									while($lin = mysql_fetch_array($res)){
										
										$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
										
										echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
									}
								?>
							</select>
						</td>
						<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
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
				<li class='color2'><a onClick="buscar(this, true, true);" onConTextMenu="buscar(this, false, true);" href="#" id='marReenvioMensagem'><?=dicionario(58)?></a><a style='margin-left: 3px' href='#' id='marReenvioMensagemNovo'></a></li>
				<li class='color1'><a onClick="buscar(this, true, false);" href="listar_processo_financeiro.php" id='marProcessoFinanceiro'><?=dicionario(41)?></a><a onClick="buscar(this, true, false);" href="cadastro_processo_financeiro.php" style='margin-left: 3px' id='marProcessoFinanceiroNovo'>[+]</a></li>
				<li class='color2'><a onClick="buscar(this, true, false);" href="listar_ordem_servico.php" id='marOrdemServico'><?=dicionario(427)?></a><a onClick="buscar(this, true, false);" href="cadastro_ordem_servico.php" style='margin-left: 3px' id='marOrdemServicoNovo'>[+]</a></li>
			</ul>
		</div>
		<div id='carregando'><?=dicionario(17)?></div>
		<div id='conteudo' style='margin:0'>
			<form name='formulario' method='post'>
				<input type='hidden' name='Acao' value='<?=$local_Acao?>'>
				<input type='hidden' name='Erro' value=<?=$local_Erro?>>
				<input type='hidden' name='Local' value='ReenvioMensagemVisualizar'>
				<table >
					<tr>
						<td class='find'>&nbsp;</td>
						<td class='descCampo'><?=dicionario(721)?></td>
					</tr>
					<tr >
						<td class='find'>&nbsp;</td>
						<td class='campo'>
							<input type='text' name='IdHistoricoMensagem' value='' autocomplete="off" style='width:70px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="parent.location = 'cadastro_reenvio_mensagem.php?IdHistoricoMensagem='+this.value;busca_reenvio_mensagem(this.value,false,document.formulario.Local.value)" onkeypress="mascara(this,event,'int')" tabindex='1'>
						</td>
                        <td class='find'>&nbsp;</td>
						<td class='descricao' style='width:500px; text-align: right; padding-left: 226px;'><B id='cp_Status'>&nbsp;</B></td>
					</tr>
				</table>
				<div id='cp_tit'><?=dicionario(723)?></div>
					<table id='cp_juridica'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><B style='color:#000; margin-right:36px'><?=dicionario(26)?></B><?=dicionario(85)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(670)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(718)?></td>
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
								<select name='IdTipoMensagem' style='width:324px'  onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" disabled>
									<option value='' selected></option>
									<?
										$sql = "select IdTipoMensagem, Titulo from TipoMensagem where IdLoja = $local_IdLoja order by IdTipoMensagem";
										$res = @mysql_query($sql,$con);
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdTipoMensagem]'>$lin[Titulo]</option>";
										}
									?>
								</select>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo' id='destinatario'><?=dicionario(104)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(132)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(133)?></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(720)?></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(724)?></td>	
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='Destinatario' value='' style='width:288px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='LoginCriacao' value='' style='width:183px' maxlength='100' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataCriacao' value='' style='width:82px' maxlength='10' readOnly>
							</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataEnvio' style='width:82px' value='' readOnly>
							</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='HoraEnvio' style='width:76px' value='' autocomplete="off" maxlength='5' readOnly>
							</td>						
						</tr>
					</table>
					<table id='no_sms_view'>
						<tr>
							<td class='find'>&nbsp;</td>							
							<td class='descCampo'><?=dicionario(725)?></td>
							<td class='find'>&nbsp;</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(726)?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(727)?></td>
							<td class='separador'>&nbsp;</td>	
							<td class='descCampo'><?=dicionario(719)?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataLeitura' style='width:80px' value='' readOnly>
							</td>
							<td class='find'><img src='../../img/estrutura_sistema/ico_date_c.gif' alt='Buscar data'></td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='HoraLeitura' style='width:70px' value='' autocomplete="off" maxlength='5' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IPLeitura' style='width:100px' value='' autocomplete="off" readOnly>
							</td>
							<td class='separador'>&nbsp;</td>							
							<td class='campo'>
								<input type='text' name='Assunto' style='width:500px' value='' autocomplete="off" readOnly>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(159)?></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='Obs' style='width:823px;' rows='3' tabindex='1000' readOnly></textarea>
							</td>
						</tr>
					</table>
					<table id='mensagem_sms' style='display:none'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(58)?></td>				
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='Mensagem' style='width:823px;' rows='3' tabindex='1001' readOnly></textarea>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?=dicionario(728)?>.</td>						
							<td class='separador'>&nbsp;</td>
							<td id="QtdTentativaEnvio1" style="display: none;" class='descCampo'><?=dicionario(1047)?></td>
							<td class='separador' colspan='2'>&nbsp;</td>						
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdReEnvio' style='width:90px' value='' autocomplete="off" maxlength='11' readOnly>
							</td>
							<td class='separador'>&nbsp;</td>
							<td id="QtdTentativaEnvio2" style="display: none;" class='campo'>
								<input type='text' name='QtdTentativaEnvio' style='width:110px' value='' autocomplete="off" maxlength='11' readOnly><br>
							</td>
							<td class='separador'></td>
							<td class='campo'>
								<input name="bt_reenviar" style='width:150px' type='button' value='<?=dicionario(729)?>?' class='botao' onClick="reenviar_mensagem(document.formulario.IdHistoricoMensagem.value)"/>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='button' id="NaoEnviarEmail" style="display: none;" value='<?=dicionario(1048)?>' class='botao' onClick="nao_enviar_email(document.formulario.IdHistoricoMensagem.value)" />
							</td>
						</tr>						
						<tr>
							<td class='find'>&nbsp;</td>
							<td/>
							<td class='separador'>&nbsp;</td>
							<td id="QtdMaxima" style="display: none;"></td>
							<td class='separador'>&nbsp;</td>
							<td/>
						</tr>
					</table>
					<HR style='color:#004492' id='hr'>
				</div >
				<div>	
					<table style='width:100%;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 style='margin-bottom:0' id='helpText' name='helpText'>&nbsp;</h1></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td><h1 style='margin-bottom:0' id='helpText2' name='helpText2'>&nbsp;</h1></td>
						</tr>
					</table>
				</div>
			</form>
		</div>
	</body>
</html>
<script>
<?
	if($local_IdHistoricoMensagem!=''){
		echo "busca_reenvio_mensagem($local_IdHistoricoMensagem,false);";		
	}
?>
	function inicia(){
		document.formulario.IdHistoricoMensagem.focus();
	}
	inicia();
	
	enterAsTab(document.forms.formulario);
	enterAsTab(document.forms.filtro);
</script>