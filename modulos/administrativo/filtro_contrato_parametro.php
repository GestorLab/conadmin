<?
	if($localOrdem == ''){					$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	$localCancelado					=	$_SESSION["filtro_contrato_cancelado"];
	$localQTDCaracterColunaPessoa	=	$_SESSION["filtro_QTDCaracterColunaPessoa"];
	$localMostraTelefone			=	$_SESSION["filtro_coluna_telefone"];
	
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
?>
	<div id='carregando'><?=dicionario(17)?></div>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_contrato_parametro.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_servico'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='IdServico'				value='<?=$localIdServico?>' />
			<input type='hidden' name='IdPessoa'				value='<?=$localIdPessoa?>' />
			<input type='hidden' name='filtro_id_cidade'		value='<?=$localIdCidade?>' />
			<input type='hidden' name='Local'					value='ContratoParametro' />
			
			<table>
				<tr>
					<td><?=dicionario(148)?></td>
					<td><?=dicionario(275)?> 1</td>
					<td><?=dicionario(275)?> 2</td>
					<td><?=dicionario(275)?> 3</td>
					<td><?=dicionario(275)?> 4</td>
					<td><?=dicionario(140)?></td>
					<td><?=dicionario(152)?></td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localPessoa?>" name='filtro_pessoa' style='width:157px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" onkeyup="busca_pessoa_aproximada(this,event);" /></td>
					<td>
						<select name='filtro_parametro_1' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:90px' onChange="busca_parametro(this.name,this.value,document.filtro.filtro_parametro_2)" onKeyDown='listar(event);'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select distinct DescricaoParametroServico from ServicoParametro where IdLoja = $local_IdLoja order by DescricaoParametroServico";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[DescricaoParametroServico] = substituir_string($lin[DescricaoParametroServico]);
									echo "<option value=\"$lin[DescricaoParametroServico]\" ".compara($localParametro1,$lin[DescricaoParametroServico],"selected='selected'","").">$lin[DescricaoParametroServico]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_parametro_2' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:90px' onChange="busca_parametro(this.name,this.value,document.filtro.filtro_parametro_3)" onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
						</select>
					</td>
					<td>
						<select name='filtro_parametro_3' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:90px' onChange="busca_parametro(this.name,this.value,document.filtro.filtro_parametro_4)" onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
						</select>
					</td>
					<td>
						<select name='filtro_parametro_4' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:90px' onChange="busca_parametro(this.name,this.value)" onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
						</select>
					</td>
					<td>
						<select name='filtro_status' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:130px' onKeyDown='listar(event)'>
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
					<td><img onmousemove="quadro_alt(event, this, '<?=dicionario(198)?>');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom');atualizar_campo()" alt='Parâmetros de Consulta Rápida' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_contrato_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>
				<tr>
					<td colspan='9'>	
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td><?=dicionario(26)?></td>
									<td>
										<input type='text' name='filtro_id_pessoa' value='' style='width:70px' maxlength='11' onChange='filtro_buscar_pessoa(this.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_pessoa' value='' style='width:376px' readOnly="readOnly" />
									</td>
								</tr>
								<tr>
									<td><?=dicionario(30)?></td>
									<td>
										<input type='text' name='filtro_id_servico' value='' style='width:70px' maxlength='11' onChange='filtro_buscar_servico(this.value,document.filtro.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_servico' value='' style='width:376px' readOnly="readOnly" />
									</td>
								</tr>
								<tr>
									<td style='width:300px'><?=dicionario(223)?></td>
									<td><input type='text' value='<?=$localNomeServico?>' name='filtro_nome_servico' style='width:260px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
								</tr>
								<tr>
									<td id='titValorParametro1'><?=dicionario(276)?> 1</td>
									<td><input type='text' value='<?=$localValorParametro1?>' name='filtro_valor_parametro1' style='width:260px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" readonly="readonly"/></td>
								</tr>
								<tr>
									<td id='titValorParametro2'><?=dicionario(276)?> 2</td>
									<td><input type='text' value='<?=$localValorParametro2?>' name='filtro_valor_parametro2' style='width:260px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" readonly="readonly"/></td>
								</tr>
								<tr>
									<td id='titValorParametro3'><?=dicionario(276)?> 3</td>
									<td><input type='text' value='<?=$localValorParametro3?>' name='filtro_valor_parametro3' style='width:260px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" readonly="readonly"/></td>
								</tr>
								<tr>
									<td id='titValorParametro4'><?=dicionario(276)?> 4</td>
									<td><input type='text' value='<?=$localValorParametro4?>' name='filtro_valor_parametro4' style='width:260px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" readonly="readonly"/></td>
								</tr>
								<tr>
									<td style='width:240px'><?=dicionario(157)?></td>
									<td>
										<select name='filtro_estado' style='width:265px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange='atualizaSessao(this.name,this.value);busca_filtro_cidade(this.value,document.filtro.filtro_id_cidade.value);'>
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
										<select name='filtro_cidade' style='width:265px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">											
											<option value=''></option>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:240px'><?=dicionario(160)?></td>
									<td>
										<input type='text' name='filtro_bairro' value='<?=$localBairro?>' style='width:260px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown="listar(event);" />
									</td>
								</tr>
								<tr>
									<td style='width:240px'><?=dicionario(155)?></td>
									<td>
										<input type='text' name='filtro_endereco' value='<?=$localEndereco?>' style='width:260px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown="listar(event);" />
									</td>
								</tr>
								<tr>
									<td>Coluna Telefone</td>
									<td>
										<select name='filtro_coluna_telefone' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=280 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localMostraTelefone,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:280px'><?=dicionario(277)?></td>
									<td>
										<select name='filtro_contrato_cancelado' style='width:105px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
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
									<td><?=dicionario(280)?></td>
									<td><input type='text' value='<?=$localQTDCaracterColunaPessoa?>' name='filtro_QTDCaracterColunaPessoa' style='width:99px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" onkeypress="mascara(this,event,'int')" onChange="atualizaSessao(this.name, (this.value == '' ? 0 : this.value));" /></td>
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
		if(document.filtro.filtro_estado.value != ""){
			busca_filtro_cidade(document.filtro.filtro_estado.value,document.filtro.filtro_id_cidade.value);
		}
		function atualizar_campo(){
			if(document.filtro.IdServico.value != ''){
				filtro_buscar_servico(document.filtro.IdServico.value,document.filtro.Local.value);
			}
			if(document.filtro.IdPessoa.value != ''){
				filtro_buscar_pessoa(document.filtro.IdPessoa.value);
			}
		}
		
		enterAsTab(document.forms.filtro);
	</script>