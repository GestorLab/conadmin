<?
	if($localOrdem == ''){							$localOrdem = "MesReferencia";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	$mascara = "";
	if($localTipoDado == ''){						$localTipoDado = 'number';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_processo_financeiro.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />

			<table>
				<tr>
					<td><?=dicionario(196)?></td>
					<td><?=dicionario(777)?></td>
					<td><?=dicionario(40)?></td>
					<td><?=dicionario(150)?></td>
					<td><?=dicionario(151)?></td>
					<td><?=dicionario(140)?></td>
					<td><?=dicionario(152)?></td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localMesReferencia?>' name='filtro_mes_referencia' style='width:90px' onFocus="Foco(this,'in')"  maxlength='7' onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'mes')" onKeyDown='listar(event)'/></td>
					<td>
						<select name='filtro_menor_vencimento' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:120px' onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdCodigoInterno, ValorCodigoInterno from CodigoInterno where IdLoja=$local_IdLoja and IdGrupoCodigoInterno=1 order by IdCodigoInterno";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									
									$lin[ValorCodigoInterno] = url_string_xsl($lin[ValorCodigoInterno],'convert');
								
									echo "<option value='$lin[IdCodigoInterno]' ".compara($localMenorVencimento,$lin[IdCodigoInterno],"selected='selected'","").">$lin[ValorCodigoInterno]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_local_cobranca' style='width:115px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
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
						<select name='filtro_campo' style='width:110px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<option value='DataCadastro' <?=compara($localCampo,"DataCadastro","selected='selected'","")?>>Data Cadastro</option>
							<option value='DataAlteracao' <?=compara($localCampo,"DataAlteracao","selected='selected'","")?>>Data Alteração</option>
							<option value='DataConfirmacao' <?=compara($localCampo,"DataConfirmacao","selected='selected'","")?>>Data Confirmação</option>
							<option value='DataNotaFiscal' <?=compara($localCampo,"DataNotaFiscal","selected='selected'","")?>>Data Nota Fiscal</option>
							<option value='MesCadastro' <?=compara($localCampo,"MesCadastro","selected='selected'","")?>>Mês Cadastro</option>	
							<option value='MesAlteracao' <?=compara($localCampo,"MesAlteracao","selected='selected'","")?>>Mês Alteração</option>
							<option value='MesConfirmacao' <?=compara($localCampo,"MesConfirmacao","selected='selected'","")?>>Mês Confirmação</option>
							<option value='MesVencimento' <?=compara($localCampo,"MesVencimento","selected='selected'","")?>>Mês Vencimento</option>
							<option value='MesNotaFiscal' <?=compara($localCampo,"MesNotaFiscal","selected='selected'","")?>>Mês Nota Fiscal</option>
							
						</select>
					</td>
					<td><input type='text' value='<?=$localValorCampo?>' name='filtro_valor_campo' style='width:74px' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" onkeypress="chama_mascara(this,event)"/></td>
					<td>
						<select name='filtro_idstatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:130px' onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=29";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='<?=dicionario(166)?>' class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avançado');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom')" alt='<?=dicionario(167)?>' /></td>
				</tr>	
				<tr>
					<td colspan='9'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:180px'><?=dicionario(132)?></td>
									<td>
										<select name='filtro_usuario_cadastro' style='width:130px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
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
													
													echo "<option value='$lin[Login]' ".compara($localUsuarioCadastro,$lin[Login],"selected='selected'","").">$lin[Login]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(134)?></td>
									<td>
										<select name='filtro_usuario_alteracao' style='width:130px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
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
													
													echo "<option value='$lin[Login]' ".compara($localUsuarioAlteracao,$lin[Login],"selected='selected'","").">$lin[Login]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(786)?></td>
									<td>
										<select name='filtro_usuario_confirmacao' style='width:130px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
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
													
													echo "<option value='$lin[Login]' ".compara($localUsuarioConfirmacao,$lin[Login],"selected='selected'","").">$lin[Login]</option>";
												}
											?>
										</select>
									</td>
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
			echo menu_acesso_rapido(3);
		?>
	</div>
	<div id='carregando'><?=dicionario(17)?></div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>