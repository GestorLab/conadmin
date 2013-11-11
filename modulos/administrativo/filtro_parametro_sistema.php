<?
	if($localOrdem == ''){							$localOrdem = "DescricaoGrupoParametroSistema";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){						$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_parametro_sistema.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Grupo Parâmetro Sistema</td>
					<td>Nome Parâmetro Sistema</td>
					<td>Valor Parâmetro</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td>
						<select name='filtro_grupo_parametro_sistema' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:300px' onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdGrupoParametroSistema, DescricaoGrupoParametroSistema from GrupoParametroSistema order by DescricaoGrupoParametroSistema";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[DescricaoGrupoParametroSistema] = url_string_xsl($lin[DescricaoGrupoParametroSistema],'convert');
									echo "<option value='$lin[IdGrupoParametroSistema]' ".compara($localGrupoParametroSistema,$lin[IdGrupoParametroSistema],"selected='selected'","").">$lin[DescricaoGrupoParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localParametroSistema?>' name='filtro_descricao_parametro_sistema' size='30' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localValorParametroSistema?>' name='filtro_valor_parametro_sistema' size='20' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao' /></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(4);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
