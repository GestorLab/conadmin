<?
	if($localOrdem == ''){					$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
	
	LimitVisualizacao("filtro");
	
	$localCancelado					=	$_SESSION["filtro_contrato_cancelado"];
	$localSoma						=	$_SESSION["filtro_contrato_soma"];	
	$localParametroAproximidade		=	$_SESSION["filtro_parametro_aproximidade"];
	$localQTDCaracterColunaPessoa	=	$_SESSION["filtro_QTDCaracterColunaPessoa"];

?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_contrato_consumo.php'>
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
					<td><?=dicionario(223)?></td>
					<td><?=dicionario(150)?></td>
					<td><?=dicionario(303)?></td>
					<td><?=dicionario(304)?></td>
					<td><?=dicionario(140)?></td>
					<td><?=dicionario(152)?></td>								
					<td/>								
					<td/>								
					<td/>								
				</tr>
				<tr>
					<td><input type='text' value="<?=$localPessoa?>" name='filtro_pessoa' style='width:160px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" onkeyup="busca_pessoa_aproximada(this,event);" /></td>
					<td><input type='text' value='<?=$localDescricaoServico?>' name='filtro_descricao_servico' style='width:85px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td>
						<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:120px' onKeyDown='listar(event)'>
							<option value='0' <?=compara($localCampo,0,"selected='selected'","")?>><?=dicionario(153)?></option>
							<option value='1' <?=compara($localCampo,1,"selected='selected'","")?>><?=dicionario(127)?></option>
							<option value='2' <?=compara($localCampo,2,"selected='selected'","")?>><?=dicionario(1042)?></option>
							
						</select>
					</td>
					<td><input type='text' value='<?=$localDataInicio?>' name='filtro_data_inicio' style='width:74px' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
					<td><input type='text' value='<?=$localDataTermino?>' name='filtro_data_fim' style='width:74px' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
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
					</td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='<?=dicionario(166)?>'  class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avançado')" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom');atualizar_campo();" alt='Parâmetros de Consulta Rápida' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_contrato_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>	
				<tr>
					<td colspan='9'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:300px'><?=dicionario(26)?></td>
									<td>
										<input type='text' name='filtro_id_pessoa' value='' style='width:70px' maxlength='11' onChange='filtro_buscar_pessoa(this.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_pessoa' value='' style='width:369px' readOnly="readOnly" />
									</td>
								</tr>
								<tr>
									<td><?=dicionario(222)?></td>
									<td>
										<input type='text' name='filtro_id_servico' value='' style='width:70px' maxlength='11' onChange='filtro_buscar_servico(this.value,document.filtro.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_servico' value='' style='width:369px' readOnly="readOnly" />
									</td>
								</tr>
								<tr>
									<td><?=dicionario(112)?></td>
									<td>
										<select name='filtro_tipo_pessoa' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:120px' onKeyDown='listar(event)'>
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
									<td><?=dicionario(106)?></td>
									<td>
										<select name='filtro_grupo_pessoa' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:120px' onKeyDown='listar(event)'>
											<option value=''><?=dicionario(153)?></option>
											<?
												
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
												
												$sql = "select IdGrupoPessoa, DescricaoGrupoPessoa from GrupoPessoa where 1 $where order by DescricaoGrupoPessoa ASC";
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
									<td><?=dicionario(275)?></td>
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
								</tr>								
								<tr>
									<td><?=dicionario(288)?></td>
									<td><input type='text' value='<?=$localValorParametroServico?>' name='filtro_valor_parametro' style='width:114px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
								</tr>
								<tr>
									<td>Estado </td>
									<td>
										<select name='filtro_estado' style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="busca_filtro_cidade(this.value);">
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
									<td>Cidade</td>
									<td>
										<select name='filtro_cidade' style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>											
											<option value=''></option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Bairro</td>
									<td>
										<input type='text' name='filtro_bairro' value='<?=$localBairro?>' style='width:245px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown="listar(event);" />
									</td>
								</tr>
								<tr>
									<td>Endereço</td>
									<td>
										<input type='text' name='filtro_endereco' value='<?=$localEndereco?>' style='width:245px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown="listar(event);" />
									</td>
								</tr>
								<tr>
									<td><?=dicionario(277)?></td>
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
									<td><input type='text' value='<?=$localQTDCaracterColunaPessoa?>' name='filtro_QTDCaracterColunaPessoa' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" onkeypress="mascara(this,event,'int')" onChange="atualizaSessao(this.name, (this.value == '' ? 0 : this.value));" /></td>
								</tr>
							</table>
							<div style='width:100%; text-align:right; margin:0;'>
								<img onmousemove="quadro_alt(event, this, 'Os filtros do \'Menu Avançado\' ao serem alterados, o valor prevalece selecionado durante a sessão');" src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='Parâmetros de Consulta Rápida [Fechar]' />
							</div>
						</div>
					</td>
				</tr>
			</table>			
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(2);			
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
		}
		
		<?if($localIdEstado != ""){ if($localNomeCidade != ""){$localNomeCidade = ",".$localNomeCidade;}?>
			if(document.filtro.filtro_estado.value != ""){
				busca_filtro_cidade(document.filtro.filtro_estado.value<?=$localNomeCidade?>);
			}	
		<?}?>
		enterAsTab(document.forms.filtro);
	</script>