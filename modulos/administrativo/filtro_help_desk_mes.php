	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='menu_help_desk_mes.php'>
			<input type='hidden' name='filtro_sub_tipo_temp'			value='<?=$localSubTipo?>' />
			<input type='hidden' name='filtro_usuario_atendimento_temp'	value='<?=$localUsuarioAtendimento?>' />
			<input type='hidden' name='filtro_usuario_alteracao_temp'	value='<?=$localUsuarioAlteracao?>' />
			<table>
				<tr>
					<td>Mês/Ano</td>
					<td>Nome Pessoa/Razão Social</td>
					<td>Assunto</td>
					<td>Local de Abertura</td>
					<td>Status</td>
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' name='filtro_mes_referencia' value='<?=$localMesReferencia?>' style='width:60px' maxlength='7' onkeypress="mascara(this,event,'mes')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td><input type='text' value='<?=$localNome?>' name='filtro_nome' style='width:180px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" onkeyup="busca_pessoa_aproximada(this,event)" /></td>
					<td><input type='text' value='<?=$localAssunto?>' name='filtro_assunto' style='width:200px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td>
						<select name='filtro_local_abertura' style='width:111px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema='129' order by ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localLocalAbertura,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_status' style='width:111px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema='128' order by IdParametroSistema;";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='submit' value='Buscar' class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avançado')" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom')" alt='Parâmetros de Consulta Rápida' /></td>
					<td style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_help_desk_opcoes.php'>Outros<BR />Relatórios</a></td>
				</tr>
				<tr>
					<td colspan='7'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:181px'>Tipo</td>
									<td>
										<select name='filtro_tipo' style='width:181px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="atualizar_filtro_subtipo_help_desk(this.value)" onKeyDown='listar(event)'>
											<option value=''>Todos</option>
											<?
												$sql = "select
															IdTipoHelpDesk,
															DescricaoTipoHelpDesk
														from 
															HelpDeskTipo 
														where
															IdStatus=1
														order by
															DescricaoTipoHelpDesk;";
												$res = @mysql_query($sql,$conCNT);
												while($lin = @mysql_fetch_array($res)){
													echo"<option value='$lin[IdTipoHelpDesk]' ".compara($localTipo,$lin[IdTipoHelpDesk],"selected='selected'","").">$lin[DescricaoTipoHelpDesk]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td>SubTipo</td>
									<td>
										<select name='filtro_sub_tipo' style='width:181px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
											<option value=''>Todos</option>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:181px'>Grupo Atendimento</td>
									<td>
										<select name='filtro_grupo_atendimento' style='width:181px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="alualizar_filtro_usuario_atendimento(this.value)" onKeyDown='listar(event)'>
											<option value=''>Todos</option>
											<?
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
														GROUP by 
															UsuarioGrupoUsuario.IdGrupoUsuario;";
												$res = @mysql_query($sql,$conCNT);
												while($lin = @mysql_fetch_array($res)){
													echo"<option value='$lin[IdGrupoUsuario]' ".compara($localGrupoAtendimento,$lin[IdGrupoUsuario],"selected='selected'","").">$lin[DescricaoGrupoUsuario]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Usuário Atendimento</td>
									<td>
										<select name='filtro_usuario_atendimento' style='width:181px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
											<option value=''>Todos</option>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:181px'>Grupo Alteração</td>
									<td>
										<select name='filtro_grupo_alteracao' style='width:181px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="atualizar_filtro_usuario_alteracao(this.value)" onKeyDown='listar(event)'>
											<option value=''>Todos</option>
											<?
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
												$res = @mysql_query($sql,$conCNT);
												while($lin = @mysql_fetch_array($res)){
													echo"<option value='$lin[IdGrupoUsuario]' ".compara($localGrupoAlteracao,$lin[IdGrupoUsuario],"selected='selected'","").">$lin[DescricaoGrupoUsuario]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Usuário Alteração</td>
									<td>
										<select name='filtro_usuario_alteracao' style='width:181px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
											<option value=''>Todos</option>
											
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
	<div id='menu_ar'>&nbsp;</div>
	<div class='graficos' style='text-align:center'>
		<BR><BR><img src='graficos/help_desk_qtd_aberto_mes.php?mesReferencia=<?=$localMesReferencia?>&Nome=<?=$localNome?>&Assunto=<?=$localAssunto?>&LocalAbertura=<?=$localLocalAbertura?>&Status=<?=$localStatus?>&Tipo=<?=$localTipo?>&SubTipo=<?=$localSubTipo?>&GrupoAtendimento=<?=$localGrupoAtendimento?>&UsuarioAtendimento=<?=$localUsuarioAtendimento?>&GrupoAlteracao=<?=$localGrupoAlteracao?>&UsuarioAlteracao=<?=$localUsuarioAlteracao?>' />
	</div>
	<script type='text/javascript'>
		if(document.filtro.filtro_tipo.value!=""){
			atualizar_filtro_subtipo_help_desk(document.filtro.filtro_tipo.value,document.filtro.filtro_sub_tipo_temp.value);
		}
		
		atualizar_filtro_usuario_atendimento(document.filtro.filtro_grupo_atendimento.value,document.filtro.filtro_usuario_atendimento_temp.value);
		atualizar_filtro_usuario_alteracao(document.filtro.filtro_grupo_alteracao.value,document.filtro.filtro_usuario_alteracao_temp.value);
		
		if(document.filtro.filtro_grupo_atendimento.value!=""){
			alualizar_filtro_usuario_atendimento(document.filtro.filtro_grupo_atendimento.value,document.filtro.filtro_usuario_atendimento_temp.value);
		}
		
		if(document.filtro.filtro_grupo_alteracao.value!=""){
			atualizar_filtro_usuario_alteracao(document.filtro.filtro_grupo_alteracao.value,document.filtro.filtro_usuario_alteracao_temp.value);
		}
		
		enterAsTab(document.forms.filtro);
	</script>