<?
	if($localOrdem == ''){					$localOrdem = "IdTicket";}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getParametroSistema(146,1);}

	LimitVisualizacao("filtro");

	if($localTipoDado == ''){				$localTipoDado = 'number';}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_help_desk_finalizacao.php'>
			<input type='hidden' name='corRegRand'						value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 							value='s' />
			<input type='hidden' name='filtro_ordem'					value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'			value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'					value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_sub_tipo_temp'			value='<?=$localSubTipo?>' />
			<input type='hidden' name='filtro_usuario_atendimento_temp'	value='<?=$localUsuarioAtendimento?>' />
			<input type='hidden' name='filtro_usuario_alteracao_temp'	value='<?=$localUsuarioAlteracao?>' />
			<table>
				<tr>
					<td>Nome Pessoa/Razão Social</td>
					<td>Assunto</td>
					<td>Campo</td>
					<td>Usuário</td>
					<td>Status</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localNome?>' name='filtro_nome' style='width:160px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" onkeyup="busca_pessoa_aproximada(this,event);" /></td>
					<td><input type='text' value='<?=$localAssunto?>' name='filtro_assunto' style='width:125px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td>
						<select name='filtro_campo_usuario' style='width:100px' onChange="atualizar_filtro_usuario_alteracao(this.value)" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');"  onKeyDown='listar(event)' >
							<option value='Todos'>Todos</option>
							<option value='1' <?=compara($localCampoUsuario,"1","selected='selected'","")?>>Usuário Cadastro</option>
							<option value='2' <?=compara($localCampoUsuario,"2","selected='selected'","")?>>Escrito Por</option>
							<option value='3' <?=compara($localCampoUsuario,"3","selected='selected'","")?>>Usuário Responsável</option>
						</select>
					</td>
					<td>
						<select name='filtro_usuario_alteracao' style='width:160px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							
						</select>
					</td>
					<td>
						<select name='filtro_status' style='width:111px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "SELECT 
											IdParametroSistema,
											ValorParametroSistema 
										FROM
											ParametroSistema 
										WHERE 
											IdGrupoParametroSistema = '128' 
											AND IdParametroSistema <> 100 
											AND IdParametroSistema <> 200 
											AND IdParametroSistema <> 300 
											AND IdParametroSistema <> 500 
											ORDER BY IdParametroSistema ;
										";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao'/></td>
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avançado')" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick=" consulta_rapida('bottom');atualizar_campo();" alt='Parâmetros de Consulta Rápida' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_help_desk_opcoes.php'>Outros<BR />Relatórios</a></td>
				</tr>	
				<tr>
					<td colspan='8'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:241px'>Local de Abertura</td>
									<td>
										<select name='filtro_local_abertura' style='width:111px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
											<option value=''>Todos</option>
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema='129' order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localIdLocalAbertura,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
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
									<td>Campo</td>
									<td>
										<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:110px'  onKeyDown="listar(event)">
											<option value=''><?=dicionario(153)?></option>
											<option value='DataConclusao' <?=compara($localCampo,"DataConclusao","selected='selected'","")?>><?=dicionario(462)?></option>
											<option value='DataFinalizacao' <?=compara($localCampo,"DataFinalizacao","selected='selected'","")?>><?=dicionario(1040)?></option>																						
											<option value='MesConclusao' <?=compara($localCampo,"MesConclusao","selected='selected'","")?>><?=dicionario(482)?></option>
											<option value='MesFinalizacao' <?=compara($localCampo,"MesFinalizacao","selected='selected'","")?>><?=dicionario(1041)?>.</option>											
										</select>
									</td>
								</tr>
								<tr>
									<td>Valor Campo</td>
									<td><input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:105px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="chama_mascara(this,event)" onKeyDown="listar(event);" /></td>
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
		atualizar_filtro_usuario_alteracao(''.value,document.filtro.filtro_usuario_alteracao_temp.value);
		
		if(document.filtro.filtro_tipo.value!=""){
			atualizar_filtro_subtipo_help_desk(document.filtro.filtro_tipo.value,document.filtro.filtro_sub_tipo_temp.value);
		}
		
		enterAsTab(document.forms.filtro);
	</script>
