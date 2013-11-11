<?
	if($localOrdem == ''){					$localOrdem = "IdTicket";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getParametroSistema(146,1);	}

	LimitVisualizacao("filtro");

	if($localTipoDado == ''){				$localTipoDado = 'number';	}
	$localIdLoja		= $_SESSION['IdLoja'];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_help_desk_impressao.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_sub_tipo_temp'	value='<?=$localSubTipo?>' />
			<input type='hidden' name='filtro_valor_temp'		value='<?=$localValor?>' />
			<input type='hidden' name='filtro_usuario_atendimento_temp'	value='<?=$localUsuarioAtendimento?>' />
		
			<table>
				<tr>
					<td>Campo</td>
					<td>Usuário</td>
					<td>Prioridade</td>
					<td>Tipo</td>
					<td>Sub Tipo</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>					
					<td>
						<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:130px' onChange="alualizar_filtro_usuario_help_desk(this.value)" onKeyDown="listar(event)">
							<option value=''>Todos</option>
							<option value='UsuarioCadastro' <?=compara($localCampo,"UsuarioCadastro","selected='selected'","")?>>Usuário Cadastro</option>
							<option value='EscritoPor' <?=compara($localCampo,"EscritoPor","selected='selected'","")?>>Escrito Por</option>
							<option value='UsuarioResponsavel' <?=compara($localCampo,"UsuarioResponsavel","selected='selected'","")?>>Usuário Responsável</option>
						</select>
					</td>
					<td>
						<select name='filtro_valor' style='width:121px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''>Todos</option>
						</select>
					</td>
					<td>
						<select name='filtro_prioridade' style='width:111px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 152 order by ValorParametroSistema;";
								$res = @mysql_query($sql,$conCNT);
								while($lin = @mysql_fetch_array($res)){
									echo"<option value='$lin[IdParametroSistema]' ".compara($localIdPrioridade,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
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
					<td>
						<select name='filtro_sub_tipo' style='width:181px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''>Todos</option>
						</select>
					</td>
					<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					
					<td><input type='submit' value='Buscar' class='botao'/></td>					
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avançado')" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="atualizar_campo(); consulta_rapida('bottom')" alt='Parâmetros de Consulta Rápida' /></td>
					<td style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_help_desk_opcoes.php'>Outros<BR />Relatórios</a></td>
				</tr>	
				<tr>
					<td colspan='8'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>								
								<tr>
									<td style='width:181px'>Grupo Atendimento</td>
									<td>
										<select name='filtro_grupo_atendimento' style='width:181px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="atualizar_filtro_usuario_atendimento(this.value)" onKeyDown='listar(event)'>
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
									<td>Status</td>
									<td>
										<select name='filtro_status' style='width:181px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
											<option value=''>Todos</option>
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema='128' order by IdParametroSistema;";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
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
			echo menu_acesso_rapido(42);
		?>
	</div>
	<script>
		function atualizar_campo(){
			if(document.filtro.filtro_usuario_atendimento_temp.value!=""){				
				atualizar_filtro_usuario_atendimento(document.filtro.filtro_grupo_atendimento.value,document.filtro.filtro_usuario_atendimento_temp.value);
			}
		}	
		if(document.filtro.filtro_tipo.value!=""){
			atualizar_filtro_subtipo_help_desk(document.filtro.filtro_tipo.value,document.filtro.filtro_sub_tipo_temp.value);
		}
		if(document.filtro.filtro_campo.value!=""){
			alualizar_filtro_usuario_help_desk(null, document.filtro.filtro_valor_temp.value);
		}		
		enterAsTab(document.forms.filtro);
	</script>