<?
	if($localOrdem == ''){
		$localOrdem = "DescricaoMalaDireta";
	}
	
	if($localOrdemDirecao == ''){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){
		$localTipoDado = 'text';
	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_mala_direta.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td><?=dicionario(125)?></td>
					<td><?=dicionario(810)?></td>
					<td><?=dicionario(718)?></td>
					<td><?=dicionario(140)?></td>
					<td><?=dicionario(152)?></td>
					<td />
				</tr>
				<tr>
					<td><input type='text' name='filtro_descricao' value='<?=$localDescricao?>' style='width:300px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td>
						<select name='filtro_id_tipo_conteudo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:150px' onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=202 order by ValorParametroSistema";
								$res = mysql_query($sql, $con);
								while($lin = @mysql_fetch_array($res)) {
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdTipoConteudo,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' name='filtro_id_tipo_mensagem' value='<?=$localIdTipoMensagem?>' style='width:100px' maxlength='11' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td>
						<select name='filtro_id_status' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:120px' onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "SELECT IdParametroSistema, ValorParametroSistema FROM ParametroSistema WHERE IdGrupoParametroSistema = 201 ORDER BY ValorParametroSistema;";
								$res = mysql_query($sql, $con);
								while($lin = @mysql_fetch_array($res)) {
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown="listar(event)" /></td>
					<td><input type='submit' value='<?=dicionario(166)?>' class='botao' /></td>
				</tr>	
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(50);
		?>
	</div>
	<script type='text/javascript'>
	<!--
		enterAsTab(document.forms.filtro);
		-->
	</script>