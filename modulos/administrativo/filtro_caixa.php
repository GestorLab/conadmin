<?php 
	if($localOrdem == ''){
		$localOrdem = "DataAbertura";
	}
	
	if($localOrdemDirecao == ''){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	if($localTipoDado == ''){
		$localTipoDado = 'number';
	}
	
	LimitVisualizacao("filtro");
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_caixa.php'>
			<input type='hidden' name='corRegRand'				value='<?php echo getParametroSistema(15,1); ?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?php echo $localOrdem; ?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?php echo $localOrdemDirecao; ?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?php echo $localTipoDado; ?>' />
			<table>
				<tr>
					<td><?php echo dicionario(250); ?></td>
					<td><?php echo dicionario(445); ?></td>
					<td><?php echo dicionario(376); ?></td>
					<td><?php echo dicionario(377); ?></td>
					<td><?php echo dicionario(140); ?></td>
					<td><?php echo dicionario(152); ?></td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?php echo $localPessoa; ?>" name='filtro_pessoa' style='width:160px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" onkeyup="busca_pessoa_aproximada(this, event);" /></td>
					
					<td>
						<select name='filtro_data' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" style='width:136px' onKeyDown="listar(event)">
							<option value='DataAbertura' <?php echo compara($localData,"DataAbertura","selected='selected'",""); ?>><?php echo dicionario(388); ?></option>
							<option value='DataFechamento' <?php echo compara($localData,"DataFechamento","selected='selected'",""); ?>><?php echo dicionario(951); ?></option>
						</select>
					</td>
					<td><input type='text' name='filtro_data_inicio' value='<?php echo $localDataInicio; ?>' style='width:121px' maxlength='19' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'dateHora')" onKeyDown="listar(event);" /></td>
					<td><input type='text' name='filtro_data_fim' value='<?php echo $localDataFim; ?>' style='width:121px' maxlength='19' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'dateHora')" onKeyDown="listar(event);" /></td>
					<td>
						<select name='filtro_id_status' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" style='width:115px' onKeyDown="listar(event)">
							<option value=''>Todos</option>	
							<?php 
								$sql = "SELECT IdParametroSistema, ValorParametroSistema FROM ParametroSistema where IdGrupoParametroSistema = 243 ORDER BY ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								
								while($lin = @mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],"CONVERT");
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus, $lin[IdParametroSistema], "selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>													
						</select>
					</td>	
					<td><input type='text' value='<?php echo $Limit; ?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?php echo menu_acesso_rapido(56); ?>
	</div>
	<script type='text/javascript'>
		enterAsTab(document.forms.filtro);
	</script>