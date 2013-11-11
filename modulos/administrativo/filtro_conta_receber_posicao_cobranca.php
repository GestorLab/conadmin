<?php 
	if($localOrdem == ''){							$localOrdem = "DataVencimento";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}

	LimitVisualizacao("filtro");

	if($localTipoDado == ''){						$localTipoDado = 'number';	}
	
	$localCancelado							=	$_SESSION["filtro_cancelado"];
	$localJuros								=	$_SESSION["filtro_juros"];
	$localSoma								=	$_SESSION["filtro_soma"];
	$localNotaFiscal						=	$_SESSION["filtro_nota_fiscal"];
	$localCPFCNPJ							=	$_SESSION["filtro_cpf_cnpj"];
	$localBoleto							=	$_SESSION["filtro_impressao"];
	$localContaReceberNotaFiscal			=	$_SESSION["filtro_conta_receber_nota_fiscal"];	
	$localTipoRelatorio						=	$_SESSION["filtro_tipo_relatorio"];
	$localSubtrairDesconto					=	$_SESSION["filtro_subtrair_desconto_conceber"];
	$localContabilizarRecebimentoVencimento	=	$_SESSION["filtro_contabiliza_recebimentos_vencimento"];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_conta_receber_posicao_cobranca.php'>
			<input type='hidden' name='corRegRand'				value='<?php echo getParametroSistema(15,1); ?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?php echo $localOrdem; ?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?php echo $localOrdemDirecao; ?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?php echo $localTipoDado; ?>' />
			<input type='hidden' name='IdServico'				value='<?php echo $local_IdServico; ?>' />
			<table>
				<tr>
					<td><?=dicionario(148); ?></td>
					<td><?=dicionario(285); ?>.</td>
					<td><?=dicionario(150); ?></td>
					<td><?=dicionario(151); ?></td>
					<td><?=dicionario(140); ?></td>
					<td><?=dicionario(152); ?></td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?php echo $localNome; ?>" name='filtro_nome' style='width:160px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeyup='busca_pessoa_aproximada(this,event);' /></td>
					<td>
						<select name='filtro_local_cobranca' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153); ?></option>
							<?php 
								$sql = "select IdLocalCobranca, AbreviacaoNomeLocalCobranca from LocalCobranca where IdLoja = $local_IdLoja order by DescricaoLocalCobranca";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[AbreviacaoNomeLocalCobranca] = url_string_xsl($lin[AbreviacaoNomeLocalCobranca],'convert');
									
									echo "<option value='$lin[IdLocalCobranca]' ".compara($localIdLocalCobranca,$lin[IdLocalCobranca],"selected='selected'","").">$lin[AbreviacaoNomeLocalCobranca]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:110px'  onKeyDown="listar(event)">
							<option value=''><?=dicionario(153); ?></option>
							<option value='IdArquivoRetorno' <?php echo compara($localCampo,"IdArquivoRetorno","selected='selected'",""); ?>><?=dicionario(42); ?></option>
							<option value='DataLancamento' <?php echo compara($localCampo,"DataLancamento","selected='selected'",""); ?>><?=dicionario(409); ?>.</option>
							<option value='DataVencimentoOriginal' <?php echo compara($localCampo,"DataVencimentoOriginal","selected='selected'",""); ?>><?=dicionario(709); ?></option>
							<option value='DataVencimentoAtual' <?php echo compara($localCampo,"DataVencimentoAtual","selected='selected'","")?>><?=dicionario(710); ?></option>
							<option value='IdLancamentoFinanceiro' <?php echo compara($localCampo,"IdLancamentoFinanceiro","selected='selected'",""); ?>><?=dicionario(650); ?>.</option>
							<option value='MesLancamento' <?php echo compara($localCampo,"MesLancamento","selected='selected'",""); ?>><?=dicionario(412); ?>.</option>
							<option value='MesVencimentoAtual' <?php echo compara($localCampo,"MesVencimentoAtual","selected='selected'",""); ?>><?=dicionario(711); ?></option>
							<option value='MesVencimentoOriginal' <?php echo compara($localCampo,"MesVencimentoOriginal","selected='selected'",""); ?>><?=dicionario(712); ?>.</option>
							<option value='DescricaoServico' <?php echo compara($localCampo,"DescricaoServico","selected='selected'",""); ?>><?=dicionario(223); ?></option>
							<option value='NossoNumero' <?php echo compara($localCampo,"NossoNumero","selected='selected'",""); ?>><?=dicionario(885); ?></option>
							<option value='NumeroDocumento' <?php echo compara($localCampo,"NumeroDocumento","selected='selected'",""); ?>>Nº <?=dicionario(713); ?></option>
							<option value='IdContaReceber' <?php echo compara($localCampo,"IdContaReceber","selected='selected'",""); ?>>Nº <?=dicionario(387); ?>.</option>
							<option value='NumeroNF' <?php echo compara($localCampo,"NumeroNF","selected='selected'",""); ?>>Nº <?=dicionario(53); ?></option>
							<option value='IdReceibo' <?php echo compara($localCampo,"IdReceibo","selected='selected'",""); ?>>N° <?=dicionario(708); ?></option>
							<option value='IdProcessoFinanceiro' <?php echo compara($localCampo,"IdProcessoFinanceiro","selected='selected'",""); ?>><?=dicionario(417); ?>.</option>
							<option value='IdArquivoRemessa' <?php echo compara($localCampo,"IdArquivoRemessa","selected='selected'",""); ?>><?=dicionario(43); ?></option>
							<option value='DataCancelamento' <?php echo compara($localCampo,"DataCancelamento","selected='selected'",""); ?>><?=dicionario(983); ?></option>
							<option value='MesCancelamento' <?php echo compara($localCampo,"MesCancelamento","selected='selected'",""); ?>><?=dicionario(982); ?></option>
							<option value='DataExclusao' <?php echo compara($localCampo,"DataExclusao","selected='selected'",""); ?>><?=dicionario(984); ?></option>
							<option value='MesExclusao' <?php echo compara($localCampo,"MesExclusao","selected='selected'",""); ?>><?=dicionario(985); ?></option>
						</select>
					</td>
					<td><input type='text' name='filtro_valor' value='<?php echo $localValor; ?>' style='width:100px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="chama_mascara(this,event)" onKeyDown="listar(event);" /></td>
					<td>
						<select name='filtro_status' style='width:170px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153); ?></option>
							<?php 
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=35 order by ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
								
								echo "<option value='200' ".compara($localIdStatus,200,"selected='selected'","").">Vencido</option>";
							?>
						</select>
					</td>
					<td>
						<input type='text' value='<?php echo $localLimit; ?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/>
					</td>
					<td><input type='submit' value='<?=dicionario(166); ?>' class='botao'/></td>
					<td><img onmousemove="quadro_alt(event, this, '<?=dicionario(198); ?>');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom'); buscar();" alt='<?=dicionario(167); ?>' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_conta_receber_opcoes.php'><?=dicionario(120); ?><BR /><?=dicionario(162); ?></a></td>
				</tr>
				<tr>
					<td colspan='8'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:280px'><?=dicionario(30); ?></td>
									<td>
										<input type='text' name='filtro_id_servico' value='' style='width:75px' maxlength='11' onChange='filtro_buscar_servico(this.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_servico' value='' style='width:322px' readOnly="readOnly" />
									</td>
								</tr>
								<tr>
									<td style='width:280px'><?=dicionario(223); ?></td>
									<td>
										<input type="text" name="filtro_descrisao_servico" value="<?=$localDescricaoServico; ?>" style="width:402px" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)" />
									</td>
								</tr>
								<tr>
									<td><?=dicionario(1019)?></td>
									<td>
										<select name='filtro_posicao_cobranca' style='width:258px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<option value=''><?=dicionario(153); ?></option>
											<?php 
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=81 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localIdPosicaoCobranca,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(1020); ?></td>
									<td>
										<?=dicionario(1021); ?> <input type='text' value='<?=$localAlteracaoDe;?>' name='filtro_alteracao_de' style='width:100px' onFocus="Foco(this,'in')" onkeypress="mascara(this,event,'date')" maxlength="10"  onBlur="Foco(this,'out')" /> <?=dicionario(745); ?> <input type='text' value='<?=$localAlteracaoAte;?>' name='filtro_alteracao_ate' onkeypress="mascara(this,event,'date')" maxlength="10" style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" />
									</td>
								</tr>
								<tr>
									<td style='width:240px'><?=dicionario(112); ?></td>
									<td>
										<select name='filtro_tipo_pessoa' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
											<option value=''><?=dicionario(153); ?></option>
											<?php 
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=1 order by ValorParametroSistema";
												$res = mysql_query($sql,$con);
												while($lin = mysql_fetch_array($res)){
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													echo "<option value='$lin[IdParametroSistema]' ".compara($localTipoPessoa,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:240px'><?=dicionario(418); ?></td>
									<td>
										<select name='filtro_cancelado' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?php 
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=100 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localCancelado,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(419); ?></td>
									<td>
										<select name='filtro_juros' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?php 
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=101 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localJuros,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>								
								<tr>
									<td><?=dicionario(421);?></td>
									<td>
										<select name='filtro_nota_fiscal' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?php 
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=103 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localNotaFiscal,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>								
								<tr>
									<td><?=dicionario(716); ?></td>
									<td>
										<select name='filtro_conta_receber_nota_fiscal' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?php 
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=173 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localContaReceberNotaFiscal,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
							</table>
							<div style='width:100%; text-align:right; margin:0;'>
								<img onmousemove="quadro_alt(event, this, '<?=dicionario(291); ?>');" src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='Parâmetros de Consulta Rápida [Fechar]' />
							</div>
						</div>
					</td>
					<td />
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?php 
			echo menu_acesso_rapido(19);
		?>
	</div>
	<script type="text/javascript">
		function buscar() {
			if(document.filtro.IdServico.value != ''){
				filtro_buscar_servico(document.filtro.IdServico.value);
			}
		}
		
		enterAsTab(document.forms.filtro);
	</script>