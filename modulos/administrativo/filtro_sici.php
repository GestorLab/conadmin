<?
	if($localOrdem == ""){
		$localOrdem = "PeriodoApuracao";
	}
	
	if($localOrdemDirecao == ""){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	if($localTipoDado == ""){
		$localTipoDado = "number";
	}
	
	LimitVisualizacao("filtro");
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_sici.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Período de Apuração</td>
					<td>Número do FISTEL</td>
					<td>Status</td>
					<td>Qtd.</td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td>
						<select name='filtro_periodo_apuracao' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:136px' onKeyDown='listar(event)' onChange="addGrupo(this.value); inserirAtributo(this.value); addAtribute()">
							<option value=''></option>
							<?
								$sql = "SELECT SICI.PeriodoApuracao FROM SICI ORDER BY SICI.PeriodoApuracao;";
								$res = mysql_query($sql,$con);
								
								while($lin = mysql_fetch_array($res)){
									$lin[PeriodoApuracao] = dataConv($lin[PeriodoApuracao], "Y-m", "m/Y");
									echo "<option value='$lin[PeriodoApuracao]' ".compara($localPeriodoApuracao,$lin[PeriodoApuracao],"selected='selected'","").">$lin[PeriodoApuracao]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localNumeroFistel?>' name='filtro_numero_fistel' style='width:130px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" maxlength='11' onKeyDown="listar(event)"/></td>
					<td>
						<select name='filtro_id_status' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" style='width:115px' onKeyDown="listar(event)">
							<option value=''>Todos</option>	
							<?
								$sql = "SELECT 
											IdParametroSistema, 
											ValorParametroSistema 
										FROM 
											ParametroSistema 
										WHERE 
											IdGrupoParametroSistema = 240 
										ORDER BY
											ValorParametroSistema;";
								$res = @mysql_query($sql,$con);
								
								while($lin = @mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo"<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>	
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)" /></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?=menu_acesso_rapido(55)?>
	</div>
	<script type='text/javascript'>
		enterAsTab(document.forms.filtro);
	</script>