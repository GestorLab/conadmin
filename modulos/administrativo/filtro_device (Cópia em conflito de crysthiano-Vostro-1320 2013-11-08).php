<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_device.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<table class='filtro'>
				<tr>
					<td><?=dicionario(996)?></td>
					<td><?=dicionario(82)?></td>
					<td><?=dicionario(152)?></td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localDescricaoDevice?>' name='filtro_nome_device' style="width: 200px;" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' /></td>
					<td class='campo' >
						<select name="IdTipoDevice" style="width: 80px;" >
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=276 order by ValorParametroSistema";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdTipoDevice,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
						
					</td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" /></td>
					<td><input type='submit' value='<?=dicionario(166)?>' class='botao' /></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(10001);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>