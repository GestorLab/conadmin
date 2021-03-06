<?php
	if($localOrdem == ''){
		$localOrdem = "Nome";
	}
	
	if($localOrdemDirecao == ''){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	if($localTipoDado == ''){
		$localTipoDado = 'text';
	}
	
	LimitVisualizacao("filtro");
	
	$localCancelado					= $_SESSION["filtro_contrato_cancelado"];
	$localSoma						= $_SESSION["filtro_contrato_soma"];
	$localParametroAproximidade		= $_SESSION["filtro_parametro_aproximidade"];
	$localQTDCaracterColunaPessoa	= $_SESSION["filtro_QTDCaracterColunaPessoa"];
	$localOcultaLocalCobranca		= $_SESSION["filtro_oculta_local_cobranca"];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_contrato_periodo_pago.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_ordem2'			value='<?=$localOrdem2?>' />
			<input type='hidden' name='filtro_ordem_direcao2'	value='<?=$localOrdemDirecao2?>' />
			<input type='hidden' name='filtro_tipoDado2'		value='<?=$localTipoDado2?>' />
			<input type='hidden' name='IdServico'				value='<?=$localIdServico?>' />
			<input type='hidden' name='IdPessoa'				value='<?=$localIdPessoa?>' />
			<input type='hidden' name='IdContrato'				value='' />
			<input type='hidden' name='Local'					value='Contrato' />
			<table>
				<tr>
					<td><?=dicionario(148)?></td>
					<td><?=dicionario(40)?></td>
					<td><?=dicionario(150)?></td>
					<td><?=dicionario(303)?></td>
					<td><?=dicionario(304)?></td>
					<td><?=dicionario(140)?></td>
					<td><?=dicionario(152)?></td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localPessoa?>" name='filtro_pessoa' style='width:160px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onKeyDown="listar(event)" onkeyup="busca_pessoa_aproximada(this,event);" /></td>
					<td>
						<select name='filtro_local_cobranca' style='width:120px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select 
											IdLocalCobranca, 
											AbreviacaoNomeLocalCobranca 
										from 
											LocalCobranca 
										where 
											IdLoja = '$local_IdLoja' 
										order by 
											DescricaoLocalCobranca";
								$res = mysql_query($sql,$con);
								
								while($lin = mysql_fetch_array($res)){
									$lin["AbreviacaoNomeLocalCobranca"] = url_string_xsl($lin[AbreviacaoNomeLocalCobranca], "CONVERT");
									
									echo "<option value='".$lin["IdLocalCobranca"]."' ".compara($localIdLocalCobranca, $lin["IdLocalCobranca"], "selected='selected'", "").">".$lin["AbreviacaoNomeLocalCobranca"]."</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_campo' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" style='width:100px' onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<option value='Inadimplente' <?=compara($localCampo, "Inadimplente", "selected='selected'", "")?>><?=dicionario(906)?></option>
							<option value='Quitado' <?=compara($localCampo, "Quitado", "selected='selected'", "")?>><?=dicionario(907)?></option>
						</select>
					</td>
					<td><input type='text' value='<?=$localDataInicio?>' name='filtro_data_inicio' style='width:70px' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
					<td><input type='text' value='<?=$localDataTermino?>' name='filtro_data_termino' style='width:70px' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
					<td>
						<select name='filtro_status' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" style='width:131px' onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?php
								$i = 0;
								$sql = "select 
											IdParametroSistema, 
											ValorParametroSistema 
										from 
											ParametroSistema 
										where 
											IdGrupoParametroSistema = '69' 
										order by 
											ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								
								while($lin = @mysql_fetch_array($res)){
									$vetor[$i] = substituir_string($lin["ValorParametroSistema"])."#".$lin["IdParametroSistema"];
									$i++;
								}
								
								$sql = "select 
											IdParametroSistema, 
											ValorParametroSistema 
										from 
											ParametroSistema 
										where 
											IdGrupoParametroSistema = '113' 
										order by 
											ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								
								while($lin = @mysql_fetch_array($res)){
									$vetor[$i] = substituir_string($lin["ValorParametroSistema"])." (Todos)#G_".$lin["IdParametroSistema"];
									$i++;
								}
								
								sort($vetor);
								
								foreach($vetor as $key => $val) {
									$vet	= explode("#",$val);
									$id		= trim($vet[1]);	
									$value	= trim($vet[0]);	
									
									echo "<option value='$id' ".compara($localIdStatus,$id,"selected='selected'","").">$value</option>";
								}
							?>
						</select>
					</td><td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='<?=dicionario(166)?>'  class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avan�ado')" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick=" consulta_rapida('bottom');atualizar_campo();" alt='Par�metros de Consulta R�pida' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_contrato_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>	
				<tr>
					<td colspan='8'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:300px'><?=dicionario(26)?></td>
									<td>
										<input type='text' name='filtro_id_pessoa' value='<?=$localIdPessoa?>' style='width:70px' maxlength='11' onChange='filtro_buscar_pessoa(this.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_pessoa' value='' style='width:369px' readOnly="readOnly" />
									</td>
								</tr>
								<tr>
									<td><?=dicionario(222)?></td>
									<td>
										<input type='text' name='filtro_id_servico' value='<?=$localIdServico?>' style='width:70px' maxlength='11' onChange='filtro_buscar_servico(this.value,document.filtro.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_servico' value='' style='width:369px' readOnly="readOnly" />
									</td>
								</tr>
								<tr>
									<td><?=dicionario(223)?></td>
									<td><input type='text' value='<?=$localDescricaoServico?>' name='filtro_descricao_servico' style='width:280px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
								</tr>
								<tr>
									<td><?=dicionario(275)?></td>
									<td>
										<select name='filtro_parametro' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:240px' onKeyDown='listar(event)'>
											<option value=''><?=dicionario(153)?></option>
											<?php
												$sql = "select distinct 
															DescricaoParametroServico 
														from 
															ServicoParametro 
														where 
															IdLoja = '$local_IdLoja' 
														order by 
															DescricaoParametroServico";
												$res = mysql_query($sql,$con);
												
												while($lin = mysql_fetch_array($res)){
													$lin["DescricaoParametroServico"] = substituir_string($lin["DescricaoParametroServico"]);
													
													echo "<option value='".$lin["DescricaoParametroServico"]."' ".compara($localParametro, $lin["DescricaoParametroServico"], "selected='selected'", "").">".$lin["DescricaoParametroServico"]."</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(276)?></td>
									<td><input type='text' value='<?=$localValorParametro?>' name='filtro_valor_parametro' style='width:100px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
								</tr>
								<tr>
									<td><?=dicionario(277)?></td>
									<td>
										<select name='filtro_contrato_cancelado' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<?php
												$sql = "select 
															IdParametroSistema, 
															ValorParametroSistema 
														from 
															ParametroSistema 
														where 
															IdGrupoParametroSistema = '105' 
														order by 
															ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												
												while($lin = @mysql_fetch_array($res)){
													$lin["ValorParametroSistema"] = url_string_xsl($lin["ValorParametroSistema"], "CONVERT");
													
													echo "<option value='".$lin["IdParametroSistema"]."' ".compara($localCancelado, $lin["IdParametroSistema"], "selected='selected'", "").">".$lin["ValorParametroSistema"]."</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(278)?></td>
									<td>
										<select name='filtro_contrato_soma' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<?php
												$sql = "select 
															IdParametroSistema, 
															ValorParametroSistema 
														from 
															ParametroSistema 
														where 
															IdGrupoParametroSistema = '106' 
														order by 
															ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												
												while($lin = @mysql_fetch_array($res)){
													$lin["ValorParametroSistema"] = url_string_xsl($lin["ValorParametroSistema"], "CONVERT");
													
													echo "<option value='".$lin["IdParametroSistema"]."' ".compara($localSoma, $lin["IdParametroSistema"], "selected='selected'", "").">".$lin["ValorParametroSistema"]."</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(279)?>.</td>
									<td>
										<select name='filtro_parametro_aproximidade' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<?php
												$sql = "select 
															IdParametroSistema, 
															ValorParametroSistema 
														from 
															ParametroSistema 
														where 
															IdGrupoParametroSistema = '106' 
														order by 
															ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												
												while($lin = @mysql_fetch_array($res)){
													$lin["ValorParametroSistema"] = url_string_xsl($lin["ValorParametroSistema"], "CONVERT");
													
													echo "<option value='".$lin["IdParametroSistema"]."' ".compara($localParametroAproximidade, $lin["IdParametroSistema"], "selected='selected'", "").">".$lin["ValorParametroSistema"]."</option>";
												}
											?>
										</select>
									</td>
								</tr>	
								<tr>
									<td><?=dicionario(226)?></td>
									<td>
										<select name='filtro_tipo' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" style='width:100px' onKeyDown='listar(event)'>
											<option value=''><?=dicionario(153)?></option>
											<?php
												$sql = "select 
															IdParametroSistema, 
															ValorParametroSistema 
														from 
															ParametroSistema 
														where 
															IdGrupoParametroSistema = '28' 
														order by 
															ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												
												while($lin = @mysql_fetch_array($res)){
													$lin["ValorParametroSistema"] = substituir_string($lin["ValorParametroSistema"], "CONVERT");
													
													echo"<option value='".$lin["IdParametroSistema"]."' ".compara($localTipoContrato, $lin[IdParametroSistema], "selected='selected'", "").">".$lin["ValorParametroSistema"]."</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(908)?></td>
									<td>
										<select name='filtro_oculta_local_cobranca' style='width:100px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?php
												$sql = "select 
															IdParametroSistema, 
															ValorParametroSistema 
														from 
															ParametroSistema 
														where 
															IdGrupoParametroSistema = '257' 
														order by 
															ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												
												while($lin = @mysql_fetch_array($res)){
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema], "CONVERT");
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localOcultaLocalCobranca,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(280)?></td>
									<td><input type='text' value='<?=$localQTDCaracterColunaPessoa?>' name='filtro_QTDCaracterColunaPessoa' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" onkeypress="mascara(this,event,'int')" onChange="atualizaSessao(this.name, (this.value == '' ? 0 : this.value));" /></td>
								</tr>
								
								<tr>
									<td style='width:240px'><?=dicionario(157)?></td>
									<td>
										<select name='filtro_estado' style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value); busca_filtro_cidade(this.value);">
											<option value=''></option>
											<?
												$sql = "select IdEstado, SiglaEstado from Estado order by SiglaEstado";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[SiglaEstado] = url_string_xsl($lin[SiglaEstado],'convert');
													
													echo "<option value='$lin[IdEstado]' ".compara($localIdEstado,$lin[IdEstado],"selected='selected'","").">$lin[SiglaEstado]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:240px'><?=dicionario(186)?></td>
									<td>
										<select name='filtro_cidade' style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">											
											<option value=''></option>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:240px'><?=dicionario(160)?></td>
									<td>
										<input type='text' name='filtro_bairro' value='<?=$localBairro?>' style='width:245px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown="listar(event);" />
									</td>
								</tr>
								<tr>
									<td style='width:240px'><?=dicionario(155)?></td>
									<td>
										<input type='text' name='filtro_endereco' value='<?=$localEndereco?>' style='width:245px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown="listar(event);" />
									</td>
								</tr>
							</table>
							<div style='width:100%; text-align:right; margin:0;'>
								<img onmousemove="quadro_alt(event, this, 'Os filtros do \'Menu Avan�ado\' ao serem alterados, o valor prevalece selecionado durante a sess�o');" src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='Par�metros de Consulta R�pida [Fechar]' />
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
			echo menu_acesso_rapido(2);
		?>
	</div>
	<script type='text/javascript'>
		function atualizar_campo(){
			if(document.filtro.IdServico.value != ""){
				filtro_buscar_servico(document.filtro.IdServico.value,document.filtro.Local.value);
			}
			
			if(document.filtro.IdPessoa.value != ""){
				filtro_buscar_pessoa(document.filtro.IdPessoa.value);
			}
			
			if(document.filtro.filtro_estado.value != ""){
				busca_filtro_cidade(document.filtro.filtro_estado.value, "<?=$localIdCidade?>");
			}
		}
		
		atualizar_campo();
		enterAsTab(document.forms.filtro);
	</script>