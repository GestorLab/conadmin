<?
	if($localOrdem == ''){					$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localTipoDado == ''){				$localTipoDado = 'text';	}

	LimitVisualizacao("filtro");
	
	$localCancelado					=	$_SESSION['filtro_contrato_cancelado'];
	$localSoma						=	$_SESSION['filtro_contrato_soma'];
	$localQTDCaracterColunaPessoa	=	$_SESSION["filtro_QTDCaracterColunaPessoa"];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_contrato_avancado.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_ordem2'			value='<?=$localOrdem2?>' />
			<input type='hidden' name='filtro_ordem_direcao2'	value='<?=$localOrdemDirecao2?>' />
			<input type='hidden' name='filtro_tipoDado2'		value='<?=$localTipoDado2?>' />
			<input type='hidden' name='IdServico'				value='<?=$local_IdServico?>' />
			<table>
				<tr>
					<td><?=dicionario(40)?></td>
					<td><?=dicionario(223)?></td>
					<td><?=dicionario(275)?></td>
					<td><?=dicionario(288)?></td>
					<td><?=dicionario(140)?></td>
					<td><?=dicionario(152)?></td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td><select name='filtro_local_cobranca' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
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
					<td><input type='text' value='<?=$localDescricaoServico?>' name='filtro_descricao_servico' style='width:90px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td>
						<select name='filtro_parametro' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:150px' onKeyDown='listar(event)'>
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
					<td><input type='text' value='<?=$localValorParametroServico?>' name='filtro_valor_parametro' style='width:80px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
					<td>
						<select name='filtro_status' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:180px' onKeyDown='listar(event)'>
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
					<td><img onmousemove="quadro_alt(event, this, '<?=dicionario(198)?>');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom')" alt='<?=dicionario(167)?>' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_contrato_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>
				<tr>
					<td colspan='8'>	
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:300px'><?=dicionario(277)?></td>
									<td>
										<select name='filtro_contrato_cancelado' style='width:105px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
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
										<select name='filtro_contrato_soma' style='width:105px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
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
									<td><?=dicionario(112)?></td>
									<td>
										<select name='filtro_tipo_pessoa' style='width:105px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
											<option value=''>Todos</option>
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
									<td><?=dicionario(332)?></td>
									<td>
										<select name='filtro_id_servico' style='width:105px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
											<option value=''></option>
											<?
												$sql = "select IdServico from Servico where IdLoja = $local_IdLoja and IdTipoServico = 1 order by IdServico asc;";
												$res = mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													echo "<option value='$lin[IdServico]' ".compara($local_IdServico,$lin[IdServico],"selected='selected'","").">$lin[IdServico]</option>";
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
	</script>
