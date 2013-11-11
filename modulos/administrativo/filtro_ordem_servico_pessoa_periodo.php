<?
	if($localOrdem == ''){					$localOrdem = "DataCriacao";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = "descending";	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){				$localTipoDado = 'number';	}
	$local_ListaConcluido					=	$_SESSION["filtro_lista_concluido"];	
	$local_ListaCancelado					=	$_SESSION["filtro_lista_cancelado"];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_ordem_servico_pessoa_periodo.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td><?=dicionario(148)?></td>
					<td><?=dicionario(223)?></td>
					<td><?=dicionario(150)?></td>
					<td><?=dicionario(151)?></td>
					<td><?=dicionario(140)?></td>
					<td><?=dicionario(152)?></td>
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' name='filtro_nome' value="<?=$localNome?>" style='width:225px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" onkeyup="busca_pessoa_aproximada(this,event);" /></td>
					<td><input type='text' name='filtro_servico' value='<?=$localDescricaoServico?>' style='width:105px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td>
						<select name='filtro_campo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:135px'  onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<option value='DataAgendamento' <?=compara($localCampo,"DataAgendamento","selected='selected'","")?>><?=dicionario(451)?></option>
							<option value='DataAlteracao' <?=compara($localCampo,"DataAlteracao","selected='selected'","")?>><?=dicionario(135)?></option>
							<option value='DataCadastro' <?=compara($localCampo,"DataCadastro","selected='selected'","")?>><?=dicionario(133)?></option>
							<option value='DataConclusao' <?=compara($localCampo,"DataConclusao","selected='selected'","")?>><?=dicionario(462)?></option>
							<option value='DataFaturamento' <?=compara($localCampo,"DataFaturamento","selected='selected'","")?>><?=dicionario(478)?></option>
						</select>
					</td>
					<td>
						<input type='text' name='filtro_valor' value='<?=$localValor?>' style='width:95px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" onkeypress="chama_mascara(this,event)"/></td>
					<td>
						<select name='filtro_idstatus' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:102px' onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 order by ValorParametroSistema";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:24px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='<?=dicionario(166)?>'  class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avançado');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom')" alt='<?=dicionario(167)?>' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_ordem_servico_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>
				<tr>
					<td colspan='8'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:159px'><?=dicionario(470)?></td>
									<td>
										<select name='filtro_usuario_atendimento' style='width:188px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')">
											<option value=''><?=dicionario(153)?></option>
											<?
												$sql = "select 
															UsuarioGrupoUsuario.Login,
															Pessoa.Nome 
														from
															GrupoUsuario,
															UsuarioGrupoUsuario,
															Usuario,
															Pessoa 
														where 
															GrupoUsuario.Idloja = $local_IdLoja and 
															GrupoUsuario.IdLoja = UsuarioGrupoUsuario.IdLoja and
															GrupoUsuario.IdGrupoUsuario = UsuarioGrupoUsuario.IdGrupoUsuario and
															UsuarioGrupoUsuario.Login = Usuario.Login and
															Usuario.IdPessoa = Pessoa.IdPessoa 
														group by Pessoa.IdPessoa
														order by Pessoa.Nome";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													$lin[Nome] = url_string_xsl($lin[Nome],'convert');
													echo "<option value='$lin[Login]' ".compara($local_UsuarioAtendimento,$lin[Login],"selected='selected'","").">$lin[Nome]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:150px'><?=dicionario(132)?></td>
									<td>
										<select name='filtro_usuario_cadastro' style='width:188px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')">
											<option value=''><?=dicionario(153)?></option>
											<?
												$sql = "select 
															Usuario.Login
														from
															Usuario 
														where 
															Usuario.Login != 'cda' and 
															Usuario.Login != 'automatico' 
														order by Login asc";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													$lin[Login] = url_string_xsl($lin[Login],'convert');
													echo "<option value='$lin[Login]' ".compara($local_UsuarioCadastro,$lin[Login],"selected='selected'","").">$lin[Login]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:150px'><?=dicionario(461)?></td>
									<td>
										<select name='filtro_usuario_conclusao' style='width:188px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')">
											<option value=''><?=dicionario(153)?></option>
											<?
												$sql = "select 
															Usuario.Login
														from
															Usuario 
														where 
															Usuario.Login != 'cda' and 
															Usuario.Login != 'automatico' 
														order by Login asc";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													$lin[Login] = url_string_xsl($lin[Login],'convert');
													echo "<option value='$lin[Login]' ".compara($local_UsuarioConclusao,$lin[Login],"selected='selected'","").">$lin[Login]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td style='width:150px'>Usuario Faturamento</td>
									<td>
										<select name='filtro_usuario_faturamento' style='width:188px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')">
											<option value=''>Todos</option>
											<?
												$sql = "select distinct
															Nome,
															UsuarioGrupoUsuario.Login
														from
															Pessoa,
															Usuario,
															GrupoUsuario,
															UsuarioGrupoUsuario
														where
															GrupoUsuario.Idloja = $local_IdLoja and
															Pessoa.IdPessoa = Usuario.IdPessoa and
															UsuarioGrupoUsuario.Login = Usuario.Login
														order by Nome asc";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													$lin[Nome] = url_string_xsl($lin[Nome],'convert');
													echo "<option value='$lin[Login]' ".compara($local_UsuarioFaturamento,$lin[Login],"selected='selected'","").">$lin[Nome]</option>";
												}
											?>
										</select>
									</td> 
								</tr>
								<tr>
									<td><?=dicionario(898)?></td>
									<td>
										<input type='text' name='filtro_data_abertura' value='<?=$localDataAbertura?>' style='width:182px' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)" onKeyPress="mascara(this,event,'date')" />
									</td>
								</tr>
								<tr>
									<td><?=dicionario(899)?></td>
									<td>
										<input type='text' name='filtro_data_conclusao' value='<?=$localDataConclusao?>' style='width:182px' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)" onKeyPress="mascara(this,event,'date')" />
									</td>
								</tr>
								<tr>
									<td style='width: 120px'><?=dicionario(973)?></td>
									<td>
										<select name='filtro_lista_concluido' style='width:90px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown="listar(event)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=60 order by ValorParametroSistema";
												$res = mysql_query($sql,$con);
												while($lin = mysql_fetch_array($res)){
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													echo "<option value='$lin[IdParametroSistema]' ".compara($local_ListaConcluido,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(978)?></td>
									<td>
										<select name='filtro_lista_cancelado' style='width:90px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown="listar(event)">
											<?
												$sql = "select
															IdParametroSistema,
															ValorParametroSistema
														from
															ParametroSistema
														where
															IdGrupoParametroSistema = 271
														order by
															ValorParametroSistema";
												$res = mysql_query($sql,$con);
												while($lin = mysql_fetch_array($res)){
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													echo "<option value='$lin[IdParametroSistema]' ".compara($local_ListaCancelado,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
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
			echo menu_acesso_rapido(20);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
