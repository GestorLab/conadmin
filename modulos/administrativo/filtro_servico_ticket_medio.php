<?
	if($localOrdem == ''){					$localOrdem = "DescricaoServico";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_servico_ticket_medio.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='IdServico'				value='' />
			<table>
				<tr>
					<td><?=dicionario(30)?></td>
					<td><?=dicionario(436)?></td>
					<td><?=dicionario(367)?></td>
					<td><?=dicionario(152)?></td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localDescricaoServico?>' name='filtro_descricao_servico' style='width:190px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td>
						<select name='filtro_tipo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:180px' onKeyDown="listar(event)">
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
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='<?=dicionario(166)?>'  class='botao' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_servico_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
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
		enterAsTab(document.forms.filtro);
	</script>
