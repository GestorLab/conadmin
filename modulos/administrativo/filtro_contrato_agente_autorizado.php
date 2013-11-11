<?
	if($localOrdem == ''){					$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
	if($localIdPais == ''){					$localIdPais = getCodigoInterno(3,1);	}
	
	LimitVisualizacao("filtro");
	
	$localCancelado					=	$_SESSION['filtro_contrato_cancelado'];
	$localSoma						=	$_SESSION['filtro_contrato_soma'];
	$localQTDCaracterColunaPessoa	=	$_SESSION["filtro_QTDCaracterColunaPessoa"];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_contrato_agente_autorizado.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_ordem2'			value='<?=$localOrdem2?>' />
			<input type='hidden' name='filtro_ordem_direcao2'	value='<?=$localOrdemDirecao2?>' />
			<input type='hidden' name='filtro_tipoDado2'		value='<?=$localTipoDado2?>' />
			<input type='hidden' name='filtro_carteira_temp'	value='<?=$localIdCarteira?>' />
			<input type='hidden' name='IdServico'				value='<?=$IdServico?>' />
			<input type='hidden' name='Local'					value='ContratoAgenteAutorizado' />
			<table>
				<tr>
					<td><?=dicionario(148)?></td>
					<td><?=dicionario(116)?></td>
					<td><?=dicionario(117)?></td>
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
					<td><input type='text' value="<?=$localPessoa?>" name='filtro_pessoa' style='width:165px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" onkeyup="busca_pessoa_aproximada(this,event);" /></td>
					<td>
						<select name='filtro_agente_autorizado' style='width:111px' onChange="return atualizar_filtro_carteira(this.value)" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select
											AgenteAutorizado.IdAgenteAutorizado,
											Pessoa.Nome
										from
											AgenteAutorizado,
											Pessoa
										where
											AgenteAutorizado.IdAgenteAutorizado = Pessoa.IdPessoa and
											AgenteAutorizado.IdStatus = 1
										order by 
											Pessoa.Nome ASC";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									echo "<option value='$lin[IdAgenteAutorizado]' ".compara($localIdAgenteAutorizado,$lin[IdAgenteAutorizado],"selected='selected'","").">$lin[Nome]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select type='text' name='filtro_carteira' value='' style='width:85px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown="listar(event);">
							<option value=''><?=dicionario(153)?></option>
						</select>
					</td>
					<td>
						<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:75px'  onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<option value='DataCadastro' <?=compara($localCampo,"DataCadastro","selected='selected'","")?>><?=dicionario(305)?></option>
							<option value='DataInicioContrato' <?=compara($localCampo,"DataInicioContrato","selected='selected'","")?>><?=dicionario(230)?></option>
							<option value='DataInicioCobranca' <?=compara($localCampo,"DataInicioCobranca","selected='selected'","")?>><?=dicionario(231)?></option>
							<option value='DataBase' <?=compara($localCampo,"DataBase","selected='selected'","")?>><?=dicionario(232)?></option>
							<option value='DataTermino' <?=compara($localCampo,"DataTermino","selected='selected'","")?>><?=dicionario(307)?></option>
							<option value='DataUltimaCobranca' <?=compara($localCampo,"DataUltimaCobranca","selected='selected'","")?>><?=dicionario(234)?></option>
						</select>
					</td>
					<td><input type='text' value='<?=$localDataInicio?>' name='filtro_data_inicio' style='width:74px' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
					<td><input type='text' value='<?=$localDataFim?>' name='filtro_data_fim' style='width:74px' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
					<td>
						<select name='filtro_status' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:85px' onKeyDown='listar(event)'>
							<option value=''>Todos</option>
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
					<td><input type='submit' value='Buscar'  class='botao' Onclick='Atualizar_campo()'/></td>
					<td><img onmousemove="quadro_alt(event, this, '<?=dicionario(198)?>');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom'); Atualizar_campo()" alt='<?=dicionario(167)?>' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_contrato_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>
				<tr>
					<td colspan='7'>	
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td><?=dicionario(30)?></td>
									<td>
										<input type='text' name='filtro_id_servico' value='' style='width:70px' maxlength='11' onChange='filtro_buscar_servico(this.value,document.filtro.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_servico' value='' style='width:300px' readOnly="readOnly" />
									</td>
								</tr>
								<tr>
									<td style='width:300px'><?=dicionario(277)?></td>
									<td>
										<select name='filtro_contrato_cancelado' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
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
										<select name='filtro_contrato_soma' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onChange="atualizaSessao(this.name,this.value)">
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
									<td><?=dicionario(223)?></td>
									<td>
										<input type='text' name='filtro_nome_servico' value='<?=$localNomeServico?>' style='width:222px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" />
									</td>
								</tr>
								<tr>
									<td><?=dicionario(280)?></td>
									<td><input type='text' value='<?=$localQTDCaracterColunaPessoa?>' name='filtro_QTDCaracterColunaPessoa' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" onkeypress="mascara(this,event,'int')" onChange="atualizaSessao(this.name, (this.value == '' ? 0 : this.value));" /></td>
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
			echo menu_acesso_rapido(2);
		?>
	</div>
	<script>
		function Atualizar_campo(){
			if(document.filtro.IdServico.value != ""){
				filtro_buscar_servico(document.filtro.IdServico.value,document.filtro.Local.value);
			}
		}
		inicia();
		enterAsTab(document.forms.filtro);
	</script>