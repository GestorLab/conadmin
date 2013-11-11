<?
	if($localOrdem == ''){					$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
	if($localContratoAtivo == ''){			$localContratoAtivo = 1;	}
	
	LimitVisualizacao("filtro");
	
	$localCancelado					=	$_SESSION["filtro_contrato_cancelado"];
	$localSoma						=	$_SESSION["filtro_contrato_soma"];
	$localQTDCaracterColunaPessoa	=	$_SESSION["filtro_QTDCaracterColunaPessoa"];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_contrato_pessoa_dados_cliente.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_ordem2'			value='<?=$localOrdem2?>' />
			<input type='hidden' name='filtro_ordem_direcao2'	value='<?=$localOrdemDirecao2?>' />
			<input type='hidden' name='filtro_tipoDado2'		value='<?=$localTipoDado2?>' />
			<input type='hidden' name='IdPessoa'				value='' />
			<input type='hidden' name='IdContrato'				value='' />
			<input type='hidden' name='IdServico'				value='<?=$localIdServico?>' />
			<input type='hidden' name='filtro_id_cidade'		value='<?=$localIdCidade?>' />
			<input type='hidden' name='filtro_nome_cidade'		value='<?=$localNomeCidade?>' />
			<input type='hidden' name='filtro_nome_servico'		value='<?=$localNomeServico?>' />
			<input type='hidden' name='Local'					value='ContratoDatas' />
			<table>
				<tr>
					<td><?=dicionario(148)?></td>
					<td><?=dicionario(112)?></td>
					<td><?=dicionario(106)?></td>
					<td><?=dicionario(150)?></td>			
					<td><?=dicionario(151)?></td>
					
					<td><?=dicionario(140)?></td>
					<td><?=dicionario(152)?></td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localPessoa?>" name='filtro_pessoa' style='width:155px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" onkeyup="busca_pessoa_aproximada(this,event);"/></td>
					<td><select name='filtro_tipo_pessoa' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "SELECT
											IdParametroSistema,
											DescricaoParametroSistema 
										FROM 
											ParametroSistema
										WHERE 
											IdGrupoParametroSistema = 1";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[DescricaoLocalCobranca] = url_string_xsl($lin[DescricaoLocalCobranca],'convert');													
									echo "<option value='$lin[IdParametroSistema]' ".compara($localTipoPessoa,$lin[IdParametroSistema],"selected='selected'","").">$lin[DescricaoParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_grupo_pessoa' style='width:100px' onFocus="Foco(this,'in')"   onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
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
								
								$sql = "select IdGrupoPessoa, DescricaoGrupoPessoa from GrupoPessoa where IdLoja = $local_IdLoja $where order by DescricaoGrupoPessoa ASC";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[DescricaoGrupoPessoa] = url_string_xsl($lin[DescricaoGrupoPessoa],'convert');
									echo "<option value='$lin[IdGrupoPessoa]' ".compara($localGrupoPessoa,$lin[IdGrupoPessoa],"selected='selected'","").">$lin[DescricaoGrupoPessoa]</option>";
								}
							?>
						</select>
					</td>
					<td>
					<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:100px'  onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<option value='CPF_CNPJ' <?=compara($localCampo,"CPF_CNPJ","selected='selected'","")?>><?=dicionario(154)?></option>							
							<option value='Email' <?=compara($localCampo,"Email","selected='selected'","")?>><?=dicionario(104)?></option>							
							<option value='Fone' <?=compara($localCampo,"Fone","selected='selected'","")?>><?=dicionario(213)?></option>						
							<option value='Filiacao' <?=compara($localCampo,"Filiacao","selected='selected'","")?>><?=dicionario(161)?></option>
							<option value='Obs' <?=compara($localCampo,"Obs","selected='selected'","")?>><?=dicionario(159)?></option>
						</select>
					</td>
					<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:100px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					
					<td>
						<select name='filtro_status' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:100px' onKeyDown='listar(event)'>
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
					<td><input type='submit' value='Buscar'  class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, '<?=dicionario(198)?>');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="atualizar_campo(); consulta_rapida('bottom')" alt='Parâmetros de Consulta Rápida' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_contrato_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>	
				<tr>
					<td colspan='9'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>								
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
									<td><?=dicionario(278)?></td>
									<td>
										<select name='filtro_contrato_soma' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
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
									<td><?=dicionario(275)?></td>
									<td>
									<select name='filtro_parametro' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:250px' onKeyDown='listar(event)'>
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
									<td><?=dicionario(276)?></td>					
									<td><input type='text' value='<?=$localValorParametroServico?>' name='filtro_valor_parametro' style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
								</tr>	
								<tr>
									<td><?=dicionario(40)?></td>
									<td>
										<select name='filtro_local_cobranca' style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
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
									<td><?=dicionario(296)?></td>
									<td>
										<select name='filtro_usuario' style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<option value=''></option>
											<?
												$sql = "select 
															IdPessoa, 
															Login 
														from 
															Usuario 
														where 
															IdStatus = 1 and
															Login != 'root' and
															Login != 'cda' and
															Login != 'automatico'
														order by 
															Login;";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													$lin[Login] = url_string_xsl($lin[Login],'convert');
													
													echo "<option value='$lin[Login]' ".compara($localUsuario,$lin[Login],"selected='selected'","").">$lin[Login]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:240px'><?=dicionario(157)?></td>
									<td>
										<select name='filtro_estado' style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange='atualizaSessao(this.name,this.value);busca_filtro_cidade(this.value,"");'>
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
								<tr>
									<td style='width:240px'><?=dicionario(994)?></td>
									<td>
										<select name='filtro_contrato_ativo' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=106 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localContratoAtivo,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
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
		}
		enterAsTab(document.forms.filtro);
	</script>