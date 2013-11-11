<?
	if($localOrdem == ''){							$localOrdem = "DataVencimento";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}

	LimitVisualizacao("filtro");

	if($localTipoDado == ''){						$localTipoDado = 'number';	}
	
	$localCancelado					=	$_SESSION["filtro_cancelado"];
	$localJuros						=	$_SESSION["filtro_juros"];
	$localSoma						=	$_SESSION["filtro_soma"];
	$localNotaFiscal				=	$_SESSION["filtro_nota_fiscal"];
	$localCPFCNPJ					=	$_SESSION["filtro_cpf_cnpj"];
	$localBoleto					=	$_SESSION["filtro_impressao"];
	$localContaReceberNotaFiscal	=	$_SESSION["filtro_conta_receber_nota_fiscal"];	
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_conta_receber_pagamento_vencimento.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_carne'			value='<?=$local_IdCarne?>' />
			<input type='hidden' name='IdServico'				value='<?=$local_IdServico?>' />
			<table>
				<tr>
					<td><?=dicionario(148)?></td>
					<td><?=dicionario(285)?>.</td>
					<td><?=dicionario(150)?></td>
					<td><?=dicionario(151)?></td>
					<td><?=dicionario(425)?></td>
					<td><?=dicionario(152)?></td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' style='width:160px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeyup='busca_pessoa_aproximada(this,event);' /></td>
					<td>
						<select name='filtro_local_cobranca' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
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
							<option value=''><?=dicionario(153)?></option>
							<option value='IdArquivoRetorno' <?=compara($localCampo,"IdArquivoRetorno","selected='selected'","")?>><?=dicionario(42)?></option>
							<option value='DataLancamento' <?=compara($localCampo,"DataLancamento","selected='selected'","")?>><?=dicionario(409)?>.</option>
							<option value='DataVencimentoOriginal' <?=compara($localCampo,"DataVencimentoOriginal","selected='selected'","")?>><?=dicionario(709)?></option>
							<option value='DataVencimentoAtual' <?=compara($localCampo,"DataVencimentoAtual","selected='selected'","")?>><?=dicionario(710)?></option>
							<option value='IdLancamentoFinanceiro' <?=compara($localCampo,"IdLancamentoFinanceiro","selected='selected'","")?>><?=dicionario(650)?>.</option>
							<option value='MesLancamento' <?=compara($localCampo,"MesLancamento","selected='selected'","")?>><?=dicionario(412)?>.</option>
							<option value='MesVencimentoAtual' <?=compara($localCampo,"MesVencimentoAtual","selected='selected'","")?>><?=dicionario(711)?></option>
							<option value='MesVencimentoOriginal' <?=compara($localCampo,"MesVencimentoOriginal","selected='selected'","")?>><?=dicionario(712)?>.</option>
							<option value='DescricaoServico' <?=compara($localCampo,"DescricaoServico","selected='selected'","")?>><?=dicionario(223)?></option>
							<option value='NumeroDocumento' <?=compara($localCampo,"NumeroDocumento","selected='selected'","")?>>Nº <?=dicionario(713)?></option>
							<option value='IdContaReceber' <?=compara($localCampo,"IdContaReceber","selected='selected'","")?>>Nº <?=dicionario(387)?>.</option>
							<option value='NumeroNF' <?=compara($localCampo,"NumeroNF","selected='selected'","")?>>Nº <?=dicionario(53)?></option>
							<option value='IdReceibo' <?=compara($localCampo,"IdReceibo","selected='selected'","")?>>N° <?=dicionario(708)?></option>
							<option value='IdProcessoFinanceiro' <?=compara($localCampo,"IdProcessoFinanceiro","selected='selected'","")?>><?=dicionario(417)?>.</option>
						</select>
					</td>
					<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:100px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="chama_mascara(this,event)" onKeyDown="listar(event);" /></td>
					<td style='width: 170px;'>
						<select name='filtro_pagamento' style='width:170px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<option value='VencimentoOriginal' <?=compara($local_Pagamento,"VencimentoOriginal","selected='selected'","")?>><?=dicionario(917)?></option>
							<option value='VencimentoAtual' <?=compara($local_Pagamento,"VencimentoAtual","selected='selected'","")?>><?=dicionario(918)?></option>
							<option value='<?=dicionario(919)?>' <?=compara($local_Pagamento,"Vencido","selected='selected'","")?>><?=dicionario(919)?></option>
						</select>
					</td>
					<td>
						<input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/>
					</td>
					<td><input type='submit' value='<?=dicionario(166)?>' class='botao'/></td>
					<td><img onmousemove="quadro_alt(event, this, '<?=dicionario(198)?>');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom'); buscar();" alt='<?=dicionario(167)?>' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_conta_receber_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>
				<tr>
					<td colspan='7'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:80px'><?=dicionario(30)?></td>
									<td>
										<input type='text' name='filtro_id_servico' value='' style='width:55px' maxlength='11' onChange='filtro_buscar_servico(this.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_servico' value='' style='width:276px' readOnly="readOnly" />
									</td>
								</tr>
								<tr>
									<td style='width:80px'><?=dicionario(223)?></td>
									<td><input type='text' value='<?=$localDescricaoServico?>' name='filtro_descrisao_servico' style='width:336px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
								</tr>
								<tr>
									<td style='width:240px'><?=dicionario(112)?></td>
									<td>
										<select name='filtro_tipo_pessoa' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
											<option value=''><?=dicionario(153)?></option>
											<?
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
									<td style='width:240px'><?=dicionario(418)?></td>
									<td>
										<select name='filtro_cancelado' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?
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
									<td><?=dicionario(419)?></td>
									<td>
										<select name='filtro_juros' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?
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
									<td><?=dicionario(714)?></td>
									<td>
										<select name='filtro_soma' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=102 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localSoma,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(421)?></td>
									<td>
										<select name='filtro_nota_fiscal' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?
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
									<td><?=dicionario(715)?></td>
									<td>
										<select name='filtro_cpf_cnpj' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=213 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localCPFCNPJ,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(716)?></td>
									<td>
										<select name='filtro_conta_receber_nota_fiscal' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?
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
								<tr>
									<td><?=dicionario(422)?></td>
									<td>
										<select name='filtro_impressao' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=104 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localBoleto,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
							</table>
							<div style='width:100%; text-align:right; margin:0;'>
								<img onmousemove="quadro_alt(event, this, '<?=dicionario(291)?>');" src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='Parâmetros de Consulta Rápida [Fechar]' />
							</div>
						</div>
					</td>
					<td />
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(19);
		?>
	</div>
	<script>
		function buscar() {
			if(document.filtro.IdServico.value != ''){
				filtro_buscar_servico(document.filtro.IdServico.value);
			}
		}
		
		enterAsTab(document.forms.filtro);
	</script>