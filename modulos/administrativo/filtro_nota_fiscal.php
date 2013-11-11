<?
	if($localOrdem == ''){				$localOrdem = "DataEmissao";}
	if($localOrdemDirecao == ''){		$localOrdemDirecao = "descending";}

	LimitVisualizacao("filtro");

	if($localTipoDado == ''){			$localTipoDado = 'number';}
	
	$localAbrirRegistro 			=	$_SESSION['filtro_abrir_registro'];	
	$filtro_ocultar_local_cob		=	$_SESSION['filtro_ocultar_local_cob'];
	$filtro_ocultar_num_conta_rec	=	$_SESSION['filtro_ocultar_num_conta_rec'];
	
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_nota_fiscal.php'>
			<input type='hidden' name='corRegRand'						value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 							value='s' />
			<input type='hidden' name='filtro_ordem'					value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'			value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'					value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_carne'					value='<?=$local_IdCarne?>' />
			<input type='hidden' name='filtro_id_servico_temp'			value='<?=$localIdServico?>' />
			<input type='hidden' name='filtro_ocultar_local_cob'		value='<?=$filtro_ocultar_local_cob?>' />
			<input type='hidden' name='filtro_ocultar_num_conta_rec'	value='<?=$filtro_ocultar_num_conta_rec?>' />
			<table>
				<tr>
					<td>Nome/Razão Social</td>
					<td>Id Nota Fiscal</td>
					<td>Modelo</td>
					<td>Data Iníc. Emissão</td>
					<td>Data Térm. Emissão</td>
					<td>Status</td>
					<td>Qtd.</td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' style='width:120px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeyup='busca_pessoa_aproximada(this,event);' /></td>
					<td><input type='text' value='<?=$localIdNotaFiscal?>' name='filtro_id_nota_fiscal' style='width:80px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td>
						<select name='filtro_nota_fiscal_layout' style='width:120px'  onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdNotaFiscalLayout, DescricaoNotaFiscalLayout from NotaFiscalLayout order by DescricaoNotaFiscalLayout";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									$lin[DescricaoNotaFiscalLayout] = url_string_xsl($lin[DescricaoNotaFiscalLayout],'convert');
									echo"<option value='$lin[IdNotaFiscalLayout]' ".compara($localIdNotaFiscalLayout,$lin[IdNotaFiscalLayout],"selected='selected'","").">$lin[DescricaoNotaFiscalLayout]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localDataInicio?>' name='filtro_data_inicio' style='width:115px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')"  maxlength='10' onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localDataTermino?>' name='filtro_data_termino' style='width:115px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')"  maxlength='10' onKeyDown='listar(event)'/></td>	
					<td>
						<select name='filtro_status' style='width:90px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=143 order by ValorParametroSistema;";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									echo"<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao'/></td>
					<td>
						<img onmousemove="quadro_alt(event, this, 'Menu Avançado');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom'); atualizar_campo();" alt='Parâmetros de Consulta Rápida' />
					</td>
					<td style='font-size:9px; font-weight:normal'>
						<a style='color:#FFF' href='listar_nota_fiscal_opcoes.php'>Outros<BR />Relatórios</a>
					</td>
				</tr>
				<tr>
					<td colspan='9'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:270px'>Abrir Registro (Mesma Janela/Nova Janela)</td>
									<td>
										<select name='filtro_abrir_registro' style='width:150px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?
												if(getCodigoInterno(3,186) == 2){
													$order = "desc";
												}else{
													$order = "asc";
												}
												$sql = "select
															IdParametroSistema,
															ValorParametroSistema
														from
															ParametroSistema
														where
															IdGrupoParametroSistema = 253
														order by
															IdParametroSistema $order";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localAbrirRegistro,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:270px'>Tipo Pessoa</td>
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
									<td><?=dicionario(222)?></td>
									<td>
										<input type='text' name='filtro_id_servico' value='<?php echo $localIdServico; ?>' style='width:70px' maxlength='11' onChange='filtro_buscar_servico(this.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_servico' value='' style='width:369px' readOnly="readOnly" />
									</td>
								</tr>
								<tr>
									<td><?=dicionario(681)?></td>
									<td>
										<input type='text' name='filtro_numero_documento' value='<?php echo $localNumeroDocumento; ?>' style='width:120px' maxlength='15' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(56)?></td>
									<td>
										<input type='text' name='filtro_id_conta_receber' value='<?php echo $localIdContaReceber; ?>' style='width:120px' maxlength='15' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/>
									</td>
								</tr>
								<tr>
									<td><?php echo dicionario(979);?></td>
									<td>
										<select name='filtro_numero_documento_ocultar' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?php 
												if(getCodigoInterno(3,218) == 1){
													$order = "desc";
												}elseif(getCodigoInterno(3,218) == 2){
													$order = "asc";
												}else{
													$order = "";
												}
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=272 order by ValorParametroSistema $order";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localNumeroDocumentoOcultar,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?php echo dicionario(980);?></td>
									<td>
										<select name='filtro_codigo_NF' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?php
												if(getCodigoInterno(3,219) == 1){
													$order = "desc";
												}elseif(getCodigoInterno(3,219) == 2){
													$order = "asc";
												}else{
													$order = "";
												}
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=273 order by ValorParametroSistema $order";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localCodigoNFOcultar,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?php echo dicionario(908);?></td>
									<td>
										<select name='filtro_ocultar_local_cob' style='width: 100px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown='listar(event)'>
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=273 order by ValorParametroSistema $order";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($filtro_ocultar_local_cob,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>	
									</td>
								</tr>
								<tr>
									<td><?php echo dicionario(1014);?></td>
									<td>
										<select name='filtro_ocultar_num_conta_rec' style='width: 100px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown='listar(event)'>
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=273 order by ValorParametroSistema $order";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($filtro_ocultar_num_conta_rec,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>	
									</td>
								</tr>
								<tr>
									<!-- Lançamento Financeiro -->
									<td><?php echo dicionario(671); ?></td>
									<td>
										<input type='text' name='filtro_id_lancamento_financeiro' value='<?php echo $localIdLancamentoFinanceiro; ?>' style='width:120px' maxlength='15' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/>
									</td>
								</tr>
								<tr>
									<!-- Mês Vencimento -->
									<td><?php echo dicionario(508); ?></td>
									<td>
										<input type='text' name='filtro_mes_vencimento' value='<?php echo $localMesVencimento; ?>' style='width:120px' maxlength='7' onkeypress="mascara(this,event,'mes')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/>
									</td>
								</tr>
								<tr>
									<!-- Mês Pagamento -->
									<td><?php echo dicionario(1011); ?></td>
									<td>
										<input type='text' name='filtro_mes_pagamento' value='<?php echo $localMesPagamento; ?>' style='width:120px' maxlength='7' onkeypress="mascara(this,event,'mes')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/>
									</td>
								</tr>
								<tr>
									<!-- Processo Financeiro -->
									<td><?php echo dicionario(682); ?></td>
									<td>
										<input type='text' name='filtro_id_processo_financeiro' value='<?php echo $localIdProcessoFinanceiro; ?>' style='width:120px' maxlength='15' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/>
									</td>
								</tr>
							</table>
							<div style='width:100%; text-align:right; margin:0;'>
								<img onmousemove="quadro_alt(event, this, 'Os filtros do \'Menu Avançado\' ao serem alterados, o valor prevalece selecionado durante a sessão');" src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='Parâmetros de Consulta Rápida [Fechar]' />
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
			echo menu_acesso_rapido(44);
		?>
	</div>
	<script type="text/javascript">
		function atualizar_campo(){
			if(document.filtro.filtro_id_servico.value != ''){
				filtro_buscar_servico(document.filtro.filtro_id_servico_temp.value);
			}
		}
		
		enterAsTab(document.forms.filtro);
	</script>
