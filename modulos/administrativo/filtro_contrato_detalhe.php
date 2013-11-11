<?	
	LimitVisualizacao("filtro");
	
	$localCancelado			=	$_SESSION["filtro_contrato_cancelado"];
	$localIdLocalCobranca	=	$_SESSION["filtro_local_cobranca"];
	$localOrdenarPor		=	$_SESSION["filtro_ordenar_por"];
	$localContratoParametro	=	$_SESSION["filtro_contrato_parametro"];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_contrato_detalhe.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='IdServico'				value='<?=$localIdServico?>' />
			<input type='hidden' name='IdPessoa'				value='<?=$localIdPessoa?>' />
			<input type='hidden' name='Local'					value='ContratoDetalhe' />
			<table>
				<tr>
					<td><?=dicionario(148)?></td>
					<td><?=dicionario(223)?></td>
					<td><?=dicionario(275)?></td>
					<td><?=dicionario(276)?></td>
					<td><?=dicionario(140)?></td>
					<td><?=dicionario(152)?></td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localPessoa?>" name='filtro_pessoa' style='width:160px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" onkeyup="busca_pessoa_aproximada(this,event);"/></td>
					<td><input type='text' value='<?=$localDescricaoServico?>' name='filtro_descricao_servico' style='width:85px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td>
						<select name='filtro_parametro' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:120px' onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select distinct DescricaoParametroServico from ServicoParametro where IdLoja = $local_IdLoja order by DescricaoParametroServico";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[DescricaoParametroServico] = substituir_string($lin[DescricaoParametroServico]);
									echo "<option value=\"$lin[DescricaoParametroServico]\" ".compara($localDescricaoParametroServico,$lin[DescricaoParametroServico],"selected='selected'","").">$lin[DescricaoParametroServico]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localValorParametroServico?>' name='filtro_valor_parametro' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
					<td>
						<select name='filtro_status' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:165px' onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								
								$i	=	0;
								
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=69 order by ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									$vetor[$i]	=	substituir_string($lin[ValorParametroSistema])."#".$lin[IdParametroSistema];
									$i++;
								}
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=113 order by ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									$vetor[$i]	=	substituir_string($lin[ValorParametroSistema])." (Todos)#G_".$lin[IdParametroSistema];
									$i++;
								}
								
								sort($vetor);
								
								foreach ($vetor as $key => $val) {
									$vet	=	explode("#",$val);
									$id		=	trim($vet[1]);	
									$value	=	trim($vet[0]);	
									
									echo "<option value='$id' ".compara($localIdStatus,$id,"selected='selected'","").">$value</option>";
								}

							?>
						</select>
					</td><td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avançado');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom');atualizar_campo();" alt='Parâmetros de Consulta Rápida' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_contrato_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>	
				<tr>
					<td colspan='8'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:280px'><?=dicionario(26)?></td>
									<td>
										<input type='text' name='filtro_id_pessoa' value='' style='width:70px' maxlength='11' onChange='filtro_buscar_pessoa(this.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_pessoa' value='' style='width:389px' readOnly="readOnly" />
									</td>
								</tr>
								<tr>
									<td style='width:280px'><?=dicionario(30)?></td>
									<td>
										<input type='text' name='filtro_id_servico' value='' style='width:70px' maxlength='11' onChange='filtro_buscar_servico(this.value,document.filtro.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_servico' value='' style='width:389px' readOnly="readOnly" />
									</td>
								</tr>
								<tr>
									<td style='width:280px'><?=dicionario(277)?></td>
									<td>
										<select name='filtro_contrato_cancelado' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=105 order by ValorParametroSistema";
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
									<td style='width:280px'><?=dicionario(317)?></td>
									<td>
										<select name='filtro_contrato_parametro' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=105 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localContratoParametro,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>								
								<tr>
									<td style='width:280px'><?=dicionario(40)?></td>
									<td>
										<select name='filtro_local_cobranca' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
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
								</tr>
								<tr>
									<td style='width:280px'><?=dicionario(175)?></td>
									<td>
										<select name='filtro_ordenar_por' style='width:100px;' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value); filtroConsultarPor(this.value)">
										<option value=''><?=dicionario(153)?></option>
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=164 order by IdParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localOrdenarPor,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:280px;'><font id='titConsultarPor' style='display:none'>Consultar por</font></td>
									<td>										
										<input type='text' id='cpConsultarPor' value='<?=$localConsultarPor?>' name='filtro_consultar_por' style='width:100px; display:none' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" onChange="atualizaSessao(this.name,this.value)"/>
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
			echo menu_acesso_rapido(19);
		?>
	</div>
	<script type='text/javascript'>	
		function atualizar_campo(){
			if(document.filtro.IdServico.value != ''){
				filtro_buscar_servico(document.filtro.IdServico.value,document.filtro.Local.value);
			}
			if(document.filtro.IdPessoa.value != ''){
				filtro_buscar_pessoa(document.filtro.IdPessoa.value);
			}
			filtroConsultarPor(document.filtro.filtro_ordenar_por.value);
		}	
		enterAsTab(document.forms.filtro);	
	</script>
