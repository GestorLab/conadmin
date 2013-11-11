<?php
	$localModulo		= 1;
	$localOperacao		= 178;
	$localSuboperacao	= "V";
	$Path				= "../../"; 
	
	include("../../files/conecta.php");
	include("../../files/funcoes.php");
	include("../../rotinas/verifica.php");
	include("../../classes/envia_mensagem/envia_mensagem.php");
	
	$local_Login				= $_SESSION["Login"];
	$local_IdLoja				= $_SESSION["IdLoja"];
	$local_Erro					= $_GET["Erro"];
	$local_Acao 				= $_POST["Acao"];
	$local_IdCaixa				= $_POST["IdCaixa"];
	$local_IdCaixaMovimentacao	= $_POST["IdCaixaMovimentacao"];
	$local_TipoMovimentacao		= $_POST["TipoMovimentacaoTemp"];
	$local_ContasReceber		= $_POST["ContasReceber"];
	$local_ItemMax				= $_POST["ItemMax"];
	$local_Obs					= $_POST["Obs"];
	$local_IdStatus				= $_POST["IdStatus"];
	$local_IdContaReceber		= $_POST["IdContaReceber"];
	
	if($local_IdCaixa == ''){
		$local_IdCaixa = $_GET["IdCaixa"];
	}
		
	if($local_IdCaixaMovimentacao == ''){
		$local_IdCaixaMovimentacao = $_GET["IdCaixaMovimentacao"];
	}
	
	if(empty($local_IdCaixa)) {
		$sql = "SELECT 
					IdCaixa 
				FROM
					Caixa 
				WHERE 
					IdLoja = $local_IdLoja AND
					IdStatus = 1 AND
					LoginAbertura = '$local_Login'
				LIMIT 1;";
		$res = @mysql_query($sql, $con);
		
		if(mysql_num_rows($res) > 0){
			$lin = @mysql_fetch_array($res);
			$local_IdCaixa = $lin["IdCaixa"];
		} else{
			header("Location: cadastro_caixa.php");
		}
	}
	
	switch($local_Acao){
		case "inserir":
			include("files/inserir/inserir_caixa_movimentacao.php");
			break;
			
		case "cancelar":
			include("files/editar/editar_caixa_movimentacao_cancelar.php");
			break;
			
		default:
			$local_Acao = "inserir";
	}
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
		<script type='text/javascript' src='../../js/prototype.js'></script>
		<script type='text/javascript' src='../../js/scriptaculous.js'></script> 
		<script type='text/javascript' src='../../js/event.js'></script> 
		<script type='text/javascript' src='js/caixa_movimentacao.js'></script>
		<script type='text/javascript' src='js/caixa_movimentacao_default.js'></script>
		<script type='text/javascript' src='js/caixa_default.js'></script>
		
		<style type='text/css'>
			#tit_Status {
				width: 151px;
				text-align: right; 
			}
		</style>
	</head>
	<body onLoad="ativaNome('<?php echo dicionario(911); ?>')">
		<?php include('filtro_caixa_movimentacao.php'); ?>
		<div id='conteudo'>
			<form name='formulario' method='post' action='cadastro_caixa_movimentacao.php' onSubmit='return validar()'>
				<input type='hidden' name='Acao' value='<?php echo $local_Acao; ?>' />
				<input type='hidden' name='Erro' value='<?php echo $local_Erro; ?>' />
				<input type='hidden' name='Local' value='Movimentacao' />
				<input type='hidden' name='Moeda' value='<?php echo getParametroSistema(5, 1); ?>' />
				<input type='hidden' name='IdTipoMovimentacaoDefault' value='<?php echo getCodigoInterno(3, 181); ?>' />
				<input type='hidden' name='PermisaoCancelar' value='<?php echo (permissaoSubOperacao($localModulo, $localOperacao, "C") ? 1 : 0); ?>'>
				<input type='hidden' name='QTDItems' value='0' />
				<input type='hidden' name='ItemMax' value='0' />
				<input type='hidden' name='ManipulaItem' value='' />
				<input type='hidden' name='ContasReceber' value='0' />
				<input type='hidden' name='TabIndex' value='3' />
				<input type='hidden' name='IdStatus' value='200' />
				<input type='hidden' name='Executando' value='0' />
				<input type='hidden' name='IdContaReceberItemTemp' value='<?php echo $local_IdContaReceber; ?>' />
				<input type="hidden" name="QuantidadeTituloEstorno" value="<?=getCodigoInterno(3,244)?>"/>
				<div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?php echo dicionario(171); ?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?php echo dicionario(388); ?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?php echo dicionario(488); ?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><?php echo dicionario(911); ?></td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'><b id="tit_TipoMovimentacao"><?php echo dicionario(732); ?></b></td>
							<td class='separador'>&nbsp;</td>
							<td class='descricao' rowspan='2' id='tit_Status'></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdCaixa' value='' style='width:70px' maxlength='11' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataAbertura' value='' style='width:121px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='NomeResponsavel' value='' style='width:181px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='IdCaixaMovimentacao' value='' autocomplete="off" style='width:80px' maxlength='11' onChange="busca_caixa_movimentacao(document.formulario.IdCaixa.value, this.value, true, document.formulario.Local.value);" onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in','auto')"  onBlur="Foco(this,'out')" tabindex='1'>
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<select name='TipoMovimentacao' style='width:138px' onChange="tipo_movimentacao(this.value);zera_ResultadoTotal()" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" tabindex='2'>
									<?php
										$sql = "SELECT
													IdParametroSistema,
													ValorParametroSistema
												FROM 
													ParametroSistema
												WHERE
													IdGrupoParametroSistema = 244 
												ORDER BY
													ValorParametroSistema;";
										$res = @mysql_query($sql,$con);
										
										while($lin = @mysql_fetch_array($res)){
											echo"<option value='$lin[IdParametroSistema]'>$lin[ValorParametroSistema]</option>";
										}
									?>
								</select>
								<input type="hidden" name="TipoMovimentacaoTemp" value="" />
							</td>
							<td class='separador'>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div id='bl_Itens'></div>
				<div>
					<div id='cp_tit'><?php echo dicionario(128); ?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?php echo dicionario(204); ?> (<?php echo getParametroSistema(5, 1); ?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(+) <?php echo dicionario(912); ?> (<?php echo getParametroSistema(5, 1); ?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(-) <?php echo dicionario(579); ?> (<?php echo getParametroSistema(5, 1); ?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>(=) <?php echo dicionario(206); ?> (<?php echo getParametroSistema(5, 1); ?>)</td>
							<td class='separador'>&nbsp;</td>
							<td class='descCampo'>Data Movimentação</td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='TotalValor' value='0,00' style='width:149px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='TotalValorMultaJuros' value='0,00' style='width:149px' readonly='readonly' />
								<input type='hidden' name='TotalValorMulta' value='0,00' style='width:149px' readonly='readonly' />
								<input type='hidden' name='TotalValorJuros' value='0,00' style='width:150px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='TotalValorDesconto' value='0,00' style='width:150px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='TotalValorFinal' value='0,00' style='width:150px' readonly='readonly' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo'>
								<input type='text' name='DataHoraCriacao' style='width:150px' readonly='readonly' />
							</td>
						</tr>
					</table>
				</div>
				<div id='bl_FormaPagamento' style='display:none;'></div>
				<div id='bl_Historico' style='display:none;'>
					<div id='cp_tit'><?php echo dicionario(130); ?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><?php echo dicionario(130); ?></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='ObsHistorico' style='width:816px;' rows='5' readonly='readonly'></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div id='bl_Observacao' style='display:none;'>
					<div id='cp_tit'><?php echo dicionario(159); ?></div>
					<table>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='descCampo'><b id='tit_Observacao'><?php echo dicionario(159); ?></b></td>
						</tr>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<textarea name='Obs' style='width:816px;' rows='3' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" tabindex='10001'></textarea>
							</td>
						</tr>
					</table>
				</div>
				<div class='cp_botao'>
					<table style='width:848px;'>
						<tr>
							<td class='find'>&nbsp;</td>
							<td class='campo'>
								<input type='button' class='botao' style='width:67px;' name='bt_concluir' value='<?php echo dicionario(914); ?>' onClick="concluir(true);" tabindex='10002' />
								<input type='button' class='botao' style='width:67px; display:none;' name='bt_recibo' value='<?php echo dicionario(708); ?>' onClick="cadastrar('recibo');" tabindex='10002' />
							</td>
							<td class='separador'>&nbsp;</td>
							<td class='campo' style='text-align:right; width:250px;'>
								<input type='button' class='botao' style='width:78px; display:inline;' name='bt_item' value='<?php echo dicionario(915); ?>' onClick="add_item()" tabindex='10003' />
								<input type='button' class='botao' style='width:78px; display:none;' name='bt_receber' value='<?php echo dicionario(916); ?>' onClick="cadastrar('inserir');" tabindex='10004' />
								<input type='button' class='botao' style='width:78px; display:none;' name='bt_cancelar' value='<?php echo dicionario(405); ?>' onClick="cadastrar('cancelar');" tabindex='10005' />
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
					</table>
				</div>
			</form>
		</div>
		<div id='quadros_fluantes'>
			<?php
				include("files/busca/conta_receber.php");
			?>
		</div>
		<script type="text/javascript">
		<?php
			if($local_IdCaixa != ""){
				echo "busca_caixa($local_IdCaixa, false, document.formulario.Local.value);";
			}
			
			if($local_IdCaixaMovimentacao != ""){
				echo "busca_caixa_movimentacao($local_IdCaixa, $local_IdCaixaMovimentacao, false, document.formulario.Local.value);";
			}
		?>
			verificaErro();
			verificaAcao();
			inicia();
			enterAsTab(document.forms.formulario);
			buscar_status();
		</script>
	</body>
</html>