<?
	if($localOrdem == ''){						$localOrdem = "IdArquivoRemessa";	}
	if($localOrdemDirecao == ''){				$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");	
	
	if($localTipoDado == ''){					$localTipoDado = 'number';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_arquivo_remessa.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Local de Cobrança</td>
					<td>Data Iní. Remessa</td>
					<td>Data Térm. Remessa</td>
					<td>Status</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td>
						<select name='filtro_local_cob' style='width:220px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select 
											IdLocalCobranca, 
											DescricaoLocalCobranca 
										from 
											LocalCobranca 
										where 
											IdLoja = $local_IdLoja and
											(
												IdTipoLocalCobranca = 3 or
												IdTipoLocalCobranca = 4 or
												IdTipoLocalCobranca = 6
											)
										order by 
											DescricaoLocalCobranca;";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[DescricaoLocalCobranca] = url_string_xsl($lin[DescricaoLocalCobranca],'convert');
									
									echo "<option value='$lin[IdLocalCobranca]' ".compara($localIdLocalCobranca,$lin[IdLocalCobranca],"selected='selected'","").">$lin[DescricaoLocalCobranca]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localDataInicio?>' name='filtro_data_inicio' size='17' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')"  maxlength='10'  onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localDataTermino?>' name='filtro_data_termino' size='17' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" maxlength='10'  onKeyDown='listar(event)'/></td>
					<td>
						<select name='filtro_status' style='width:140px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=80";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao'/></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(35);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
	
