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
		<form name='filtro' method='post' action='listar_conta_receber.php'>
			<input type='hidden' name='corRegRand'				value='<?php echo getParametroSistema(15,1); ?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?php echo $localOrdem; ?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?php echo $localOrdemDirecao; ?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?php echo $localTipoDado; ?>' />
			<input type='hidden' name='filtro_carne'			value='<?php echo $local_IdCarne; ?>' />
			<input type='hidden' name='IdServico'				value='<?php echo $local_IdServico; ?>' />
			<table>
				<tr>
					<td><?php echo dicionario(148); ?></td>
					<td><?php echo dicionario(285); ?>.</td>
					<td><?php echo dicionario(150); ?></td>
					<td><?php echo dicionario(151); ?></td>
					<td><?php echo dicionario(140); ?></td>
					<td><?php echo dicionario(152); ?></td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?php echo $localNome; ?>" name='filtro_nome' style='width:160px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeyup='busca_pessoa_aproximada(this,event);' /></td>
					<td>
						<select name='filtro_local_cobranca' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''><?php echo dicionario(153); ?></option>
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
							<option value=''><?php echo dicionario(153); ?></option>
							<option value='IdArquivoRetorno' <?php echo compara($localCampo,"IdArquivoRetorno","selected='selected'",""); ?>><?php echo dicionario(42); ?></option>
							<option value='DataLancamento' <?php echo compara($localCampo,"DataLancamento","selected='selected'",""); ?>><?php echo dicionario(409); ?>.</option>
							<option value='DataVencimentoOriginal' <?php echo compara($localCampo,"DataVencimentoOriginal","selected='selected'",""); ?>><?php echo dicionario(709); ?></option>
							<option value='DataVencimentoAtual' <?php echo compara($localCampo,"DataVencimentoAtual","selected='selected'","")?>><?php echo dicionario(710); ?></option>
							<option value='IdLancamentoFinanceiro' <?php echo compara($localCampo,"IdLancamentoFinanceiro","selected='selected'",""); ?>><?php echo dicionario(650); ?>.</option>
							<option value='MesLancamento' <?php echo compara($localCampo,"MesLancamento","selected='selected'",""); ?>><?php echo dicionario(412); ?>.</option>
							<option value='MesVencimentoAtual' <?php echo compara($localCampo,"MesVencimentoAtual","selected='selected'",""); ?>><?php echo dicionario(711); ?></option>
							<option value='MesVencimentoOriginal' <?php echo compara($localCampo,"MesVencimentoOriginal","selected='selected'",""); ?>><?php echo dicionario(712); ?>.</option>
							<option value='DescricaoServico' <?php echo compara($localCampo,"DescricaoServico","selected='selected'",""); ?>><?php echo dicionario(223); ?></option>
							<option value='NossoNumero' <?php echo compara($localCampo,"NossoNumero","selected='selected'",""); ?>><?php echo dicionario(885); ?></option>
							<option value='NumeroDocumento' <?php echo compara($localCampo,"NumeroDocumento","selected='selected'",""); ?>>Nº <?php echo dicionario(713); ?></option>
							<option value='IdContaReceber' <?php echo compara($localCampo,"IdContaReceber","selected='selected'",""); ?>>Nº <?php echo dicionario(387); ?>.</option>
							<option value='NumeroNF' <?php echo compara($localCampo,"NumeroNF","selected='selected'",""); ?>>Nº <?php echo dicionario(53); ?></option>
							<option value='IdReceibo' <?php echo compara($localCampo,"IdReceibo","selected='selected'",""); ?>>N° <?php echo dicionario(708); ?></option>
							<option value='IdProcessoFinanceiro' <?php echo compara($localCampo,"IdProcessoFinanceiro","selected='selected'",""); ?>><?php echo dicionario(417); ?>.</option>
							<option value='IdArquivoRemessa' <?php echo compara($localCampo,"IdArquivoRemessa","selected='selected'",""); ?>><?php echo dicionario(43); ?></option>
							<option value='DataCancelamento' <?php echo compara($localCampo,"DataCancelamento","selected='selected'",""); ?>><?php echo dicionario(983); ?></option>
							<option value='MesCancelamento' <?php echo compara($localCampo,"MesCancelamento","selected='selected'",""); ?>><?php echo dicionario(982); ?></option>
							<option value='DataExclusao' <?php echo compara($localCampo,"DataExclusao","selected='selected'",""); ?>><?php echo dicionario(984); ?></option>
							<option value='MesExclusao' <?php echo compara($localCampo,"MesExclusao","selected='selected'",""); ?>><?php echo dicionario(985); ?></option>
						</select>
					</td>
					<td><input type='text' name='filtro_valor' value='<?php echo $localValor; ?>' style='width:100px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="chama_mascara(this,event)" onKeyDown="listar(event);" /></td>
					<td>
						<select name='filtro_status' style='width:170px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''><?php echo dicionario(153); ?></option>
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
					<td><input type='submit' value='<?php echo dicionario(166); ?>' class='botao'/></td>
					<td><img onmousemove="quadro_alt(event, this, '<?php echo dicionario(198); ?>');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom'); buscar();" alt='<?php echo dicionario(167); ?>' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_conta_receber_opcoes.php'><?php echo dicionario(120); ?><BR /><?php echo dicionario(162); ?></a></td>
				</tr>
				<tr>
					<td colspan='8'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:280px'><?php echo dicionario(30); ?></td>
									<td>
										<input type='text' name='filtro_id_servico' value='' style='width:75px' maxlength='11' onChange='filtro_buscar_servico(this.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_servico' value='' style='width:322px' readOnly="readOnly" />
									</td>
								</tr>
								<tr>
									<td style='width:280px'><?php echo dicionario(223); ?></td>
									<td><input type='text' value='<?php echo $localDescricaoServico; ?>' name='filtro_descrisao_servico' style='width:402px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
								</tr>
								<tr>
									<td style='width: 240px;'><?php echo dicionario(106); ?></td>
									<td>
										<select name='filtro_grupo_pessoa' style='width:195px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown="listar(event)">
											<option value=''><?php echo dicionario(153); ?></option>
											<?php 
												$where = "";
												
												if($_SESSION["RestringirAgenteAutorizado"] == true){
													$sqlAgente	=	"select 
																		AgenteAutorizado.IdGrupoPessoa 
																	from 
																		AgenteAutorizado
																	where 
																		AgenteAutorizado.IdLoja = $local_IdLoja  and 
																		AgenteAutorizado.IdAgenteAutorizado = '$local_IdPessoaLogin' and 
																		AgenteAutorizado.Restringir = 1 and 
																		AgenteAutorizado.IdStatus = 1 and
																		AgenteAutorizado.IdGrupoPessoa is not null";
													$resAgente	=	@mysql_query($sqlAgente,$con);
													while($linAgente	=	@mysql_fetch_array($resAgente)){
														$where    .=	" and IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
													}
												}
												if($_SESSION["RestringirAgenteCarteira"] == true){
													$sqlAgente	=	"select 
																		AgenteAutorizado.IdGrupoPessoa 
																	from 
																		AgenteAutorizado,
																		Carteira
																	where 
																		AgenteAutorizado.IdLoja = $local_IdLoja  and 
																		AgenteAutorizado.IdLoja = Carteira.IdLoja and
																		AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
																		Carteira.IdCarteira = '$local_IdPessoaLogin' and 
																		AgenteAutorizado.Restringir = 1 and 
																		AgenteAutorizado.IdStatus = 1 and 
																		AgenteAutorizado.IdGrupoPessoa is not null";
													$resAgente	=	@mysql_query($sqlAgente,$con);
													while($linAgente	=	@mysql_fetch_array($resAgente)){
														$where    .=	" and IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
													}
												}
												
												$sql = "select IdGrupoPessoa, DescricaoGrupoPessoa from GrupoPessoa where IdLoja = $local_IdLoja $where order by DescricaoGrupoPessoa ASC";
												$res = mysql_query($sql,$con);
												while($lin = mysql_fetch_array($res)){
													$lin[DescricaoGrupoPessoa] = url_string_xsl($lin[DescricaoGrupoPessoa],'convert');
													echo "<option value='$lin[IdGrupoPessoa]' ".compara($localGrupoPessoa,$lin[IdGrupoPessoa],"selected='selected'","").">$lin[DescricaoGrupoPessoa]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:240px'><?php echo dicionario(112); ?></td>
									<td>
										<select name='filtro_tipo_pessoa' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
											<option value=''><?php echo dicionario(153); ?></option>
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
									<td style='width:240px'><?php echo dicionario(418); ?></td>
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
									<td><?php echo dicionario(419); ?></td>
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
									<td><?php echo dicionario(714); ?></td>
									<td>
										<select name='filtro_soma' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?php 
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
									<td><?php echo dicionario(909); ?></td>
									<td>
										<select name='filtro_subtrair_desconto_conceber' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?php 
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=258 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localSubtrairDesconto,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?php echo dicionario(910); ?></td>
									<td>
										<select name='filtro_contabiliza_recebimentos_vencimento' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?php 
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=258 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localContabilizarRecebimentoVencimento,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?php echo dicionario(421);?></td>
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
									<td><?php echo dicionario(715); ?></td>
									<td>
										<select name='filtro_cpf_cnpj' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?php 
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
									<td><?php echo dicionario(716); ?></td>
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
								<tr>
									<td><?php echo dicionario(422); ?></td>
									<td>
										<select name='filtro_impressao' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?php 
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
								<!--<tr>
									<td>Formato do Relatório</td>
									<td>
										<select name='filtro_tipo_relatorio' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?php 
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=104 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localTipoRelatorio,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										
										</select>
									</td>
								</tr>-->
							</table>
							<div style='width:100%; text-align:right; margin:0;'>
								<img onmousemove="quadro_alt(event, this, '<?php echo htmlespecialchars(dicionario(291)); ?>');" src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='Parâmetros de Consulta Rápida [Fechar]' />
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