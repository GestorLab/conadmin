<?
	if($localOrdem == ''){					$localOrdem = "Assunto";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
	
	LimitVisualizacao("filtro");
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_protocolo.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Assunto</td>
					<td>Tipo Protocolo</td>
					<td>Local de Abertura</td>
					<td>Status</td>
					<td>Qtd.</td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localAssunto?>' name='filtro_assunto' style='width:260px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td>
						<select name='filtro_protocolo_tipo' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" style='width:145px' onKeyDown="listar(event)">
							<option value=''>Todos</option>	
							<?
								$sql = "select IdProtocoloTipo, DescricaoProtocoloTipo from ProtocoloTipo where IdLoja = $local_IdLoja order by DescricaoProtocoloTipo";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									$lin[DescricaoProtocoloTipo] = url_string_xsl($lin[DescricaoProtocoloTipo],'convert');
									
									echo"<option value='$lin[IdProtocoloTipo]' ".compara($localIdProtocoloTipo,$lin[IdProtocoloTipo],"selected='selected'","").">$lin[DescricaoProtocoloTipo]</option>";
								}
							?>													
						</select>
					</td>
					<td>
						<select name='filtro_local_abertura' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" style='width:115px' onKeyDown="listar(event)">
							<option value=''>Todos</option>	
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 205 order by ValorParametroSistema";
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
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 239 order by ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo"<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>													
						</select>
					</td>	
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
					<td style='font-size:9px; font-weight:normal'><a style='color:#FFF' href='listar_protocolo_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?=menu_acesso_rapido(51)?>
	</div>
	<script type='text/javascript'>
		enterAsTab(document.forms.filtro);
	</script>