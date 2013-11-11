<?
	if($localOrdem == ''){						$localOrdem = "DescricaoGrupoDevice";	}
	if($localOrdemDirecao == ''){				$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){					$localTipoDado = 'text';	}
?>

	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_poste.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table class='filtro'>
				<tr>
					<td>Nome Poste</td>
					<td><?=dicionario(82)?></td>
					<td><?=dicionario(152)?></td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localNomePoste?>' name='filtro_nome_poste' style="width: 200px;" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' tabindex='1' /></td>
					<td class='campo' >
						<select name="fitro_Tipo_Poste" style="width: 190px;" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='2'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "SELECT
											IdPosteTipo,
											DescricaoPosteTipo
										FROM 
											PosteTipo";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){									
									$lin[DescricaoPosteTipo] = url_string_xsl($lin[DescricaoPosteTipo],'convert');									
									echo "<option value='$lin[IdPosteTipo]' ".compara($localTipoPoste,$lin[IdPosteTipo],"selected='selected'","").">$lin[DescricaoPosteTipo]</option>";
								}
							?>
						</select>						
					</td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)" tabindex='3'/></td>
					<td><input type='submit' value='<?=dicionario(166)?>' class='botao' tabindex='4' /></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(59);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>