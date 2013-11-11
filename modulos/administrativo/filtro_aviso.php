<?
	if($localOrdem == ''){					$localOrdem = "Data";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){				$localTipoDado = 'number';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_aviso.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Título Aviso</td>
					<td>Forma Aviso</td>
					<td>Data Expiração</td>
					<td>Visível</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' name='filtro_titulo' value='<?=$localTituloAviso?>' style='width:220px' maxlength='50' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td>
						<select name='filtro_aviso_forma' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:200px' onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdAvisoForma, DescricaoAvisoForma from AvisoForma";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[DescricaoAvisoForma] = url_string_xsl($lin[DescricaoAvisoForma],'convert');
									
									echo "<option value='$lin[IdAvisoForma]' ".compara($localIdAvisoForma,$lin[IdAvisoForma],"selected='selected'","").">$lin[DescricaoAvisoForma]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' name='filtro_data_expiracao' value='<?=$localDataExpiracao?>' style='width:100px' maxlength='10' onkeypress="mascara(this,event,'date')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>	
					<td>
						<select name='filtro_visivel' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:100px' onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<option value='1' <?=compara($localVisivel,'1',"selected='selected'","")?>>Sim</option>
							<option value='2' <?=compara($localVisivel,'2',"selected='selected'","")?>>Não</option>
						</select>
					</td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
				</tr>	
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(37);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
