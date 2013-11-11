<?
	if($localOrdem == ''){					$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
	
	LimitVisualizacao("filtro");
	
	$localCancelado					=	$_SESSION["filtro_contrato_cancelado"];
	$localSoma						=	$_SESSION["filtro_contrato_soma"];
	$localDataBase					=   $_SESSION["filtro_data_base"];
	$localDataBase					=   $_SESSION["filtro_data_base"];	
	$localDataBaseNula				=   $_SESSION["filtro_data_base_nula"];	
	$localQTDCaracterColunaPessoa	=	$_SESSION["filtro_QTDCaracterColunaPessoa"];
	
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_contrato_periodicidade.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_ordem2'			value='<?=$localOrdem2?>' />
			<input type='hidden' name='filtro_ordem_direcao2'	value='<?=$localOrdemDirecao2?>' />
			<input type='hidden' name='filtro_tipoDado2'		value='<?=$localTipoDado2?>' />
			<input type='hidden' name='IdServico'				value='<?=$localIdServico?>' />
			<input type='hidden' name='IdPessoa'				value='' />
			<input type='hidden' name='IdContrato'				value='' />
			<table>
				<tr>
					<td><?=dicionario(148)?></td>
					<td><?=dicionario(223)?></td>
					<td><?=dicionario(224)?></td>
					<td><?=dicionario(227)?></td>					
					<td><?=dicionario(285)?>.</td>
					<td><?=dicionario(140)?></td>
					<td><?=dicionario(152)?></td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localPessoa?>" name='filtro_pessoa' style='width:160px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" onkeyup="busca_pessoa_aproximada(this,event);" /></td>
					<td><input type='text' value='<?=$localDescricaoServico?>' name='filtro_descricao_servico' style='width:85px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td>
						<select name='filtro_periodicidade' style='width:95px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdPeriodicidade, DescricaoPeriodicidade from Periodicidade where IdLoja = $local_IdLoja and Ativo = 1 order by DescricaoPeriodicidade";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[DescricaoPeriodicidade] = url_string_xsl($lin[DescricaoPeriodicidade],'convert');
									
									echo "<option value='$lin[IdPeriodicidade]' ".compara($localIdPeriodicidade,$lin[IdPeriodicidade],"selected='selected'","").">$lin[DescricaoPeriodicidade]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_mes_fechado' style='width:77px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>

							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=70 order by ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localMesFechado,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td>					
						<select name='filtro_local_cobranca' style='width:80px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdLocalCobranca, AbreviacaoNomeLocalCobranca from LocalCobranca where IdLoja = $local_IdLoja order by DescricaoLocalCobranca";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[AbreviacaoNomeLocalCobranca] = url_string_xsl($lin[AbreviacaoNomeLocalCobranca],'convert');
									
									echo "<option value='$lin[IdLocalCobranca]' ".compara($localLocalCobranca,$lin[IdLocalCobranca],"selected='selected'","").">$lin[AbreviacaoNomeLocalCobranca]</option>";
								}
							?>
						</select>
					</td>									
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
					<td><input type='submit' value='<?=dicionario(166)?>'  class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, '<?=dicionario(198)?>');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom');atualizar_campo();" alt='<?=dicionario(167)?>' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_contrato_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>	
				<tr>
					<td colspan='9'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td><?=dicionario(30)?></td>
									<td>
										<input type='text' name='filtro_id_servico' value='' style='width:70px' maxlength='11' onChange='filtro_buscar_servico(this.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_servico' value='' style='width:341px' readOnly="readOnly" />
									</td>
								</tr>
								<tr>
									<td style='width:300px'><?=dicionario(350)?></td>
									<td>
										<select name='filtro_parametro' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:240px' onKeyDown='listar(event)'>
											<option value=''><?=dicionario(153)?></option>
											<?
												$sql = "select distinct 
															DescricaoParametroServico 
														from
															ServicoParametro 
														where 
															IdLoja = $local_IdLoja 
														order by 
															DescricaoParametroServico ";
												$res = mysql_query($sql,$con);
												while($lin = mysql_fetch_array($res)){
													$lin[DescricaoParametroServico] = substituir_string($lin[DescricaoParametroServico]);
													echo "<option value=\"$lin[DescricaoParametroServico]\" ".compara($localDescricaoParametroServico,$lin[DescricaoParametroServico],"selected='selected'","").">$lin[DescricaoParametroServico]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:300px'><?=dicionario(276)?></td>
									<td>
										<input type='text' value='<?=$localValorParametroServico?>' name='filtro_valor_parametro' style='width:234px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" />
									</td>
								</tr>
								<tr>
									<td style='width:300px'><?=dicionario(277)?></td>
									<td>
										<select name='filtro_contrato_cancelado' style='width:106px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
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
									<td><?=dicionario(278)?></td>
									<td>
										<select name='filtro_contrato_soma' style='width:106px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=106 order by ValorParametroSistema";
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
									<td style='width:121px'><?=dicionario(351)?></td>
									<td><input type='text' value='<?=$localDataInicioCobranca?>' maxlength='10' name='filtro_data_inicio_cobranca' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyPress="mascara(this,event,'date')" onKeyDown="listar(event)" onChange="atualizaSessao(this.name,this.value)"/></td>
								</tr>									
								<tr>
									<td style='width:121px'><?=dicionario(232)?></td>
									<td><input type='text' value='<?=$localDataBase?>' maxlength='10' name='filtro_data_base' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyPress="mascara(this,event,'date')" onKeyDown="listar(event)" onChange="atualizaSessao(this.name,this.value)"/></td>									
								</tr>
								<tr>
									<td><?=dicionario(352)?></td>
									<td>
										<select name='filtro_data_base_nula' style='width:106px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<option value=''><?=dicionario(153)?></option>
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=168 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localDataBaseNula,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(280)?></td>
									<td><input type='text' value='<?=$localQTDCaracterColunaPessoa?>' name='filtro_QTDCaracterColunaPessoa' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" onkeypress="mascara(this,event,'int')" onChange="atualizaSessao(this.name, (this.value == '' ? 0 : this.value));" /></td>
								</tr>								
							</table>
							<div style='width:100%; text-align:right; margin:0;'>
								<img onmousemove="quadro_alt(event, this, '<?=dicionario(291)?>');" src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='<?=dicionario(168)?>' />
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
			echo menu_acesso_rapido(2);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
		
		function atualizar_campo(){
			if(document.filtro.IdServico.value != ''){
				filtro_buscar_servico(document.filtro.IdServico.value);

			}
		}
	</script>
