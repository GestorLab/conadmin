<?php
	if($localOrdem == ''){
		$localOrdem = "Assunto";
	}
	
	if($localOrdemDirecao == ''){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	if($localTipoDado == ''){
		$localTipoDado = 'text';
	}
	
	LimitVisualizacao("filtro");
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_protocolo_usuario_atendimento.php'>
			<input type='hidden' name='corRegRand'						value='<?php echo getParametroSistema(15,1); ?>' />
			<input type='hidden' name='filtro' 							value='s' />
			<input type='hidden' name='filtro_ordem'					value='<?php echo $localOrdem; ?>' />
			<input type='hidden' name='filtro_ordem_direcao'			value='<?php echo $localOrdemDirecao; ?>' />
			<input type='hidden' name='filtro_tipoDado'					value='<?php echo $localTipoDado; ?>' />
			<input type='hidden' name='filtro_login_responsavel_temp'	value='<?php echo $localLoginResponsavel; ?>' />
			<input type='hidden' name='filtro_login_alteracao_temp'		value='<?php echo $localLoginAlteracao; ?>' />
			<table>
				<tr>
					<td><?php echo dicionario(148); ?></td>
					<td><?php echo dicionario(719); ?></td>
					<td><?php echo dicionario(930); ?></td>
					<td><?php echo dicionario(140); ?></td>
					<td><?php echo dicionario(152); ?></td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?php echo $localPessoa; ?>' name='filtro_pessoa' style='width:190px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onKeyDown="listar(event)" onkeyup="busca_pessoa_aproximada(this,event);"/></td>
					<td><input type='text' value='<?php echo $localAssunto; ?>' name='filtro_assunto' style='width:190px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td>
						<select name='filtro_local_abertura' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" style='width:115px' onKeyDown="listar(event)">
							<option value=''>Todos</option>	
							<?php
								$sql = "select 
											IdParametroSistema, 
											ValorParametroSistema 
										from 
											ParametroSistema 
										where 
											IdGrupoParametroSistema = 205 
										order by 
											ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								
								while($lin = @mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo"<option value='$lin[IdParametroSistema]' ".compara($localLocalAbertura,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>													
						</select>
					</td>	
					<td>
						<select name='filtro_id_status' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" style='width:115px' onKeyDown="listar(event)">
							<option value=''>Todos</option>	
							<?php
								$sql = "select 
											IdParametroSistema, 
											ValorParametroSistema 
										from 
											ParametroSistema 
										where 
											IdGrupoParametroSistema = 239 
										order by 
											ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								
								while($lin = @mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo"<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>													
						</select>
					</td>	
					<td><input type='text' value='<?php echo $Limit; ?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avançado')" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom')" alt='Parâmetros de Consulta Rápida' /></td>
					<td style='font-size:9px; font-weight:normal'><a style='color:#FFF' href='listar_protocolo_opcoes.php'><?php echo dicionario(120); ?><br /><?php echo dicionario(162); ?></a></td>
				</tr>
				<tr>
					<td colspan='7'>
						<div id='filtroRapido' style='width:100%; display:none; background-color:#0065d5'>
							<table style='width:100%;'>
								<tr> <!-- Expirado -->
									<td style='width:181px'><?php echo dicionario(933); ?></td>
									<td>
										<select name='filtro_protocolo_expirado' style='width:81px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
											<?php
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 262 order by ValorParametroSistema;";
												$res = @mysql_query($sql,$con);
												
												while($lin = @mysql_fetch_array($res)){
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localProtocoloExpirado,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr> <!-- Listar Protocolos Concluído -->
									<td><?php echo dicionario(942); ?></td>
									<td>
										<select name='filtro_protocolo_concluido' style='width:81px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown='listar(event)'>
											<?php
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 265 order by ValorParametroSistema;";
												$res = @mysql_query($sql,$con);
												
												while($lin = @mysql_fetch_array($res)){
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localProtocoloConcluido,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr> <!-- Ocultar coluna Local Aber. -->
									<td><?php echo dicionario(943); ?></td>
									<td>
										<select name='filtro_protocolo_ocultar_local_abertura' style='width:81px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown='listar(event)'>
											<?php
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 266 order by ValorParametroSistema;";
												$res = @mysql_query($sql,$con);
												
												while($lin = @mysql_fetch_array($res)){
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localProtocoloOcultarLocalAbertura,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr> <!-- Tipo Protocolo -->
									<td><?php echo dicionario(929); ?></td>
									<td>
										<select name='filtro_protocolo_tipo' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" style='width:222px' onKeyDown="listar(event)">
											<option value=''>Todos</option>	
											<?php
												$sql = "select IdProtocoloTipo, DescricaoProtocoloTipo from ProtocoloTipo where IdLoja = $local_IdLoja order by DescricaoProtocoloTipo";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													$lin[DescricaoProtocoloTipo] = url_string_xsl($lin[DescricaoProtocoloTipo],'convert');
													
													echo"<option value='$lin[IdProtocoloTipo]' ".compara($localIdProtocoloTipo,$lin[IdProtocoloTipo],"selected='selected'","").">$lin[DescricaoProtocoloTipo]</option>";
												}
											?>													
										</select>
									</td>
								</tr>
								<tr> <!-- Grupo Atendimento -->
									<td><?php echo dicionario(469); ?></td>
									<td>
										<select name='filtro_id_grupo_usuario' style='width:181px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="atualizar_filtro_usuario_atendimento(this.value)" onKeyDown='listar(event)'>
											<option value=''>Todos</option>
											<?php
												$sql = "select 
															UsuarioGrupoUsuario.IdGrupoUsuario, 
															GrupoUsuario.DescricaoGrupoUsuario 
														from 
															UsuarioGrupoUsuario, 
															GrupoUsuario, 
															Usuario, 
															Pessoa 
														where 
															UsuarioGrupoUsuario.IdLoja = $localIdLoja and 
															UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
															UsuarioGrupoUsuario.Login = Usuario.Login and 
															UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
															Usuario.IdPessoa = Pessoa.IdPessoa and 
															Pessoa.TipoUsuario = 1 and 
															Usuario.IdStatus = 1 
														group by 
															UsuarioGrupoUsuario.IdGrupoUsuario;";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													echo"<option value='$lin[IdGrupoUsuario]' ".compara($localIdGrupoUsuario,$lin[IdGrupoUsuario],"selected='selected'","").">$lin[DescricaoGrupoUsuario]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr> <!-- Usuário Atendimento -->
									<td><?php echo dicionario(470); ?></td>
									<td>
										<select name='filtro_login_responsavel' style='width:181px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
											<option value=''>Todos</option>
										</select>
									</td>
								</tr>
								<tr> <!-- Grupo Alteração -->
									<td><?php echo dicionario(946); ?></td>
									<td>
										<select name='filtro_id_grupo_alteracao' style='width:181px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="atualizar_filtro_usuario_alteracao(this.value)" onKeyDown='listar(event)'>
											<option value=''>Todos</option>
											<?php
												$sql = "select 
															UsuarioGrupoUsuario.IdGrupoUsuario, 
															GrupoUsuario.DescricaoGrupoUsuario 
														from 
															UsuarioGrupoUsuario, 
															GrupoUsuario, 
															Usuario, 
															Pessoa 
														where 
															UsuarioGrupoUsuario.IdLoja = $localIdLoja and 
															UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
															UsuarioGrupoUsuario.Login = Usuario.Login and 
															UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
															Usuario.IdPessoa = Pessoa.IdPessoa and 
															Pessoa.TipoUsuario = 1 and 
															Usuario.IdStatus = 1 
														group by 
															UsuarioGrupoUsuario.IdGrupoUsuario;";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													echo"<option value='$lin[IdGrupoUsuario]' ".compara($localIdGrupoAlteracao,$lin[IdGrupoUsuario],"selected='selected'","").">$lin[DescricaoGrupoUsuario]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr> <!-- Usuário Alteração -->
									<td><?php echo dicionario(93); ?></td>
									<td>
										<select name='filtro_login_alteracao' style='width:181px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
											<option value=''>Todos</option>
											
										</select>
									</td>
								</tr>
								<tr> <!-- Data Inicio Alteração -->
									<td><?php echo dicionario(944); ?></td>
									<td><input type='text' value='<?=$localDataInicio?>' name='filtro_data_inicio' style='width:176px' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onKeyDown='listar(event)'/></td>
								</tr>
								<tr> <!-- Data Fim Alteração -->
									<td><?php echo dicionario(945); ?></td>
									<td><input type='text' value='<?=$localDataFim?>' name='filtro_data_fim' style='width:176px' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onKeyDown='listar(event)'/></td>
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
		<?php echo menu_acesso_rapido(51); ?>
	</div>
	<script type='text/javascript'>
		atualizar_filtro_usuario_atendimento(document.filtro.filtro_id_grupo_usuario.value,document.filtro.filtro_login_responsavel_temp.value);
		atualizar_filtro_usuario_alteracao(document.filtro.filtro_id_grupo_alteracao.value,document.filtro.filtro_login_alteracao_temp.value);
		enterAsTab(document.forms.filtro);
	</script>