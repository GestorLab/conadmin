<?
	if($localOrdem == ''){							$localOrdem = "DescricaoCodigoInterno";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){						$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_codigo_interno.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Grupo Código Interno</td>
					<td>Nome Código Interno</td>
					<td>Valor Código Interno</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td>
						<select name='filtro_grupo_codigo_interno' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:300px' onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdGrupoCodigoInterno, DescricaoGrupoCodigoInterno from GrupoCodigoInterno order by DescricaoGrupoCodigoInterno";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[DescricaoGrupoCodigoInterno] = url_string_xsl($lin[DescricaoGrupoCodigoInterno],'convert');
									echo "<option value='$lin[IdGrupoCodigoInterno]' ".compara($localGrupoCodigoInterno,$lin[IdGrupoCodigoInterno],"selected='selected'","").">$lin[DescricaoGrupoCodigoInterno]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localCodigoInterno?>' name='filtro_descricao_codigo_interno' size='30' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localValorCodigoInterno?>' name='filtro_valor_codigo_interno' size='20' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao' /></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(5);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
