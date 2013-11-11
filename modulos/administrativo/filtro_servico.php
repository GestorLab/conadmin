<?
	if($localOrdem == ''){					$localOrdem = "DescricaoServico";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($local_servicosDesativados == ''){	$local_servicosDesativados = getCodigoInterno(3,221);	}
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
	$localIdLoja 					=	$_SESSION["IdLoja"];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_servico.php'>
			<input type='hidden' name='corRegRand'					value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 						value='s' />
			<input type='hidden' name='filtro_ordem'				value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'		value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'				value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_servicosDesativados'	value='<?=$local_servicosDesativados?>' />
			<input type='hidden' name='IdServico'					value='' />
			<table>
				<tr>
					<td><?=dicionario(30)?></td>
					<td><?=dicionario(436)?></td>
					<td><?=dicionario(367)?></td>
					<td><?=dicionario(204)?> (<?=getParametroSistema(5,1)?>)</td>
					<td><?=dicionario(140)?></td>
					<td><?=dicionario(152)?></td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localDescricaoServico?>' name='filtro_descricao_servico' style='width:150px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td>
						<select name='filtro_tipo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:100px' onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=71 order by ValorParametroSistema";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdTipoServico,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_servico_grupo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:150px' onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdServicoGrupo, DescricaoServicoGrupo from ServicoGrupo order by DescricaoServicoGrupo";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[DescricaoServicoGrupo] = url_string_xsl($lin[DescricaoServicoGrupo],'convert');

									echo "<option value='$lin[IdServicoGrupo]' ".compara($localServicoGrupo,$lin[IdServicoGrupo],"selected='selected'","").">$lin[DescricaoServicoGrupo]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localValor?>' name='filtro_valor' maxlength='12' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'float')" onKeyDown="listar(event)"/></td>								
					<td>
						<select name='filtro_idstatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:120px' onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=17 order by ValorParametroSistema";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>	
					<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='<?=dicionario(166)?>'  class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avançado')" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom');atualizar_campo();" alt='Parâmetros de Consulta Rápida' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_servico_opcoes.php'><?=dicionario(120)?><br /><?=dicionario(162)?></a></td>
					
				</tr>	
				<tr>
					<td colspan='8'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>								
								<tr>
									<td><?=dicionario(988)?></td>
									<td>
										<select name='filtro_listar_servicos_desativados' style='width:105px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=105 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');													
													echo "<option value='$lin[IdParametroSistema]' ".compara($local_servicosDesativados,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:300px'><?=dicionario(224)?></td>
									<td><select name='filtro_periodicidade' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
											<option value=''><?=dicionario(153)?></option>	
											<?
											$sql = "select IdPeriodicidade, DescricaoPeriodicidade from Periodicidade where IdLoja = $local_IdLoja and Ativo=1 order by DescricaoPeriodicidade";
											$res = @mysql_query($sql,$con);
											while($lin = mysql_fetch_array($res)){
													
													$lin[DescricaoLocalCobranca] = url_string_xsl($lin[DescricaoLocalCobranca],'convert');
													
													echo "<option value='$lin[IdPeriodicidade]' ".compara($local_periodicidade,$lin[IdPeriodicidade],"selected='selected'","").">$lin[DescricaoPeriodicidade]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:300px'><?=dicionario(112)?></td>
									<td><select name='filtro_tipo_pessoa' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
											<option value=''><?=dicionario(153)?></option>											
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=1 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = mysql_fetch_array($res)){													
													$lin[DescricaoLocalCobranca] = url_string_xsl($lin[DescricaoLocalCobranca],'convert');													
													echo "<option value='$lin[IdParametroSistema]' ".compara($local_idTipoPessoa,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:300px'><?=dicionario(33)?></td>
									<td><select name='filtro_terceiro' style='width:200px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
											<option value=''><?=dicionario(153)?></option>
												<?
													$sql = "SELECT 
																Pessoa.Nome, 
																Terceiro.IdPessoa

															FROM
																Pessoa,
																Terceiro
															WHERE
																Terceiro.IdLoja = $localIdLoja AND
																Pessoa.IdLoja = Terceiro.IdLoja AND												
																Pessoa.IdPessoa = Terceiro.IdPessoa";
													$res = mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');													
													echo "<option value='$lin[IdPessoa]' ".compara($local_filtroTerceiro,$lin[IdPessoa],"selected='selected'","").">$lin[Nome]</option>";
													}
												?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:300px'><?=dicionario(545)?></td>
									<td><select name='filtro_tipo_nota_fiscal' style='width:200px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
											<option value=''><?=dicionario(153)?></option>
											<?
												$sql = "select
															NotaFiscalTipo.IdNotaFiscalTipo,
															NotaFiscalLayout.DescricaoNotaFiscalLayout
														from
															NotaFiscalLayout,
															NotaFiscalTipo
														where
															NotaFiscalTipo.IdLoja = $local_IdLoja and
															NotaFiscalLayout.IdNotaFiscalLayout = NotaFiscalTipo.IdNotaFiscalLayout 
														order by
															NotaFiscalLayout.DescricaoNotaFiscalLayout;";
												$res = @mysql_query($sql,$con);
												while($lin = mysql_fetch_array($res)){
													
													$lin[DescricaoLocalCobranca] = url_string_xsl($lin[DescricaoLocalCobranca],'convert');
													
													echo "<option value='$lin[IdNotaFiscalTipo]' ".compara($local_tipo_nota_fiscal,$lin[IdNotaFiscalTipo],"selected='selected'","").">$lin[DescricaoNotaFiscalLayout]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:300px'><?=dicionario(546)?></td>
									<td><select name='filtro_categoria_tributaria' style='width:200px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
											<option value=''><?=dicionario(153)?></option>
											<?
													$sql = "select
																ParametroSistema.IdParametroSistema,
																ParametroSistema.ValorParametroSistema
															from
																ParametroSistema
															where
																ParametroSistema.IdGrupoParametroSistema = 159";
													$res = @mysql_query($sql,$con);
													while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($local_categoriaTributaria,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
													}
												?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:300px'><?=dicionario(549)?></td>
									<td><select name='filtro_email_cobraca' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
											<option value=''><?=dicionario(153)?></option>
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=105 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($local_filtro_email_cobraca,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:300px'><?=dicionario(550)?></td>
									<td><select name='filtro_executar_rotinas_diarias' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
											<option value=''><?=dicionario(153)?></option>
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=277 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($local_executarRotinas,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:120px'><?=dicionario(157)?></td>
									<td>
										<select name='filtro_estado' style='width:250px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="busca_filtro_cidade_estado(this.value)">
											<option value=''></option>
											<?
												$sql = "select IdEstado, NomeEstado from Estado where 1 order by NomeEstado asc";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													echo "<option value='$lin[IdEstado]' ".compara($local_idPaisEstadoCidade,$lin[IdEstado],"selected='selected'","").">$lin[NomeEstado]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(186)?></td>
									<td>
										<select name='filtro_cidade' style='width:250px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
											<option value='<?=$local_IdCidade;?>'></option>
										</select>
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
			echo menu_acesso_rapido(18);
		?>
	</div>
	<script>
		function atualizar_campo(){				
			if(document.filtro.filtro_estado.value != ''){
				if(document.filtro.filtro_cidade.value == ''){
					busca_filtro_cidade_estado(document.filtro.filtro_estado.value,"");
				} else{
					busca_filtro_cidade_estado(document.filtro.filtro_estado.value,document.filtro.filtro_cidade.value);
				}
			}
		}
		enterAsTab(document.forms.filtro);
	</script>
