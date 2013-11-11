<?
	if($localOrdem == ''){						$localOrdem = "MesReferencia";	}
	if($localOrdemDirecao == ''){				$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){					$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_nf_2_via_eletronica_remessa.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table class='filtro'>
				<tr>
					<td>Modelo</td>
					<td>Período de Apuração</td>
					<td>Status</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td>
						<select name='filtro_nota_fiscal_layout' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:397px' onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "	select
												IdNotaFiscalLayout,
												DescricaoNotaFiscalLayout
											from
												NotaFiscalLayout
											where
												IdNotaFiscalLayout in (1)
											order by
												DescricaoNotaFiscalLayout";
								$res = @mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[DescricaoNotaFiscalLayout],'convert');
									echo "<option value='$lin[IdNotaFiscalLayout]' ".compara($localIdNotaFiscalLayout,$lin[IdNotaFiscalLayout],"selected='selected'","").">$lin[DescricaoNotaFiscalLayout]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<input type='text' value='<?=$localMesReferencia?>' name='filtro_mes_referencia' style='width: 120px' maxlength='7' onkeypress="mascara(this,event,'mes')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/>
					</td>
					<td>
						<select name='filtro_status' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:170px' onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=137 order by ValorParametroSistema";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao' /></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(39);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
