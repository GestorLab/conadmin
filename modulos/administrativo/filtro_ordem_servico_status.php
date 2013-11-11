	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='menu_ordem_servico_status.php'>
			<table>
				<tr>
					<td><?=dicionario(495)?></td>
					<td><?=dicionario(487)?></td>
					<td><?=dicionario(496)?></td>
					<td />
					<td />
				</tr>
				<tr>
					<td>
						<select name='filtro_grupo_usuario_atendimento' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:205px' onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>	
							<?
								$sql = "select IdGrupoUsuario, DescricaoGrupoUsuario from GrupoUsuario where IdLoja = $IdLoja";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									$lin[DescricaoGrupoUsuario] = url_string_xsl($lin[DescricaoGrupoUsuario],'convert');									
									echo"<option value='$lin[IdGrupoUsuario]' ".compara($IdGrupoUsuarioAtendimento,$lin[IdGrupoUsuario],"selected='selected'","").">$lin[DescricaoGrupoUsuario]</option>";
								}
							?>													
						</select>
					</td>								
					<td>
						<select name='filtro_ordem_servico_tipo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:145px'  onChange="return atualiza_filtro_tipo_servico(this.value)" onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>	
							<?
								$sql = "select IdTipoOrdemServico, DescricaoTipoOrdemServico from TipoOrdemServico order by DescricaoTipoOrdemServico";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									$lin[DescricaoTipoOrdemServico] = url_string_xsl($lin[DescricaoTipoOrdemServico],'convert');									
									echo"<option value='$lin[IdTipoOrdemServico]' ".compara($IdTipoOrdemServico,$lin[IdTipoOrdemServico],"selected='selected'","").">$lin[DescricaoTipoOrdemServico]</option>";
								}
							?>													
						</select>
					</td>								
					<td>
						<select name='filtro_ordem_servico_sub_tipo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:145px' onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>							
						</select>
					</td>
					<td><input type='submit' value='<?=dicionario(166)?>' class='botao' /></td>
					<td style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_ordem_servico_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>&nbsp;</div>
	<div class='graficos' style='text-align:center'>
		<BR><BR><img src='graficos/ordem_servico_status.php?IdGrupoUsuarioAtendimento=<?=$IdGrupoUsuarioAtendimento?>&IdTipoOrdemServico=<?=$IdTipoOrdemServico?>&IdSubTipoOrdemServico=<?=$IdSubTipoOrdemServico?>' />
	</div>
	<script>
		function inicia(){
			if(document.filtro.filtro_ordem_servico_tipo.value != ""){
				atualiza_filtro_tipo_servico(document.filtro.filtro_ordem_servico_tipo.value,'<?=$IdSubTipoOrdemServico?>');
			}
		}
		inicia();
		enterAsTab(document.forms.filtro);
	</script>
